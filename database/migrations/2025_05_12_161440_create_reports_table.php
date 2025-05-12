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
        Schema::create('reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            // Polymorphic relationship to the reportable model (e.g., Post, Comment)
            $table->morphs('reportable'); // Adds reportable_type (string) and reportable_id (unsignedBigInteger)

            // The reason for the report
            $table->text('reason')->nullable();

            // Status of the report (e.g., pending, reviewed, dismissed, resolved)
            $table->string('status')->default('pending');

            $table->timestamps();


            //$table->index(['reportable_type', 'reportable_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};
