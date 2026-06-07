<?php

use App\Enums\AccountStatus;
use App\Enums\UserRole;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
        $table->uuid('id')->primary();
        $table->string('first_name');
        $table->string('last_name');
        $table->string('username')->unique();
        $table->string('email')->unique();
        $table->string('password');
        $table->date('birth_date');
        $table->text('bio')->nullable();
        $table->string('avatar_url')->nullable();
        $table->timestamp('email_verified_at')->nullable();
        $table->timestamp('blocked_at')->nullable();
        $table->text('blocked_reason')->nullable();
        $table->timestamp('last_login_at')->nullable();
        $table->string('user_role')->default('user');
        $table->string('account_status')->default('active');

        $table->rememberToken();
        $table->timestamps();
        });

    }



    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
