<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Add user_id to categories if not exists
        if (!Schema::hasColumn('categories', 'user_id')) {
            Schema::table('categories', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            });
        }

        // Add user_id to menu_items if not exists
        if (!Schema::hasColumn('menu_items', 'user_id')) {
            Schema::table('menu_items', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            });
        }

        // Add user_id to packages if not exists (you might need this too)
        if (!Schema::hasColumn('packages', 'user_id')) {
            Schema::table('packages', function (Blueprint $table) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            if (Schema::hasColumn('categories', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('menu_items', function (Blueprint $table) {
            if (Schema::hasColumn('menu_items', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });

        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
        });
    }
};