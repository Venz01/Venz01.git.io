<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->enum('delivery_fee_status', ['not_required', 'pending', 'assigned', 'accepted', 'rejected'])
                ->default('not_required')
                ->after('delivery_fee');

            $table->timestamp('delivery_fee_assigned_at')->nullable()->after('delivery_fee_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivery_fee_status', 'delivery_fee_assigned_at']);
        });
    }
};
