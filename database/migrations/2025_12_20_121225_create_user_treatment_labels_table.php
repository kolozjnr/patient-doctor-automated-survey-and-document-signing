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
        Schema::create('user_treatment_labels', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestampTz('updated_at')->default(DB::raw('now()'));
            $table->softDeletes();

            $table->index('label_id', 'idx_user_treatment_labels_label');
            $table->index('user_id', 'idx_user_treatment_labels_user');

            $table->unique(['user_id', 'label_id'], 'user_treatment_labels_user_id_label_id_key');

            $table->foreignId('label_id')
              ->constrained('labels')
              ->onDelete('cascade');
        
        $table->foreignId('user_id')
              ->constrained('users')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_treatment_labels');
    }
};
