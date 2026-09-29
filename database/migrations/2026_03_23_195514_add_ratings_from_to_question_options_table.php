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
        Schema::table('question_options', function (Blueprint $table) {
             $table->text('rating_first_text')->nullable()->after('option_value');
            $table->text('rating_last_text')->nullable()->after('rating_first_text');
        });

        Schema::table('surveys', function (Blueprint $table) {
            $table->date('available_date')->nullable()->after('is_completed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surveys', function (Blueprint $table) {
             $table->dropColumn(['available_date']);
        });
        Schema::table('question_options', function (Blueprint $table) {
             $table->dropColumn(['rating_first_text', 'rating_last_text']);
             $table->string('option_text')->nullable(false)->change();
        });
    }
};
