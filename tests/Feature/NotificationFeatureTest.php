<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;

class NotificationFeatureTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    // public function test_example(): void
    // {
    //     $response = $this->get('/');

    //     $response->assertStatus(200);
    // }

    public function test_notification_send()
    {
        // STEP 1: create user
        $user = User::factory()->create();

        // STEP 2: generate JWT token
        $token = JWTAuth::fromUser($user);

        // STEP 3: call API with Authorization header
        $response = $this->withHeaders([
            'Authorization' => "Bearer $token"
        ])->postJson('/api/v1/notifications/send', [
            'user_id' => $user->id,
            'type' => 'sms',
            'recipient' => '01890893098',
            'message' => 'Your bill is due',
            'metadata' => [
                'campaign' => 'billing_reminder'
            ]
        ]);

        // STEP 4: assertions
        $response->assertStatus(200)
                 ->assertJson(['status' => 'queued']);
    }
}
