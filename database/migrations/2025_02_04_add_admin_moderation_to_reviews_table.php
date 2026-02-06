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
        Schema::table('reviews', function (Blueprint $table) {
            // Admin moderation fields
            $table->string('admin_status')->default('approved')->after('is_approved'); // approved, flagged, under_review, removed
            $table->text('flagged_reason')->nullable()->after('admin_status');
            $table->text('admin_notes')->nullable()->after('flagged_reason');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null')->after('admin_notes');
            $table->timestamp('admin_reviewed_at')->nullable()->after('reviewed_by');
            $table->boolean('caterer_warned')->default(false)->after('admin_reviewed_at');
            $table->timestamp('caterer_warned_at')->nullable()->after('caterer_warned');
            
            // Add indexes for better query performance
            $table->index('admin_status');
            $table->index('caterer_warned');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropForeign(['reviewed_by']);
            $table->dropIndex(['admin_status']);
            $table->dropIndex(['caterer_warned']);
            $table->dropColumn([
                'admin_status',
                'flagged_reason',
                'admin_notes',
                'reviewed_by',
                'admin_reviewed_at',
                'caterer_warned',
                'caterer_warned_at'
            ]);
        });
    }
};