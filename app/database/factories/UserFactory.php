<?php

namespace Database\Factories;

use App\Models\User;
use App\Enums\UserRole;
use App\Enums\AccountStatus;
use Illuminate\Database\Eloquent\Factories\Factory;


class UserFactory extends Factory
{
    /**
     * Связываем factory с моделью User
     */
    protected $model = User::class;

    /**
     * Базовое состояние пользователя (default state)
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name'  => fake()->lastName(),
            'username' => fake()->unique()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'password' => 'password123',
            'birth_date' => fake()->date(),
            'bio' => null,
            'avatar_url' => null,
            'user_role' => UserRole::USER,
            'account_status' => AccountStatus::ACTIVE,
        ];
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'user_role' => UserRole::ADMIN,
        ]);
    }

    public function blocked(): static
    {
        return $this->state(fn () => [
            'account_status' => AccountStatus::BLOCKED,
            'blocked_at' => now(),
            'blocked_reason' => 'Created by factory state',
        ]);
    }

    public function verified(): static
    {
        return $this->state(fn () => [
            'email_verified_at' => now(),
        ]);
    }
}
