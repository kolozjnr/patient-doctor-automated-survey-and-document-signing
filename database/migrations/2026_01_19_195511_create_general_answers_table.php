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
        Schema::create('general_answers', function (Blueprint $table) {
             $table->id();
            $table->text('answer')->nullable();
            $table->json('answers')->nullable();
            $table->text('optional_answer')->nullable();
            $table->json('table_answer')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('question_id')->nullable();

            $table->timestampsTz();
            $table->softDeletes();

            $table->index('question_id', 'idx_ga_question_id');
            $table->index('user_id', 'idx_ga_user_id');
            $table->index(['user_id', 'question_id'], 'idx_ga_user_question');

            $table->unique('id', 'general_answers_id_unique');
            $table->unique(['user_id', 'question_id'], 'general_answers_user_question_unique');

            $table->foreign('question_id', 'fk_general_answers_question_id')
                  ->references('id')->on('questions');

            $table->foreign('user_id', 'fk_general_answers_user_id')
                  ->references('id')->on('users');
    
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_answers');
    }
};
