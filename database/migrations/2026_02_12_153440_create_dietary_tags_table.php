
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dietary_tags', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g., "No Pork", "Vegan"
            $table->string('slug')->unique(); // e.g., "no_pork", "vegan"
            $table->string('icon')->nullable(); // emoji icon
            $table->string('color')->default('gray'); // color for UI
            $table->boolean('is_system')->default(false); // system tags can't be deleted
            $table->timestamps();
        });

        // Insert default system tags
        DB::table('dietary_tags')->insert([
            ['name' => 'No Pork', 'slug' => 'no_pork', 'icon' => '🐷', 'color' => 'red', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vegetarian', 'slug' => 'vegetarian', 'icon' => '🥦', 'color' => 'green', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vegan', 'slug' => 'vegan', 'icon' => '🌱', 'color' => 'green', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Halal', 'slug' => 'halal', 'icon' => '☪️', 'color' => 'emerald', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Gluten-Free', 'slug' => 'gluten_free', 'icon' => '🌾', 'color' => 'yellow', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Dairy-Free', 'slug' => 'dairy_free', 'icon' => '🥛', 'color' => 'blue', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Seafood-Free', 'slug' => 'seafood_free', 'icon' => '🦐', 'color' => 'cyan', 'is_system' => true, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nut-Free', 'slug' => 'nut_free', 'icon' => '🥜', 'color' => 'orange', 'is_system' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Low Sodium', 'slug' => 'low_sodium', 'icon' => '🧂', 'color' => 'purple', 'is_system' => false, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Diabetic-Friendly', 'slug' => 'diabetic', 'icon' => '💉', 'color' => 'pink', 'is_system' => false, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('dietary_tags');
    }
};