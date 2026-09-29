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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question');
            $table->integer('page_number')->default(0)->nullable();
            $table->integer('page_number_admin')->nullable();
            $table->text('explanation_text')->nullable();

            $table->string('type', 255);
            $table->json('options')->nullable();
            $table->string('icon', 255)->nullable();
            $table->string('module_name', 255)->nullable();

            $table->boolean('follow_up_on_yes')->default(true);
            $table->boolean('is_subquestion')->default(false);
            $table->integer('_order')->nullable();
            $table->text('placeholder')->nullable();
            $table->integer('rate_max')->default(10)->nullable();
            $table->text('table_fields')->nullable();
            $table->boolean('only_female')->default(true);
            
            $table->timestampTz('_created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('_updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->softDeletes();

            $table->unsignedBigInteger('parent_question_id')->nullable();
            $table->unsignedBigInteger('question_label')->nullable();
            $table->unsignedBigInteger('follow_up_id')->nullable();

            $table->index('module_name', 'idx_questions_module_name');
            $table->index('is_subquestion', 'idx_questions_is_subquestion');
            $table->index(['module_name', 'question_label'], 'idx_questions_module_label');
            $table->index(['module_name', 'type'], 'idx_questions_module_type');
            $table->index('_order', 'idx_questions_order');
            $table->index('parent_question_id', 'idx_questions_parent_question_id');
            $table->index('question_label', 'idx_questions_question_label');
            $table->index(
                ['module_name', 'is_subquestion', '_order'],
                'idx_questions_reorder_perf'
            );

        });

        Schema::table('questions', function (Blueprint $table) {

            $table->foreign('parent_question_id', 'fk_questions_parent_question_id')
                ->references('id')
                ->on('questions')
                ->onDelete('cascade');

            $table->foreign('question_label', 'fk_questions_labels')
                ->references('id')
                ->on('labels')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
