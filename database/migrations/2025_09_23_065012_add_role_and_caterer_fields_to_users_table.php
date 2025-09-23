<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('customer')->after('password');
            
            // Caterer-specific fields, nullable for customers
            $table->string('business_name')->nullable()->after('role');
            $table->string('owner_full_name')->nullable()->after('business_name');
            $table->string('business_address')->nullable()->after('owner_full_name');
            $table->string('business_permit_number')->nullable()->after('business_address');
            $table->string('business_permit_file_path')->nullable()->after('business_permit_number');
            
            // You can add more caterer-specific fields if needed
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'role',
                'business_name',
                'owner_full_name',
                'business_address',
                'business_permit_number',
                'business_permit_file_path',
            ]);
        });
    }
};
