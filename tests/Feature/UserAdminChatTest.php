<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Message;
use App\Models\EmailLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class UserAdminChatTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $renter;

    protected function setUp(): void
    {
        parent::setUp();

        // Prevent real external calls during tests
        Http::fake([
            'account.nks.vn/api/nks/user/login' => Http::response([
                'success' => false,
                'code' => 500,
                'error' => 'Tài khoản không tồn tại',
                'message' => 'Unauthorized'
            ], 200)
        ]);

        // Create Default Users
        $this->admin = User::create([
            'name' => 'NKS Admin',
            'email' => 'admin@nks.vn',
            'password' => bcrypt('admin123'),
            'role' => 'admin',
            'status' => 'active'
        ]);

        $this->renter = User::create([
            'name' => 'Duy renter',
            'email' => 'duy@example.com',
            'password' => bcrypt('123456'),
            'role' => 'renter',
            'status' => 'active'
        ]);
    }

    /**
     * Test admin can view users list.
     */
    public function test_admin_can_view_users_list()
    {
        $response = $this->getJson("/nks-api/admin/users?admin_id={$this->admin->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['success', 'users']);
    }

    /**
     * Test non-admin cannot view users list.
     */
    public function test_non_admin_cannot_view_users_list()
    {
        $response = $this->getJson("/nks-api/admin/users?admin_id={$this->renter->id}");

        $response->assertStatus(403);
    }

    /**
     * Test admin can block and unblock user.
     */
    public function test_admin_can_toggle_user_status()
    {
        // Block user
        $response = $this->postJson("/nks-api/admin/users/toggle-status/{$this->renter->id}", [
            'admin_id' => $this->admin->id
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('user.status', 'blocked');

        $this->assertEquals('blocked', $this->renter->fresh()->status);

        // Verify status email log was created
        $this->assertDatabaseHas('email_logs', [
            'user_id' => $this->renter->id,
            'subject' => '⚠️ Thông báo quan trọng: Tài khoản BDS NKS tạm thời bị khóa'
        ]);

        // Attempt login while blocked
        $loginResponse = $this->postJson('/nks-api/login', [
            'email' => 'duy@example.com',
            'password' => '123456'
        ]);
        $loginResponse->assertStatus(403);

        // Unblock user
        $response2 = $this->postJson("/nks-api/admin/users/toggle-status/{$this->renter->id}", [
            'admin_id' => $this->admin->id
        ]);
        $response2->assertStatus(200)
            ->assertJsonPath('user.status', 'active');

        $this->assertEquals('active', $this->renter->fresh()->status);
    }

    /**
     * Test user registration generates welcome email log.
     */
    public function test_user_registration_logs_email()
    {
        $response = $this->postJson('/nks-api/register', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'role' => 'renter'
        ]);

        $response->assertStatus(200);

        $john = User::where('email', 'john@example.com')->first();
        $this->assertNotNull($john);

        $this->assertDatabaseHas('email_logs', [
            'user_id' => $john->id,
            'recipient_email' => 'john@example.com',
            'subject' => '✨ Chào mừng bạn đến với BDS NKS - Ngôi nhà mới, hành trình mới!'
        ]);
    }

    /**
     * Test chat functionality.
     */
    public function test_chat_functionality()
    {
        // Send chat message as renter
        $response = $this->postJson('/nks-api/chat/send', [
            'sender_id' => $this->renter->id,
            'message' => 'Hello support!'
        ]);

        $response->assertStatus(200);

        // Assert message exists
        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->renter->id,
            'message' => 'Hello support!'
        ]);

        // Assert chatbot auto-reply was generated
        $this->assertDatabaseHas('messages', [
            'sender_id' => $this->admin->id,
            'receiver_id' => $this->renter->id
        ]);

        // Fetch chat history as renter
        $historyResponse = $this->getJson("/nks-api/chat/history?user_id={$this->renter->id}");
        $historyResponse->assertStatus(200)
            ->assertJsonCount(2, 'messages'); // 1 user msg + 1 chatbot msg
    }

    /**
     * Test cancelling an appointment generates confirmation and owner alert emails.
     */
    public function test_cancel_appointment_sends_emails()
    {
        // Setup appointment
        $appt = \App\Models\Appointment::create([
            'user_id' => $this->renter->id,
            'property_id' => '91',
            'appt_name' => 'Duy renter',
            'appt_phone' => '0912345678',
            'email' => 'duy@example.com',
            'appointment_date' => '2026-06-25',
            'appointment_time' => '14:30:00',
            'status' => 'confirmed'
        ]);

        $this->actingAs($this->renter);

        $response = $this->postJson("/nks-api/appointments/cancel/{$appt->id}");
        $response->assertStatus(200);

        $this->assertDatabaseMissing('appointments', ['id' => $appt->id]);

        // Email to renter
        $this->assertDatabaseHas('email_logs', [
            'user_id' => $this->renter->id,
            'recipient_email' => 'duy@example.com',
            'subject' => '❌ [BDS NKS] Xác nhận hủy lịch hẹn xem nhà thành công'
        ]);

        // Email to host/owner (91 points to Sunny with email nks.diaocchinhchu@nks.vn in our mock rsitems)
        $this->assertDatabaseHas('email_logs', [
            'recipient_email' => 'nks.diaocchinhchu@nks.vn',
            'subject' => '⚠️ [BDS NKS] Khách hàng đã hủy lịch hẹn xem nhà'
        ]);
    }

    /**
     * Test upgrade host sends notification email.
     */
    public function test_upgrade_host_sends_email()
    {
        $response = $this->postJson('/nks-api/profile/upgrade-host', [
            'email' => $this->renter->email,
            'name' => 'Duy renter updated',
            'phone' => '0900000009'
        ]);

        $response->assertStatus(200);

        $this->assertEquals('owner', $this->renter->fresh()->role);

        $this->assertDatabaseHas('email_logs', [
            'user_id' => $this->renter->id,
            'recipient_email' => $this->renter->email,
            'subject' => '👑 [BDS NKS] Chúc mừng! Tài khoản của bạn đã được nâng cấp thành Chủ nhà'
        ]);
    }

    /**
     * Test proxy update CCCD persists to the local database.
     */
    public function test_update_cccd_persists_locally()
    {
        $this->actingAs($this->renter);

        $response = $this->postJson('/nks-api/nks/user/updateCccd', [
            'access_token' => 'mock_token_for_local_user',
            'front' => 'data:image/png;base64,mockfront',
            'back' => 'data:image/png;base64,mockback',
            'number' => '079123456789',
            'date' => '2025-05-12',
            'place' => 'Cuc Canh sat QLHC ve TTXH'
        ]);

        $response->assertStatus(200);

        $updatedRenter = $this->renter->fresh();
        $this->assertEquals('079123456789', $updatedRenter->id_number);
        $this->assertEquals('2025-05-12', $updatedRenter->id_date);
        $this->assertEquals('Cuc Canh sat QLHC ve TTXH', $updatedRenter->id_place);
    }

    /**
     * Test apiSessionSync does not downgrade host/owner or admin back to renter.
     */
    public function test_session_sync_does_not_downgrade_owner_role()
    {
        // Upgrade renter to owner locally
        $this->renter->update(['role' => 'owner']);

        // Mock remote NKS API returning the older renter role
        Http::fake([
            'account.nks.vn/api/nks/user' => Http::response([
                'success' => true,
                'user' => [
                    'id' => $this->renter->id,
                    'name' => $this->renter->name,
                    'email' => $this->renter->email,
                    'phone' => $this->renter->phone,
                    'role' => 'renter', // Older role on remote
                    'status' => 'active',
                    'point' => 50
                ]
            ], 200)
        ]);

        $response = $this->postJson('/nks-api/session/sync', [
            'access_token' => 'real_access_token_123',
            'user' => [
                'email' => $this->renter->email,
                'role' => 'owner'
            ]
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('user.role', 'owner'); // Role should be preserved!

        $this->assertEquals('owner', $this->renter->fresh()->role);
    }

    /**
     * Test apiLogin does not downgrade host/owner or admin back to renter.
     */
    public function test_login_does_not_downgrade_owner_role()
    {
        // Upgrade renter to owner locally
        $this->renter->update(['role' => 'owner']);

        // Mock remote NKS Login API returning the older renter role
        Http::fake([
            'account.nks.vn/api/nks/user/login' => Http::response([
                'success' => true,
                'access_token' => 'real_access_token_123',
                'user' => [
                    'id' => $this->renter->id,
                    'name' => $this->renter->name,
                    'email' => $this->renter->email,
                    'phone' => $this->renter->phone,
                    'role' => 'renter', // Older role on remote
                    'status' => 'active',
                    'point' => 50
                ]
            ], 200)
        ]);

        $response = $this->postJson('/nks-api/login', [
            'email' => $this->renter->email,
            'password' => '123456'
        ]);

        $response->assertStatus(200)
            ->assertJsonPath('user.role', 'owner'); // Role should be preserved!

        $this->assertEquals('owner', $this->renter->fresh()->role);
    }
}

