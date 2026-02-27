<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Mark a costing row as the caterer's default template
        Schema::table('package_costings', function (Blueprint $table) {
            $table->boolean('is_default_template')->default(false)->after('notes');
            $table->string('template_name')->nullable()->after('is_default_template')
                  ->comment('Friendly name when used as a template, e.g. "Standard Wedding"');
        });
    }

    public function down(): void
    {
        Schema::table('package_costings', function (Blueprint $table) {
            $table->dropColumn(['is_default_template', 'template_name']);
        });
    }
};