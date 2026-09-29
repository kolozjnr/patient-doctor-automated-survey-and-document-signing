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
        Schema::table('document_assignments', function (Blueprint $table) {
            $table->string('token')->nullable()->unique()->after('user_id');
            $table->string('docuseal_slug')->nullable()->after('docuseal_submission_id');
            $table->string('signing_url')->nullable()->after('docuseal_submission_id');
            $table->string('download_url')->nullable()->after('docuseal_submission_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('document_assignments', function (Blueprint $table) {
            $table->dropColumn(['download_url']);
            $table->dropColumn(['signing_url']);
            $table->dropColumn(['docuseal_slug']);
            $table->dropColumn(['token']);
        });
    }
};
