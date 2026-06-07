<?php

namespace Tests\Feature\Auth;


use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register(): void
    {
        // 1. Данные для регистрации
        $payload = [
            'first_name' => 'Test User',
            'last_name' => 'Random',
            'username' =>'user1',
            'email'=>'test@example.com',
            'password' => 'Password123',
            'password_confirmation' => 'Password123',
            'birth_date' => '1990-01-01',
        ];

        // 2. Отправляем запрос на регистрацию
        $response = $this->postJson('/api/v1/register', $payload);

        // 3. Проверяем статус
        $response->assertStatus(201);

        // 4. Проверяем структуру ответа
        $response->assertJsonStructure([
            'user',
        ]);

        // 5. Проверяем, что пользователь создан в БД
        $this->assertDatabaseHas('users', [
            'email' => 'test@example.com',
        ]);

        // 6. Проверяем, что пароль реально захеширован
        $user = User::where('email', 'test@example.com')->first();

        $this->assertTrue(
            Hash::check('Password123', $user->password)
        );

    }
}
