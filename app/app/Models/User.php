<?php

namespace App\Models;

// Наша User-модель наследует встроенную auth-логику Laravel.
use Illuminate\Foundation\Auth\User as Authenticatable;
// Laravel сам будет создавать UUID при User::create()
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Sanctum\HasApiTokens;

use App\Enums\UserRole;
use App\Enums\AccountStatus;


/**
 * @property string $id
 * @property string $first_name
 * @property string $last_name
 * @property string $username
 * @property string $email
 * @property string $password
 * @property Carbon $birth_date
 * @property string|null $bio
 * @property UserRole $user_role
 * @property AccountStatus $account_status
 * @property string|null $avatar_url
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $blocked_at
 * @property string|null $blocked_reason
 * @property Carbon|null $last_login_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

class User extends Authenticatable {

use Notifiable;
use HasUuids;
use HasFactory;
use HasApiTokens;

/**
 * Переопределяем тип ключа, выключаем автоинкрементацию
 */
protected $keyType = "string";
public $incrementing = false;


    /**
     * Поля, которые пользователь может передавать при create/update.
     */
  protected $fillable = [
    'first_name',
    'last_name',
    'username',
    'email',
    'password',
    'birth_date',
    'bio',
      'avatar_url',
    ];

    /**
     * Поля, которые скрываются из JSON response.
     */
  protected $hidden = [
            'password',
            'remember_token',
        ];

    /**
     * Автоматическое приведение типов.
     */
  protected function casts(): array
            {
                return [
                    'user_role' => UserRole::class,
                    'account_status' => AccountStatus::class,
                    'birth_date' => 'date',
                    'email_verified_at' => 'datetime',
                    'blocked_at' => 'datetime',
                    'last_login_at' => 'datetime',
                    'password' => 'hashed',
                ];
            }
}
