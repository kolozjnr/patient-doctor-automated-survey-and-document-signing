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
        Schema::create('surveys', function (Blueprint $table) {
            $table->id();
            $table->uuid('batch_uuid')->index();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->date('survey_delivery_date')->nullable();
            $table->string('frequency')->nullable();
            $table->text('custom_reoccurrence')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamp('last_sent_at')->nullable();
            $table->string('cron_status')->default('active');
            $table->unsignedInteger('send_count')->default(0);
            $table->timestampsTz();
            $table->softDeletes();
        });

        Schema::create('survey_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->nullable()->constrained()->nullOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->text('response_text')->nullable();
            $table->integer('response_rating')->nullable();
            $table->string('response_yes_no')->nullable();
            $table->text('response_multi_options')->nullable();
            $table->text('response_multi_options_multi_answer')->nullable();
            $table->timestampsTz();
            $table->softDeletes();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_questions');
        Schema::dropIfExists('surveys');
    }
};
