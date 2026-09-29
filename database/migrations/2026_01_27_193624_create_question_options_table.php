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
        Schema::create('question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->cascadeOnDelete();
            $table->string('option_text', 255)->nullable();
            $table->decimal('option_value', 10, 2)->nullable();
            $table->integer('display_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->bigInteger('max_value')->nullable();
            $table->bigInteger('min_value')->nullable();
            $table->json('metadata')->nullable();
        });

        Schema::table('general_answers', function (Blueprint $table) {
            $table->unsignedBigInteger('question_option_id')->nullable()->after('question_id');
            $table->foreign('question_option_id')->references('id')->on('question_options');
            $table->decimal('numeric_answer_value', 10, 2)->nullable();
        });

        Schema::table('survey_answers', function (Blueprint $table) {
            $table->unsignedBigInteger('question_option_id')->nullable()->after('question_id');
            $table->foreign('question_option_id')->references('id')->on('question_options');
            $table->decimal('numeric_answer_value', 10, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_answers', function (Blueprint $table) {
            $table->dropForeign(['question_option_id']);
            $table->dropColumn(['question_option_id', 'numeric_answer_value']);
        });

        Schema::table('general_answers', function (Blueprint $table) {
            $table->dropForeign(['question_option_id']);
            $table->dropColumn(['question_option_id', 'numeric_answer_value']);
        });

        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['max_value', 'min_value', 'metadata']);
        });
        Schema::dropIfExists('question_options');
    }
};
