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
            'subject' => 'Thông báo: Tài khoản của bạn đã bị khóa tạm thời'
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
            'subject' => 'Chào mừng bạn đến với BDS NKS - Hệ thống Bất Động Sản Chính Chủ'
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
}
