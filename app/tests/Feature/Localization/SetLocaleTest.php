<?php

namespace Tests\Feature\Localization;

use App\Enums\Messages\AuthMessages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SetLocaleTest extends TestCase
{
    use RefreshDatabase;

    private function translated(string $locale, string $key): string
    {
        $previousLocale = app()->getLocale();

        app()->setLocale($locale);
        $translated = __($key);
        app()->setLocale($previousLocale);

        return $translated;
    }

    public function test_api_returns_russian_message_when_accept_language_is_ru(): void
    {
        $response = $this
            ->withHeader('Accept-Language', 'ru')
            ->postJson('/api/v1/refresh', [
                'refresh_token' => 'invalid-token',
            ]);

        $response->assertStatus(401);

        $this->assertSame(
            $this->translated('ru', AuthMessages::InvalidSession->value),
            $response->json('message')
        );

        $this->assertNotSame(
            $this->translated('en', AuthMessages::InvalidSession->value),
            $response->json('message')
        );
    }

    public function test_api_returns_english_message_when_accept_language_is_en(): void
    {
        $response = $this
            ->withHeader('Accept-Language', 'en')
            ->postJson('/api/v1/refresh', [
                'refresh_token' => 'invalid-token',
            ]);

        $response->assertStatus(401);

        $this->assertSame(
            $this->translated('en', AuthMessages::InvalidSession->value),
            $response->json('message')
        );
    }

    public function test_api_uses_fallback_locale_when_accept_language_is_not_supported(): void
    {
        $response = $this
            ->withHeader('Accept-Language', 'de')
            ->postJson('/api/v1/refresh', [
                'refresh_token' => 'invalid-token',
            ]);

        $response->assertStatus(401);

        $this->assertSame(
            $this->translated(config('app.fallback_locale'), AuthMessages::InvalidSession->value),
            $response->json('message')
        );
    }
}
