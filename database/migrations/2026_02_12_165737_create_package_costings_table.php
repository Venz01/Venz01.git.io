<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_costings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // caterer

            // ── Cost Components (all nullable — caterer opts in to each) ──────
            $table->decimal('ingredient_cost', 10, 2)->nullable()->comment('Raw food cost per head');
            $table->decimal('labor_cost', 10, 2)->nullable()->comment('Staffing / service crew cost per head');
            $table->decimal('equipment_cost', 10, 2)->nullable()->comment('Tables, linens, chafing dishes, etc.');
            $table->decimal('consumables_cost', 10, 2)->nullable()->comment('Utensils, softdrinks, packaging');
            $table->decimal('overhead_cost', 10, 2)->nullable()->comment('Utilities, kitchen overhead per head');
            $table->decimal('transport_cost', 10, 2)->nullable()->comment('Delivery / logistics per head');

            // ── Markup Strategy ──────────────────────────────────────────────
            $table->decimal('profit_margin_percent', 5, 2)->default(25.00)->comment('Target profit % on top of total cost');
            $table->decimal('suggested_price', 10, 2)->nullable()->comment('System-calculated suggested price/head');
            $table->decimal('final_price', 10, 2)->nullable()->comment('Caterer-approved final price/head (syncs to packages.price)');

            // ── Notes ────────────────────────────────────────────────────────
            $table->text('notes')->nullable()->comment('Internal costing notes, not visible to customers');

            $table->timestamps();

            $table->unique('package_id'); // one costing record per package
        });

        // Add costing_template_id to packages (references package_costings for cloning)
        Schema::table('packages', function (Blueprint $table) {
            if (!Schema::hasColumn('packages', 'costing_template_id')) {
                $table->unsignedBigInteger('costing_template_id')->nullable()->after('user_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            if (Schema::hasColumn('packages', 'costing_template_id')) {
                $table->dropColumn('costing_template_id');
            }
        });

        Schema::dropIfExists('package_costings');
    }
};