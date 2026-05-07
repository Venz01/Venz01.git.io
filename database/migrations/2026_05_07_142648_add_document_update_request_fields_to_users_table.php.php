<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'document_update_requested')) {
                $table->boolean('document_update_requested')->default(false)->after('rejection_reason');
            }

            if (! Schema::hasColumn('users', 'document_update_reason')) {
                $table->text('document_update_reason')->nullable()->after('document_update_requested');
            }

            if (! Schema::hasColumn('users', 'document_update_requested_at')) {
                $table->timestamp('document_update_requested_at')->nullable()->after('document_update_reason');
            }

            if (! Schema::hasColumn('users', 'document_update_resolved_at')) {
                $table->timestamp('document_update_resolved_at')->nullable()->after('document_update_requested_at');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            foreach ([
                'document_update_resolved_at',
                'document_update_requested_at',
                'document_update_reason',
                'document_update_requested',
            ] as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
