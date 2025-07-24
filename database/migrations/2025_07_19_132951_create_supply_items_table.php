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
        Schema::create('supply_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supply_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('movement_item_id');
            $table->integer('quantity_requested');
            $table->integer('quantity_fulfilled')->default(0);
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_price', 12, 2);
            $table->enum('status', ['pending', 'partial', 'fulfilled'])->default('pending');
            $table->timestamps();

            $table->foreign('supply_id')->references('id')->on('supplies')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('movement_item_id')->references('id')->on('movement_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_items');
    }
};
