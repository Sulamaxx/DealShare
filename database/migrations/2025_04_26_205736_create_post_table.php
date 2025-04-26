<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();             // Save 800 LKR
            $table->text('description')->nullable();         // 20% off on total bill value ...
            $table->string('link')->nullable();               // powertoolspecialists.com.au
            $table->integer('upvotes')->default(0);           // +10
            $table->integer('downvotes')->default(0);         // -1
            $table->timestamp('posted_at')->nullable();       // 10min
            $table->integer('comment_count')->default(0);     // 5
            $table->string('category')->nullable();           // Hotel Deals | Top Deals
            $table->boolean('verified_member')->default(false); // Verified Member badge
            $table->string('image')->nullable();              // Image (promotion picture)
            $table->string('discount_text')->nullable();      // Example: 20% OFF
            $table->string('price_saving')->nullable();       // Example: Save 800 LKR
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
