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
        Schema::create('survey_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // The Answer Storage
            // 1. Simple value for Text, Dropdown, Yes/No, Date
            $table->text('answer')->nullable();

            // 2. Numeric value for Ratings (Easier for generating averages/reports)
            $table->integer('rating')->nullable();

            // 3. JSON for complex types: Multi-checkbox, Popups, "With Extra" data
            // This stores things like {"choice": "yes", "extra_text": "Details...", "selected_ids": [1, 2]}
            $table->json('properties')->nullable();

            $table->timestampsTz();
            $table->softDeletes();

            // Indexing for faster reporting
            $table->index(['survey_id', 'question_id']);
            $table->unique(['user_id', 'survey_id', 'question_id'], 'user_survey_question_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_answers');
    }
};
