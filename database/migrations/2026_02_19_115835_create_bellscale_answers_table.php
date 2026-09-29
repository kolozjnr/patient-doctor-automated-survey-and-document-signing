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
        Schema::create('bellscale_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->foreignId('option_id')->nullable()->constrained('question_options')
                ->cascadeOnDelete();
            $table->text('answer')->nullable();       // for text type
            $table->decimal('answer_number', 8, 2)->nullable(); // for rating/scores
            $table->boolean('answer_boolean')->nullable(); // for yes/no
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bellscale_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamp('last_completed_at')->nullable();
            $table->timestamp('next_due_at')->nullable();
            $table->boolean('reminder_sent')->default(false);

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bellscale_cycles');
        Schema::dropIfExists('bellscale_answers');
    }
};
