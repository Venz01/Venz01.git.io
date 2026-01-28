<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            // Add user_id if not exists
            if (!Schema::hasColumn('menu_items', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
            
            // Add image_path if not exists
            if (!Schema::hasColumn('menu_items', 'image_path')) {
                $table->string('image_path')->nullable()->after('price');
            }
            
            // Add status if not exists
            if (!Schema::hasColumn('menu_items', 'status')) {
                $table->string('status')->default('available')->after('image_path');
            }
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            if (Schema::hasColumn('menu_items', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            
            if (Schema::hasColumn('menu_items', 'image_path')) {
                $table->dropColumn('image_path');
            }
            
            if (Schema::hasColumn('menu_items', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};