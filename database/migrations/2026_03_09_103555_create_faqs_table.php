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
     Schema::create('faq_categories', function (Blueprint $table) {
        $table->id();
        $table->string('category');
        $table->text('header')->nullable();
        $table->boolean('hide')->default(false);
        $table->integer('category_order');
        $table->timestamps();
    });

    Schema::create('faqs', function (Blueprint $table) {
        $table->id();
        $table->foreignId('category_id')->constrained('faq_categories')->cascadeOnDelete();
        $table->text('question');
        $table->text('answer');
        $table->string('imageurl')->nullable();
        $table->integer('question_order');
        $table->boolean('is_subquestion')->default(false);
        $table->timestamps();
    });

    Schema::create('faq_videousers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('faq_id')->constrained('faqs')->cascadeOnDelete();
        $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
        $table->boolean('watch_status')->default(false);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faq_videousers');
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('faq_categories');
    }
};