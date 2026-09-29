<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('labels', function (Blueprint $table) {
            $table->id();
            $table->string('label', 255);
            $table->string('category', 255);
            $table->string('color', 10)->nullable();

            // Timestamps (Postgres style)
            $table->timestampTz('created_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));

            $table->timestampTz('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP'));
        $table->softDeletes();

            // Unique constraint (redundant but matches DB)
            $table->unique('id', 'labels_label_unique');
        });

        /*
         | Trigger to auto-update updated_at
         */
        DB::statement("
            CREATE TRIGGER set_updated_at_labels
            BEFORE UPDATE ON labels
            FOR EACH ROW
            EXECUTE FUNCTION update_updated_at_column();
        ");
    }

    public function down(): void
    {
        DB::statement("
            DROP TRIGGER IF EXISTS set_updated_at_labels
            ON labels
        ");

        Schema::dropIfExists('labels');
    }
};
