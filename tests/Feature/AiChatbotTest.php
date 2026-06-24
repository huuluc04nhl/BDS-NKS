<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\AiChatMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;

class AiChatbotTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_ai_chat_saves_messages_and_calls_gemini()
    {
        // 1. Mock Gemini API response
        Http::fake([
            'generativelanguage.googleapis.com/*' => Http::response([
                'candidates' => [
                    [
                        'content' => [
                            'parts' => [
                                ['text' => 'Chào quý khách, tôi có thể hỗ trợ gì cho bạn về BĐS NKS?']
                            ]
                        ]
                    ]
                ]
            ], 200)
        ]);

        $payload = [
            'message' => 'Tôi muốn tìm thuê nhà Quận 7',
            'session_id' => 'test-session-123',
            'email' => 'customer@example.com',
            'area_context' => 'Quận 7'
        ];

        // 2. Call the API endpoint
        $response = $this->postJson('/nks-api/ai/chat', $payload);

        // 3. Assert response status and structure
        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'reply' => 'Chào quý khách, tôi có thể hỗ trợ gì cho bạn về BĐS NKS?'
        ]);

        // 4. Assert messages are stored in DB
        $this->assertDatabaseHas('ai_chat_messages', [
            'session_id' => 'test-session-123',
            'role' => 'user',
            'content' => 'Tôi muốn tìm thuê nhà Quận 7'
        ]);

        $this->assertDatabaseHas('ai_chat_messages', [
            'session_id' => 'test-session-123',
            'role' => 'model',
            'content' => 'Chào quý khách, tôi có thể hỗ trợ gì cho bạn về BĐS NKS?'
        ]);
    }

    public function test_api_ai_chat_history_retrieval()
    {
        // 1. Seed some messages
        AiChatMessage::create([
            'session_id' => 'test-session-123',
            'role' => 'user',
            'content' => 'Hello'
        ]);

        AiChatMessage::create([
            'session_id' => 'test-session-123',
            'role' => 'model',
            'content' => 'Hi there'
        ]);

        // 2. Retrieve history
        $response = $this->getJson('/nks-api/ai/chat/history?session_id=test-session-123');

        // 3. Assert success and message count
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'messages'
        ]);

        $this->assertCount(2, $response->json('messages'));
    }
}
