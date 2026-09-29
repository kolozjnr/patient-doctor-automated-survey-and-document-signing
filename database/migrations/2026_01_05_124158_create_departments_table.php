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
        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('category')->nullable();
            $table->string('status')->default('active');
             $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
        });

        Schema::create('department_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('department_id')->constrained()->cascadeOnDelete();
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();
            $table->unique(['user_id', 'department_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_user');
        Schema::dropIfExists('departments');
    }
};
