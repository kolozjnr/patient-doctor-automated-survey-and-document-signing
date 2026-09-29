<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            // Drop old FK
            $table->dropForeign('fk_questions_labels');

            // Re-add with SET NULL
            $table->foreign('question_label', 'fk_questions_labels')
                ->references('id')
                ->on('labels')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropForeign('fk_questions_labels');

            // Restore cascade if needed
            $table->foreign('question_label', 'fk_questions_labels')
                ->references('id')
                ->on('labels')
                ->onDelete('cascade');
        });
    }
};
