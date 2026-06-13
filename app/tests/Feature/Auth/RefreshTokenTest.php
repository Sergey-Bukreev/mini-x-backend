<?php

namespace Tests\Feature\Auth;

use App\Enums\Messages\AuthMessages;
use App\Models\AuthSession;
use App\Models\User;
use App\Services\Auth\AuthTokenService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RefreshTokenTest extends TestCase
{
    use RefreshDatabase;

    private function tokenService(): AuthTokenService
    {
        return app(AuthTokenService::class);
    }

    public function test_user_can_refresh_access_token(): void
    {

        // 1 создаём пользователя через factory
        $user = User::factory()->create();

        // 2 отправляем запрос на login
        $loginResponse = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // 3 проверяем структуру и статус ответа
        $loginResponse->assertStatus(200);

        $loginResponse->assertJsonStructure([
            'access_token',
            'refresh_token',
            'user',
        ]);

        // 4 доестаем рефреш токен
        $refreshToken = $loginResponse->json('refresh_token');
        $refreshTokenHash = $this->tokenService()->hashRefreshToken($refreshToken);

        /// 5 отправляем запрос на рефреш
        $refreshResponse = $this->postJson('/api/v1/refresh', [
            'refresh_token' => $refreshToken,
        ]);

        /// 6 проверяем структуру и статус ответа
        $refreshResponse->assertStatus(200);

        $refreshResponse->assertJsonStructure([
            'access_token',
            'refresh_token',
            'user',
        ]);

        // 7 проверяем, что новый refresh token отличается от старого
        $this->assertNotSame($refreshToken, $refreshResponse->json('refresh_token'));

        // 8 проверяем, что старая сессия отозвана
        $oldSession = AuthSession::where('refresh_token_hash', $refreshTokenHash)->first();

        $this->assertNotNull($oldSession->revoked_at);

        // 9 проверяем, что новая сессия создана
        $this->assertDatabaseCount('auth_sessions', 2);
    }

    public function test_refresh_token_cannot_be_reused(): void
    {

        // 1 создаём пользователя через factory
        $user = User::factory()->create();

        // 2 отправляем запрос на login
        $loginResponse = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password123',
        ]);

        // 3 доестаем рефреш токен
        $refreshToken = $loginResponse->json('refresh_token');

        // 4 первый раз успешно обновляем токены
        $this->postJson('/api/v1/refresh', [
            'refresh_token' => $refreshToken,
        ])->assertStatus(200);

        // 5 повторно отправляем старый refresh token
        $response = $this->postJson('/api/v1/refresh', [
            'refresh_token' => $refreshToken,
        ]);

        // 6 проверяем, что старый refresh token больше не работает
        $response->assertStatus(401);

        $response->assertJson([
            'message' => __(AuthMessages::InvalidSession->value),
        ]);
    }

    public function test_refresh_fails_with_invalid_token(): void
    {

        // 1 отправляем несуществующий refresh token
        $response = $this->postJson('/api/v1/refresh', [
            'refresh_token' => 'invalid-refresh-token',
        ]);

        // 2 проверяем, что сессия не найдена
        $response->assertStatus(401);

        $response->assertJson([
            'message' => __(AuthMessages::InvalidSession->value),
        ]);
    }

    public function test_refresh_fails_when_session_is_revoked(): void
    {

        // 1 создаём пользователя через factory
        $user = User::factory()->create();

        // 2 создаём отозванную auth session
        $refreshToken = 'revoked-refresh-token';

        AuthSession::factory()
            ->forUser($user)
            ->forRefreshToken($refreshToken)
            ->revoked()
            ->create();

        // 3 отправляем запрос на рефреш
        $response = $this->postJson('/api/v1/refresh', [
            'refresh_token' => $refreshToken,
        ]);

        // 4 проверяем, что отозванная сессия не работает
        $response->assertStatus(401);

        $response->assertJson([
            'message' => __(AuthMessages::InvalidSession->value),
        ]);
    }

    public function test_refresh_fails_when_session_is_expired(): void
    {

        // 1 создаём пользователя через factory
        $user = User::factory()->create();

        // 2 создаём auth session с истекшим expires_at
        $refreshToken = 'expired-refresh-token';

        AuthSession::factory()
            ->forUser($user)
            ->forRefreshToken($refreshToken)
            ->expired()
            ->create();

        // 3 отправляем запрос на рефреш
        $response = $this->postJson('/api/v1/refresh', [
            'refresh_token' => $refreshToken,
        ]);

        // 4 проверяем, что истекшая сессия не работает
        $response->assertStatus(401);

        $response->assertJson([
            'message' => __(AuthMessages::InvalidSession->value),
        ]);
    }

    public function test_refresh_fails_when_absolute_session_lifetime_is_expired(): void
    {

        // 1 создаём пользователя через factory
        $user = User::factory()->create();

        // 2 создаём auth session с истекшим absolute_expires_at
        $refreshToken = 'absolute-expired-refresh-token';

        AuthSession::factory()
            ->forUser($user)
            ->forRefreshToken($refreshToken)
            ->absoluteExpired()
            ->create();

        // 3 отправляем запрос на рефреш
        $response = $this->postJson('/api/v1/refresh', [
            'refresh_token' => $refreshToken,
        ]);

        // 4 проверяем, что сессия с истекшим absolute lifetime не работает
        $response->assertStatus(401);

        $response->assertJson([
            'message' => __(AuthMessages::InvalidSession->value),
        ]);
    }

}
