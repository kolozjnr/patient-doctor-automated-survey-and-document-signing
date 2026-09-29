<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->string('send_to')->nullable()->after('description');
            $table->string('department_selected')->nullable()->after('description');
            $table->tinyInteger('priority')->default(3)->after('description')->comment('1=High, 2=Medium, 3=Low');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
            $table->dropColumn(['send_to', 'department_selected', 'priority']);
        });
    }
};
