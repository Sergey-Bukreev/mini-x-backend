<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
/**
 * @property string $id
 * @property string $user_id
 * @property string $refresh_token_hash
 * @property Carbon $expires_at
 * @property Carbon $absolute_expires_at
 * @property Carbon|null $revoked_at
 * @property string $ip_address
 * @property string $user_agent
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */

class AuthSession extends Model
{
    use HasUuids;

    /**
     * Переопределяем тип ключа, выключаем автоинкрементацию
     */
    protected $keyType = "string";
    public $incrementing = false;


    protected $fillable = [
        'user_id',
        'refresh_token_hash',
        'expires_at',
        'absolute_expires_at',
        'revoked_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array {
        return [
            'expires_at' => 'datetime',
            'absolute_expires_at' => 'datetime',
            'revoked_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
