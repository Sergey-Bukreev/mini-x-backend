<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_successfully(): void
    {
        // 1 создаём пользователя через factory
        $user = User::factory()->create();

        // 2 отправляем запрос на login
        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // 3 проверяем ответ
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'access_token',
            'refresh_token',
            'user',
        ]);

        // 4 проверяем что refresh session создана в БД
        $this->assertDatabaseHas('auth_sessions', [
            'user_id' => $user->id,
        ]);
    }
}
