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
        Schema::create('user_treatment_metadata', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->date('treatment_date')->nullable();
            $table->date('stop_treatment_date')->nullable();
            $table->text('stop_treatment_reason')->nullable();
            $table->date('baseline_start_date')->nullable();
            $table->date('daily_baseline_start_date')->nullable();
            $table->integer('baseline_weekly_duration')->default(0);
            $table->integer('treatment_weekly_duration')->default(0);
            $table->integer('treatment_monthly_duration')->default(0);
            $table->boolean('situation_before_status')->nullable();
            
            $table->unsignedBigInteger('complication_label')->nullable();
            $table->unsignedBigInteger('question_label')->nullable();
            $table->unsignedBigInteger('highlight_patient_label')->nullable();
            $table->unsignedBigInteger('disease_label')->nullable();
            $table->unsignedBigInteger('treatment_label')->nullable();

            $table->timestampTz('created_at')->default(DB::raw('now()'));
            $table->timestampTz('updated_at')->default(DB::raw('now()'));
            $table->index('user_id', 'idx_user_treatment_metadata_user_id');
            $table->index('baseline_start_date', 'idx_utm_baseline_start_date');
            $table->index('daily_baseline_start_date', 'idx_utm_daily_baseline_start_date');
            $table->index('treatment_date', 'idx_utm_treatment_date');
            $table->index(['user_id', 'treatment_date', 'baseline_start_date'],'idx_utm_comprehensive');
            $table->unique('user_id', 'user_treatment_metadata_user_id_key');

            $table->foreign('user_id', 'fk_user_id_treatment')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('complication_label', 'fk_complication_label')->references('id')->on('labels');
            $table->foreign('highlight_patient_label', 'fk_highlight_patient_label')->references('id')->on('labels');
            $table->foreign('question_label', 'fk_question_label')->references('id')->on('labels');
            $table->foreign('treatment_label', 'fk_treatment_label')->references('id')->on('labels');
            $table->foreign('disease_label', 'fk_disease_label')->references('id')
            ->on('labels');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_treatment_metadata');
    }
};
