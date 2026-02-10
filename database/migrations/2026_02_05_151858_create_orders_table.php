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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('caterer_id')->constrained('users')->onDelete('cascade');
            $table->string('order_number')->unique();
            
            // Delivery/Pickup Info
            $table->enum('fulfillment_type', ['delivery', 'pickup'])->default('delivery');
            $table->dateTime('fulfillment_date');
            $table->time('fulfillment_time')->nullable();
            $table->string('delivery_address')->nullable();
            $table->text('special_instructions')->nullable();
            
            // Customer Info
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            
            // Pricing
            $table->decimal('subtotal', 10, 2);
            $table->decimal('delivery_fee', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2);
            
            // Payment
            $table->enum('payment_method', ['gcash', 'paymaya', 'bank_transfer', 'cod'])->default('gcash');
            $table->string('receipt_path')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            
            // Order Status
            $table->enum('order_status', ['pending', 'confirmed', 'preparing', 'ready', 'completed', 'cancelled'])->default('pending');
            $table->text('cancellation_reason')->nullable();
            
            $table->timestamps();
        });

        // Pivot table for order items
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('display_menu_id')->constrained('display_menus')->onDelete('cascade');
            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Price at time of order
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};