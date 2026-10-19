<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        $payload = [
            'name' => 'Nia Rahma',
            'email' => 'nia@sekolah.test',
            'password' => 'secret123',
            'password_confirmation' => 'secret123',
        ];

        $this->postJson('/api/v1/register', $payload)->assertCreated();
        $this->assertDatabaseHas('users', ['email' => 'nia@sekolah.test']);
    }

    public function test_user_can_login_and_receive_token(): void
    {
        $user = User::factory()->create([
            'email' => 'login@sekolah.test',
            'password' => 'secret123',
        ]);

        $this->postJson('/api/v1/login', [
            'email' => 'login@sekolah.test',
            'password' => 'secret123',
        ])
            ->assertOk()
            ->assertJsonStructure(['token', 'user']);
    }

    public function test_login_fails_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'email' => 'gagal@sekolah.test',
            'password' => 'secret123',
        ]);

        $this->postJson('/api/v1/login', [
            'email' => 'gagal@sekolah.test',
            'password' => 'salah',
        ])->assertStatus(401);
    }

    public function test_authenticated_user_can_access_me(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->getJson('/api/v1/me', [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])
            ->assertOk()
            ->assertJsonPath('email', $user->email);
    }

    public function test_logout_revokes_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test')->plainTextToken;

        $this->postJson('/api/v1/logout', [], [
            'Authorization' => "Bearer {$token}",
            'Accept' => 'application/json',
        ])->assertOk();

        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}
