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
        Schema::create('movement_items', function (Blueprint $table) {
            $table->id();
            $table->enum('move', ['in', 'out'])->default('in');
            $table->enum('reference', ['sales_items', 'order_items', 'purchase_items']);
            $table->unsignedBigInteger('product_id');
            $table->bigInteger('reference_id');
            $table->string('item_name');
            $table->enum('name', ['purchases', 'supply', 'sales', 'retur']);
            $table->text('item_description')->nullable();
            $table->integer('quantity')->default(0);
            $table->double('buying_price')->default(0);
            $table->double('selling_price')->default(0);
            $table->double('total_price')->default(0);
            $table->double('discount')->default(0);
            $table->double('grand_total')->default(0);
            $table->unsignedBigInteger('created_by');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('created_by')->references('id')->on('users');
            $table->enum('status', ['draft', 'pending', 'cancel', 'done']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
