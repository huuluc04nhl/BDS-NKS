<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Property;
use App\Models\Appointment;
use App\Models\SavedProperty;
use App\Models\Demand;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PropertyApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Fake the external NKS API to return our high-fidelity mock data and prevent network call failures
        Http::fake([
            'online.nks.vn/api/nks/rsitems' => Http::response([
                'success' => true,
                'data' => [
                    [
                        'id' => 91,
                        'title' => 'Nhà phố nguyên căn hẻm xe hơi, Lê Văn Sỹ, Phú Nhuận',
                        'slug' => 'nha-pho-nguyen-can-hem-xe-hoi-le-van-sy-phu-nhuan-91',
                        'featureimg' => 'https://images.unsplash.com/photo-1513694203232-719a280e022f',
                        'geolocation' => '10.7932,106.6710',
                        'price' => 25000000,
                        'rentprice' => 25000000,
                        'total_area' => 85.0,
                        'floors' => 3,
                        'rstype' => 'Nhà phố',
                        'bed' => 3,
                        'bath' => 3,
                        'province' => 'Thành phố Hồ Chí Minh',
                        'address' => '222 Lê Văn Sỹ, Phường Nhiêu Lộc, Quận Phú Nhuận, Thành phố Hồ Chí Minh',
                        'phone' => '0932030958',
                        'email' => 'nks.diaocchinhchu@nks.vn',
                        'sale' => [
                            'name' => 'Sunny',
                            'avatar' => 'https://data.nks.vn//storage/users/202110040100053107.png',
                            'phone' => '0932030958'
                        ],
                        'formatedPrice' => '25 triệu/tháng',
                        'formatedSqrPrice' => '294k/m²'
                    ]
                ]
            ], 200),
            'account.nks.vn/api/nks/user/login' => Http::response([
                'success' => true,
                'access_token' => 'mock_nks_access_token_123',
                'user' => [
                    'id' => 999,
                    'name' => 'Nguyen Van Owner',
                    'username' => 'owner_username',
                    'email' => 'owner@nks.vn',
                    'phone' => '0987654321',
                    'role' => 'owner',
                    'status' => 'active',
                    'point' => 100
                ]
            ], 200),
            'account.nks.vn/api/nks/user' => Http::response([
                'success' => true,
                'user' => [
                    'id' => 999,
                    'name' => 'Nguyen Van Owner',
                    'username' => 'owner_username',
                    'email' => 'owner@nks.vn',
                    'phone' => '0987654321',
                    'role' => 'owner',
                    'status' => 'active',
                    'point' => 100
                ]
            ], 200),
            'account.nks.vn/api/nks/user/updateInfo' => Http::response([
                'success' => true,
                'user' => [
                    'id' => 999,
                    'name' => 'Updated Name',
                    'username' => 'owner_username',
                    'email' => 'owner@nks.vn',
                    'phone' => '0987654321',
                    'role' => 'owner',
                    'status' => 'active',
                    'point' => 105
                ]
            ], 200),
            'online.nks.vn/api/nks/provinces' => Http::response([
                ['id' => 79, 'name' => 'Thành phố Hồ Chí Minh'],
                ['id' => 1, 'name' => 'Thành phố Hà Nội']
            ], 200),
            'online.nks.vn/api/nks/administratives' => Http::response([
                ['id' => 12227, 'name' => 'Phường Long Bình']
            ], 200)
        ]);
    }

    /**
     * Test user registration and login
     */
    public function test_user_registration_and_login()
    {
        // 1. Register
        $regResponse = $this->postJson('/nks-api/register', [
            'name' => 'Nguyen Van Owner',
            'email' => 'owner@nks.vn',
            'password' => 'password123',
            'role' => 'owner'
        ]);

        $regResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'user' => [
                    'name' => 'Nguyen Van Owner',
                    'email' => 'owner@nks.vn',
                    'role' => 'owner'
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'owner@nks.vn',
            'role' => 'owner'
        ]);

        // 2. Login
        $loginResponse = $this->postJson('/nks-api/login', [
            'email' => 'owner@nks.vn',
            'password' => 'password123'
        ]);

        $loginResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'user' => [
                    'email' => 'owner@nks.vn'
                ]
            ]);
    }

    /**
     * Test profile update and host upgrade
     */
    public function test_profile_update_and_host_upgrade()
    {
        $user = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@nks.vn',
            'role' => 'renter',
            'phone' => '0123456789'
        ]);

        // 1. Update Profile
        $updateResponse = $this->postJson('/nks-api/profile/update', [
            'email' => 'test@nks.vn',
            'name' => 'Updated Name',
            'phone' => '0987654321',
            'avatar' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=updated'
        ]);

        $updateResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'user' => [
                    'name' => 'Updated Name',
                    'phone' => '0987654321'
                ]
            ]);

        // 2. Upgrade Host
        $upgradeResponse = $this->postJson('/nks-api/profile/upgrade-host', [
            'email' => 'test@nks.vn',
            'name' => 'Host User',
            'phone' => '0987654321'
        ]);

        $upgradeResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'user' => [
                    'role' => 'owner'
                ]
            ]);

        $this->assertEquals('owner', $user->fresh()->role);
    }

    /**
     * Test owner property upload
     */
    public function test_owner_property_upload()
    {
        $user = User::factory()->create(['role' => 'owner']);

        $response = $this->postJson('/nks-api/properties/add', [
            'user_id' => $user->id,
            'title' => 'Nha dep quan 3',
            'address' => '321 Nguyen Dinh Chieu, Q3',
            'geolocation' => '10.7765,106.6850',
            'rstype' => 'Nhà phố',
            'transaction_type' => 'Cho thuê',
            'price' => 15000000,
            'total_area' => 50,
            'bed' => 2,
            'bath' => 2,
            'floors' => 2,
            'direction' => 'Nam',
            'feature_img' => 'https://images.unsplash.com/photo-1600585154340-be6161a56a0c',
            'description' => 'Mieu ta can nha dep'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseHas('properties', [
            'user_id' => $user->id,
            'title' => 'Nha dep quan 3'
        ]);

        // Fetch owner properties
        $fetchResponse = $this->getJson("/nks-api/properties/owner/{$user->id}");
        $fetchResponse->assertStatus(200)
            ->assertJsonCount(1, 'properties');
    }

    /**
     * Test appointments booking and fetching
     */
    public function test_appointments()
    {
        $user = User::factory()->create(['phone' => '0932030958']);

        $response = $this->postJson('/nks-api/appointments/book', [
            'user_id' => $user->id,
            'property_id' => '91', // Fallback property ID
            'appt_name' => 'John Doe',
            'appt_phone' => '0932030958',
            'appointment_date' => '2026-06-10',
            'appointment_time' => '14:00'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $apptId = $response->json('appointment.id');

        $this->assertDatabaseHas('appointments', [
            'id' => $apptId,
            'appt_name' => 'John Doe'
        ]);

        // Get appointments
        $getRes = $this->getJson("/nks-api/appointments/user/{$user->id}");
        $getRes->assertStatus(200)
            ->assertJsonCount(1, 'appointments')
            ->assertJsonPath('appointments.0.property_title', 'Nhà phố nguyên căn hẻm xe hơi, Lê Văn Sỹ, Phú Nhuận');

        // Cancel appointment
        $cancelRes = $this->postJson("/nks-api/appointments/cancel/{$apptId}");
        $cancelRes->assertStatus(200);

        $this->assertDatabaseMissing('appointments', ['id' => $apptId]);
    }

    /**
     * Test saved properties (favorites)
     */
    public function test_favorites()
    {
        $user = User::factory()->create();

        // Toggle favorite (save)
        $saveRes = $this->postJson('/nks-api/favorites/toggle', [
            'user_id' => $user->id,
            'external_property_id' => '91'
        ]);

        $saveRes->assertStatus(200)
            ->assertJson(['status' => 'saved']);

        $this->assertDatabaseHas('saved_properties', [
            'user_id' => $user->id,
            'external_property_id' => '91'
        ]);

        // Fetch favorites
        $getRes = $this->getJson("/nks-api/favorites/user/{$user->id}");
        $getRes->assertStatus(200)
            ->assertJsonCount(1, 'favorites')
            ->assertJsonPath('favorites.0.title', 'Nhà phố nguyên căn hẻm xe hơi, Lê Văn Sỹ, Phú Nhuận');

        // Toggle favorite again (remove)
        $removeRes = $this->postJson('/nks-api/favorites/toggle', [
            'user_id' => $user->id,
            'external_property_id' => '91'
        ]);

        $removeRes->assertStatus(200)
            ->assertJson(['status' => 'removed']);

        $this->assertDatabaseMissing('saved_properties', [
            'user_id' => $user->id,
            'external_property_id' => '91'
        ]);
    }

    /**
     * Test community demands
     */
    public function test_demands()
    {
        $user = User::factory()->create();

        $addRes = $this->postJson('/nks-api/demands/add', [
            'user_id' => $user->id,
            'title' => 'Can thue studio Q1',
            'transaction_type' => 'Thuê',
            'area' => '40m2',
            'budget' => '10 trieu',
            'content' => 'Studio dep thoang mat'
        ]);

        $addRes->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('demands', [
            'user_id' => $user->id,
            'title' => 'Can thue studio Q1'
        ]);

        // Get demands list
        $getRes = $this->getJson('/nks-api/demands/list');
        $getRes->assertStatus(200)
            ->assertJsonCount(1, 'demands')
            ->assertJsonPath('demands.0.title', 'Can thue studio Q1');
    }

    /**
     * Test session sync and automatic self-healing restore
     */
    public function test_session_sync_self_healing()
    {
        // 1. Send sync request for a non-existing user with local backup data
        $response = $this->postJson('/nks-api/session/sync', [
            'user' => [
                'name' => 'Self Healing User',
                'email' => 'healed@nks.vn',
                'phone' => '0999888777',
                'role' => 'owner',
                'avatar' => 'https://api.dicebear.com/7.x/adventurer/svg?seed=healed'
            ],
            'favorites' => [
                [
                    'id' => 91, // external property ID
                    'title' => 'External Prop',
                    'slug' => 'external-prop-91',
                    'featureimg' => '',
                    'address' => '',
                    'rstype' => 'Nhà phố',
                    'formatedPrice' => ''
                ]
            ],
            'appointments' => [
                [
                    'property_id' => '91',
                    'date' => '2026-06-15',
                    'time' => '10:00',
                    'name' => 'Self Healing User',
                    'phone' => '0999888777'
                ]
            ],
            'properties' => [
                [
                    'id' => 2001, // local property old ID
                    'title' => 'My Restored Property',
                    'slug' => 'my-restored-property',
                    'address' => 'District 1',
                    'price' => 15000000,
                    'total_area' => 50
                ]
            ]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'recreated' => true,
                'user' => [
                    'email' => 'healed@nks.vn',
                    'role' => 'owner'
                ]
            ]);

        // 2. Assert database user got recreated
        $this->assertDatabaseHas('users', [
            'email' => 'healed@nks.vn',
            'role' => 'owner'
        ]);

        // 3. Assert properties, favorites, and appointments got restored
        $user = User::where('email', 'healed@nks.vn')->first();
        
        $this->assertDatabaseHas('properties', [
            'user_id' => $user->id,
            'title' => 'My Restored Property'
        ]);

        $this->assertDatabaseHas('saved_properties', [
            'user_id' => $user->id,
            'external_property_id' => '91'
        ]);

        $this->assertDatabaseHas('appointments', [
            'user_id' => $user->id,
            'property_id' => '91',
            'appointment_date' => '2026-06-15 00:00:00'
        ]);
    }

    /**
     * Test updating and deleting a property
     */
    public function test_property_update_and_delete()
    {
        $user = User::factory()->create(['role' => 'owner']);
        $property = Property::create([
            'user_id' => $user->id,
            'title' => 'Original Title',
            'slug' => 'original-title-123',
            'address' => '123 Go Vap',
            'geolocation' => '10.8,106.6',
            'rstype' => 'Căn hộ',
            'transaction_type' => 'Cho thuê',
            'price' => 10000000,
            'formated_price' => '10 triệu/tháng',
            'total_area' => 45,
            'bed' => 1,
            'bath' => 1,
            'floors' => 1,
            'feature_img' => 'http://img.jpg',
            'is_verified' => true
        ]);

        // 1. Update property
        $updateResponse = $this->postJson("/nks-api/properties/update/{$property->id}", [
            'user_id' => $user->id,
            'title' => 'Updated Title',
            'address' => '123 Binh Thanh',
            'geolocation' => '10.8,106.6',
            'rstype' => 'Căn hộ',
            'transaction_type' => 'Cho thuê',
            'price' => 12000000,
            'total_area' => 45,
            'bed' => 1,
            'bath' => 1,
            'floors' => 1,
            'feature_img' => 'http://img.jpg',
            'description' => 'Updated description'
        ]);

        $updateResponse->assertStatus(200)
            ->assertJson([
                'success' => true,
                'property' => [
                    'title' => 'Updated Title',
                    'address' => '123 Binh Thanh',
                    'price' => 12000000
                ]
            ]);

        $this->assertDatabaseHas('properties', [
            'id' => $property->id,
            'title' => 'Updated Title'
        ]);

        // 2. Delete property
        $deleteResponse = $this->deleteJson("/nks-api/properties/delete/{$property->id}?user_id={$user->id}");

        $deleteResponse->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseMissing('properties', [
            'id' => $property->id
        ]);
    }

    /**
     * Test admin user management endpoints
     */
    public function test_admin_user_management()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $renter = User::factory()->create(['role' => 'renter', 'name' => 'Regular Renter', 'email' => 'renter@nks.vn']);

        // 1. Try to fetch user listing as renter (should return 403)
        $fetchRenterResponse = $this->getJson("/nks-api/admin/users?admin_id={$renter->id}");
        $fetchRenterResponse->assertStatus(403);

        // 2. Fetch user listing as admin (should return 200)
        $fetchAdminResponse = $this->getJson("/nks-api/admin/users?admin_id={$admin->id}");
        $fetchAdminResponse->assertStatus(200)
            ->assertJson([
                'success' => true
            ])
            ->assertJsonFragment(['email' => 'renter@nks.vn'])
            ->assertJsonFragment(['email' => $admin->email]);

        // 5. Try to delete own account as admin (should return 400)
        $deleteSelfResponse = $this->deleteJson("/nks-api/admin/users/delete/{$admin->id}?admin_id={$admin->id}");
        $deleteSelfResponse->assertStatus(400);

        // 6. Try to delete renter as renter (should return 403)
        $deleteAsRenterResponse = $this->deleteJson("/nks-api/admin/users/delete/{$renter->id}?admin_id={$renter->id}");
        $deleteAsRenterResponse->assertStatus(403);

        // 7. Delete renter as admin (should return 200)
        $deleteAsAdminResponse = $this->deleteJson("/nks-api/admin/users/delete/{$renter->id}?admin_id={$admin->id}");
        $deleteAsAdminResponse->assertStatus(200)
            ->assertJson([
                'success' => true
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $renter->id
        ]);
    }
}

