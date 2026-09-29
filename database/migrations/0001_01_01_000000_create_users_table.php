<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('patient_id', 256)->unique()->nullable();
        $table->text('first_name')->nullable();
        $table->text('last_name')->nullable();
        $table->text('email')->nullable();
        $table->text('phone')->nullable();
        $table->string('country_code', 256)->nullable();
        $table->date('date_of_birth')->nullable();
        $table->timestamp('email_verified_at')->nullable();
        $table->enum('gender', ['Vrouwelijk', 'Mannelijk', 'Anders'])->nullable()->comment('Postgres enum: gender_type');
        $table->enum('status', ['Actief', 'Inactief', 'Nieuw', 'Intake Ingevuid', 'Intake Invullen', 'Ingelogd', 'Algemeen Gevuld'])->nullable()->comment('Postgres enum: user_status');

        $table->string('height', 256)->nullable();
        $table->string('weight', 256)->nullable();

        $table->boolean('intake_status')->default(false);
        $table->date('status_date')->nullable();
        $table->boolean('phone_verified')->default(false);
        $table->boolean('terms_agreed')->default(false);
        $table->boolean('daily_notification_frequency')->default(false);

        $table->text('consent')->nullable();
        $table->text('user_type')->nullable();
        $table->integer('system_reserve')->default(0);
        $table->string('password')->nullable();
        $table->timestampTz('created_at')->default(DB::raw('now()'));
        $table->timestampTz('updated_at')->default(DB::raw('now()'));
        $table->softDeletes();
        $table->rememberToken();
        $table->index('id', 'idx_users_id');
        $table->index('patient_id', 'idx_users_patient_id');
        $table->index('status', 'idx_users_status');
        $table->index('status_date', 'idx_users_status_date');
        $table->index(
            'daily_notification_frequency',
            'idx_users_daily_notification_frequency'
        );
    });

    /*
    | Keep ONLY the integrity trigger
    */
    DB::statement("CREATE TRIGGER set_updated_at_users BEFORE UPDATE ON users FOR EACH ROW EXECUTE FUNCTION update_updated_at_column();");


        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
