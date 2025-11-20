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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('caterer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('booking_number')->unique();
            $table->string('event_type');
            $table->date('event_date');
            $table->string('time_slot');
            $table->integer('guests');
            $table->string('venue_name');
            $table->text('venue_address');
            $table->text('special_instructions')->nullable();
            $table->decimal('price_per_head', 10, 2);
            $table->decimal('total_price', 10, 2);
            $table->decimal('deposit_amount', 10, 2);
            $table->decimal('service_fee', 10, 2)->default(500);
            $table->decimal('deposit_paid', 10, 2);
            $table->decimal('balance', 10, 2);
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->enum('payment_method', ['gcash', 'paymaya', 'bank_transfer']);
            $table->string('receipt_path');
            $table->enum('payment_status', ['deposit_paid', 'fully_paid', 'refunded'])->default('deposit_paid');
            $table->enum('booking_status', ['pending', 'confirmed', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();

            $table->index(['customer_id', 'event_date']);
            $table->index(['caterer_id', 'booking_status']);
        });

        Schema::create('booking_menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->foreignId('menu_item_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['booking_id', 'menu_item_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_menu_items');
        Schema::dropIfExists('bookings');
    }
};
