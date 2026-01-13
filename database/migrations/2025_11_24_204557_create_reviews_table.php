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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('caterer_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating'); // 1-5 stars
            $table->text('comment');
            $table->text('caterer_response')->nullable();
            $table->timestamp('responded_at')->nullable();
            $table->boolean('is_approved')->default(true); // For moderation
            $table->timestamps();
            
            // Ensure one review per booking
            $table->unique('booking_id');
            
            // Indexes for faster queries
            $table->index('caterer_id');
            $table->index('customer_id');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
