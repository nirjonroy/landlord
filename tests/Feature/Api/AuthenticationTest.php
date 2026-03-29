<?php

namespace Tests\Feature\Api;

use App\Models\Admin;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\PersonalAccessToken;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_login_via_api_and_receive_a_token(): void
    {
        $user = User::factory()->create([
            'email' => 'api-user@example.com',
        ]);

        $response = $this->postJson('/api/user/login', [
            'email' => $user->email,
            'password' => 'password',
            'device_name' => 'android-user',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'token_type' => 'Bearer',
                'type' => 'user',
                'user' => [
                    'email' => 'api-user@example.com',
                ],
            ])
            ->assertJsonStructure([
                'token',
                'token_type',
                'type',
                'user' => ['id', 'name', 'email'],
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_admins_can_login_via_api_and_receive_a_token(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'api-admin@example.com',
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'password',
            'device_name' => 'android-admin',
        ]);

        $response
            ->assertOk()
            ->assertJson([
                'token_type' => 'Bearer',
                'type' => 'admin',
                'admin' => [
                    'email' => 'api-admin@example.com',
                ],
            ])
            ->assertJsonStructure([
                'token',
                'token_type',
                'type',
                'admin' => ['id', 'name', 'email'],
            ]);

        $this->assertDatabaseCount('personal_access_tokens', 1);
    }

    public function test_users_can_not_login_via_the_admin_api_endpoint(): void
    {
        User::factory()->create([
            'email' => 'wrong-table@example.com',
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => 'wrong-table@example.com',
            'password' => 'password',
        ]);

        $response
            ->assertStatus(422)
            ->assertJsonValidationErrors('email');
    }

    public function test_issued_user_token_can_access_authenticated_api_route(): void
    {
        $user = User::factory()->create([
            'email' => 'token-user@example.com',
        ]);

        $token = $this->postJson('/api/user/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->json('token');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/user');

        $response
            ->assertOk()
            ->assertJson([
                'email' => 'token-user@example.com',
            ]);
    }

    public function test_issued_admin_token_can_access_authenticated_api_route(): void
    {
        $admin = Admin::factory()->create([
            'email' => 'token-admin@example.com',
        ]);

        $token = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'password',
        ])->json('token');

        $response = $this->withHeader('Authorization', 'Bearer '.$token)->getJson('/api/user');

        $response
            ->assertOk()
            ->assertJson([
                'email' => 'token-admin@example.com',
            ]);
    }
}
