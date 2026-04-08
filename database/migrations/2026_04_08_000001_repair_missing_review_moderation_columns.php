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
        if (!Schema::hasTable('reviews')) {
            return;
        }

        Schema::table('reviews', function (Blueprint $table) {
            if (!Schema::hasColumn('reviews', 'admin_status')) {
                $table->string('admin_status')->default('approved')->after('is_approved');
            }

            if (!Schema::hasColumn('reviews', 'flagged_reason')) {
                $table->text('flagged_reason')->nullable()->after('admin_status');
            }

            if (!Schema::hasColumn('reviews', 'admin_notes')) {
                $table->text('admin_notes')->nullable()->after('flagged_reason');
            }

            if (!Schema::hasColumn('reviews', 'reviewed_by')) {
                $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete()->after('admin_notes');
            }

            if (!Schema::hasColumn('reviews', 'admin_reviewed_at')) {
                $table->timestamp('admin_reviewed_at')->nullable()->after('reviewed_by');
            }

            if (!Schema::hasColumn('reviews', 'caterer_warned')) {
                $table->boolean('caterer_warned')->default(false)->after('admin_reviewed_at');
            }

            if (!Schema::hasColumn('reviews', 'caterer_warned_at')) {
                $table->timestamp('caterer_warned_at')->nullable()->after('caterer_warned');
            }
        });

        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'admin_status') && !Schema::hasIndex('reviews', 'reviews_admin_status_index')) {
                $table->index('admin_status');
            }
            if (Schema::hasColumn('reviews', 'caterer_warned') && !Schema::hasIndex('reviews', 'reviews_caterer_warned_index')) {
                $table->index('caterer_warned');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (!Schema::hasTable('reviews')) {
            return;
        }

        Schema::table('reviews', function (Blueprint $table) {
            if (Schema::hasColumn('reviews', 'reviewed_by')) {
                $table->dropConstrainedForeignId('reviewed_by');
            }

            if (Schema::hasIndex('reviews', 'reviews_admin_status_index')) {
                $table->dropIndex(['admin_status']);
            }
            if (Schema::hasIndex('reviews', 'reviews_caterer_warned_index')) {
                $table->dropIndex(['caterer_warned']);
            }

            $dropColumns = array_filter([
                Schema::hasColumn('reviews', 'admin_status') ? 'admin_status' : null,
                Schema::hasColumn('reviews', 'flagged_reason') ? 'flagged_reason' : null,
                Schema::hasColumn('reviews', 'admin_notes') ? 'admin_notes' : null,
                Schema::hasColumn('reviews', 'admin_reviewed_at') ? 'admin_reviewed_at' : null,
                Schema::hasColumn('reviews', 'caterer_warned') ? 'caterer_warned' : null,
                Schema::hasColumn('reviews', 'caterer_warned_at') ? 'caterer_warned_at' : null,
            ]);

            if (!empty($dropColumns)) {
                $table->dropColumn($dropColumns);
            }
        });
    }
};
