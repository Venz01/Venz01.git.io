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
        Schema::table('users', function (Blueprint $table) {
            // Common fields (if not already existing)
            $table->string('phone')->nullable()->after('email');
            $table->text('bio')->nullable()->after('contact_number');
            $table->string('profile_photo')->nullable()->after('bio');
            
            // Caterer-specific fields
            $table->text('services_offered')->nullable()->after('business_address');
            $table->json('cuisine_types')->nullable()->after('services_offered');
            $table->integer('years_of_experience')->nullable()->after('cuisine_types');
            $table->integer('team_size')->nullable()->after('years_of_experience');
            $table->json('service_areas')->nullable()->after('team_size');
            $table->string('instagram_link')->nullable()->after('facebook_link');
            $table->string('website_link')->nullable()->after('instagram_link');
            $table->time('business_hours_start')->nullable()->after('website_link');
            $table->time('business_hours_end')->nullable()->after('business_hours_start');
            $table->json('business_days')->nullable()->after('business_hours_end'); // ['monday', 'tuesday', etc.]
            $table->decimal('minimum_order', 10, 2)->nullable()->after('business_days');
            $table->decimal('maximum_capacity', 10, 2)->nullable()->after('minimum_order');
            $table->boolean('offers_delivery')->default(false)->after('maximum_capacity');
            $table->boolean('offers_setup')->default(false)->after('offers_delivery');
            $table->text('special_features')->nullable()->after('offers_setup');
            
            // Customer-specific fields
            $table->string('preferred_cuisine')->nullable()->after('special_features');
            $table->string('default_address')->nullable()->after('preferred_cuisine');
            $table->string('city')->nullable()->after('default_address');
            $table->string('postal_code')->nullable()->after('city');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'bio',
                'profile_photo',
                'services_offered',
                'cuisine_types',
                'years_of_experience',
                'team_size',
                'service_areas',
                'instagram_link',
                'website_link',
                'business_hours_start',
                'business_hours_end',
                'business_days',
                'minimum_order',
                'maximum_capacity',
                'offers_delivery',
                'offers_setup',
                'special_features',
                'preferred_cuisine',
                'default_address',
                'city',
                'postal_code',
            ]);
        });
    }
};
