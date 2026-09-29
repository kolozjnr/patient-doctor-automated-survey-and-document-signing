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
        Schema::create('survey_charts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('survey_id')->constrained('surveys')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('questions')->cascadeOnDelete();
            $table->string('chart_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('survey_charts');
    }
};
