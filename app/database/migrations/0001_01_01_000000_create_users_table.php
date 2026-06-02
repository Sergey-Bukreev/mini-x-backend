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
        DB::statement(sprintf(
            "CREATE TYPE user_role AS ENUM ('%s', '%s')",
            UserRole::USER->value,
            UserRole::ADMIN->value
        ));

        DB::statement(sprintf(
            "CREATE TYPE account_status AS ENUM ('%s', '%s')",
            AccountStatus::ACTIVE->value,
            AccountStatus::BLOCKED->value
        ));

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

        $table->rememberToken();
        $table->timestamps();
        });

        DB::statement(sprintf(
            "ALTER TABLE users ADD COLUMN user_role user_role NOT NULL DEFAULT '%s'",
            UserRole::USER->value
        ));

        DB::statement(sprintf(
            "ALTER TABLE users ADD COLUMN account_status account_status NOT NULL DEFAULT '%s'",
            AccountStatus::ACTIVE->value
        ));
    }



    public function down(): void
    {
        Schema::dropIfExists('users');
        DB::statement('DROP TYPE IF EXISTS account_status');
        DB::statement('DROP TYPE IF EXISTS user_role');
    }
};
