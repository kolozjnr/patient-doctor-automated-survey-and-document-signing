<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('client_admins', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->string('country_code')->nullable();
            $table->string('phone')->nullable();
            $table->softDeletes();
            $table->rememberToken();
            $table->string('password');

            $table->timestampTz('created_at')->default(DB::raw('now()'));
            $table->timestampTz('updated_at')->default(DB::raw('now()'));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_admins');
    }
};
