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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->text('description')->nullable();
            $table->string('document_type')->nullable();
            $table->string('file_type')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_url')->nullable();
            $table->string('docuseal_template_id')->nullable();
            $table->foreignId('created_by')
              ->constrained('client_admins')
              ->cascadeOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->index('docuseal_template_id');
        });

        Schema::create('document_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('docuseal_submission_id')->nullable();
            $table->string('signing_url')->nullable();
            $table->string('status')->nullable();
            $table->timestamp('signed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['document_id', 'user_id']);
            $table->index(['user_id', 'status']);
            $table->index('docuseal_submission_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_assignments');
        Schema::dropIfExists('documents');
    }
};
