<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('auth_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id')->index();
            $table->string('refresh_token_hash')->unique();
            $table->timestamp('expires_at');
            $table->timestamp('absolute_expires_at');
            $table->timestamp('revoked_at')->nullable()->index();
            $table->string('ip_address')->index();
            $table->text('user_agent');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('auth_sessions');
    }
};
