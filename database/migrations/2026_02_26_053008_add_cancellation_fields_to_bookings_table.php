<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('cancelled_by')->nullable()->after('booking_status');        // 'customer' | 'caterer'
            $table->text('cancellation_reason')->nullable()->after('cancelled_by');
            $table->string('refund_status')->default('none')->after('cancellation_reason'); // 'none' | 'pending' | 'issued' | 'waived'
            $table->text('refund_details')->nullable()->after('refund_status');          // GCash / bank details from customer
            $table->timestamp('cancelled_at')->nullable()->after('refund_details');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn([
                'cancelled_by',
                'cancellation_reason',
                'refund_status',
                'refund_details',
                'cancelled_at',
            ]);
        });
    }
};