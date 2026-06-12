<?php

namespace Database\Factories;

use App\Models\AuthSession;
use App\Models\User;
use App\Services\Auth\AuthTokenService;
use Illuminate\Database\Eloquent\Factories\Factory;


class AuthSessionFactory extends Factory
{
    /**
     * Связываем factory с моделью AuthSession
     */
    protected $model = AuthSession::class;

    /**
     * Доступ к токен сервису через приватный метод класса
     */
    private function tokenService(): AuthTokenService
    {
        return app(AuthTokenService::class);
    }

    public function definition(): array
    {
        $tokenService = $this->tokenService();
        $refreshToken = $tokenService->generateRefreshToken();

        // По умолчанию создаем активную refresh-сессию.
        return [
            'user_id' => User::factory(),
            'refresh_token_hash' => $tokenService->hashRefreshToken($refreshToken),
            'expires_at' => now()->addDays(config('tokens.refresh.expires_in_days')),
            'absolute_expires_at' => now()->addDays(config('tokens.refresh.absolute_expires_in_days')),
            'revoked_at' => null,
            'ip_address' => fake()->ipv4(),
            'user_agent' => fake()->userAgent(),
        ];
    }


    /**
     * Привязывает refresh-сессию к конкретному пользователю.
     */
    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }


    /**
     * Создает refresh-сессию для конкретного refresh token.
     */
    public function forRefreshToken(string $refreshToken): static
    {
        return $this->state(fn () => [
            'refresh_token_hash' => $this->tokenService()->hashRefreshToken($refreshToken),
        ]);
    }


    /**
     * Делает refresh-сессию отозванной.
     */
    public function revoked(): static
    {
        return $this->state(fn () => [
            'revoked_at' => now(),
        ]);
    }


    /**
     * Делает refresh-сессию истекшей по обычному сроку жизни.
     */
    public function expired(): static
    {
        return $this->state(fn () => [
            'expires_at' => now()->subMinute(),
            'absolute_expires_at' => now()->addDays(config('tokens.refresh.absolute_expires_in_days')),
        ]);
    }


    /**
     * Делает refresh-сессию истекшей по абсолютному сроку жизни.
     */
    public function absoluteExpired(): static
    {
        return $this->state(fn () => [
            'expires_at' => now()->addDays(config('tokens.refresh.expires_in_days')),
            'absolute_expires_at' => now()->subMinute(),
        ]);
    }
}
