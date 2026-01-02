<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenuItemsTable extends Migration
{
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->nullable();
            $table->timestamps();

            $table->foreign('package_id')
                ->references('id')
                ->on('packages')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
}
