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
        Schema::table('users', function (Blueprint $table) {
            // Add the badge_id column
            $table->foreignId('badge_id')
                ->nullable() // Make it nullable if a user might not have a badge initially
                ->constrained('badges')
                ->onDelete('set null'); // If a badge is deleted, set user's badge_id to null
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Drop the foreign key constraint first
            $table->dropConstrainedForeignId('badge_id');
            // Then drop the column
            $table->dropColumn('badge_id');
        });
    }
};
