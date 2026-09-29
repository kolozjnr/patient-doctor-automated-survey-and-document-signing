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
        Schema::table('general_answers', function (Blueprint $table) {
        $table->unsignedBigInteger('option_id')->nullable()->after('question_id');
        
        // Optional: Add a foreign key for data integrity
        $table->foreign('option_id')->references('id')->on('question_options')->onDelete('cascade');

        });

        Schema::table('survey_answers', function (Blueprint $table) {
        $table->unsignedBigInteger('option_id')->nullable()->after('question_id');
        
        // Optional: Add a foreign key for data integrity
        $table->foreign('option_id')->references('id')->on('question_options')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_answers', function (Blueprint $table) {
            $table->dropForeign(['option_id']);
            $table->dropColumn('option_id');
        });
        
        Schema::table('general_answers', function (Blueprint $table) {
            $table->dropForeign(['option_id']);
            $table->dropColumn('option_id');
        });
    }
};
