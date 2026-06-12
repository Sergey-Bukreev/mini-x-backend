<?php

namespace App\Services\Auth;

use App\Models\User;

use Illuminate\Support\Str;
use App\Models\AuthSession;
use Illuminate\Http\Request;

class AuthTokenService
{
    // Создает запись refresh-сессии в базе.
    private function createSessionRecord(
        User $user,
        string $refreshToken,
        Request $request,
        mixed $absoluteExpiresAt
    ): AuthSession {
        return AuthSession::create([
            'user_id' => $user->id,
            'refresh_token_hash' => $this->hashRefreshToken($refreshToken),
            'expires_at' => now()->addDays(config('tokens.refresh.expires_in_days')),
            'absolute_expires_at' => $absoluteExpiresAt,
            'revoked_at' => null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    // Создает access token через Sanctum.
    public function createAccessToken(User $user): string
    {
        return $user
            ->createToken(
                config('tokens.access.name'),
                ['*'],
                now()->addMinutes(config('tokens.access.expires_in_minutes'))
            )
            ->plainTextToken;
    }

    // Генерирует новый refresh token для клиента.
    public function generateRefreshToken(): string
    {
        return Str::random(64);
    }

    // Хеширует refresh token перед сохранением или поиском в базе.
    public function hashRefreshToken(string $refreshToken): string
    {
        return hash('sha256', $refreshToken);
    }

    // Создает новую refresh-сессию
    public function createRefreshSession(User $user, string $refreshToken, Request $request): AuthSession
    {
        return $this->createSessionRecord(
            $user,
            $refreshToken,
            $request,
            now()->addDays(config('tokens.refresh.absolute_expires_in_days'))
        );
    }

    // Отзывает старую refresh-сессию и создает новую в рамках той же цепочки.
    public function rotateRefreshSession(AuthSession $authSession, User $user, string $newRefreshToken, Request $request): AuthSession
    {
        $authSession->update([
            'revoked_at' => now(),
        ]);


        return $this->createSessionRecord(
            $user,
            $newRefreshToken,
            $request,
            $authSession->absolute_expires_at
        );
    }

    // Ищет refresh-сессию по сырому refresh token.
    public function findSessionByRefreshToken(string $refreshToken): ?AuthSession
    {
        return AuthSession::where(
            'refresh_token_hash',
            $this->hashRefreshToken($refreshToken)
        )->first();
    }
}
