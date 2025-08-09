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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->id();
            $table->string('opname_number')->unique();
            $table->date('opname_date');
            $table->unsignedBigInteger('created_by');
            $table->text('notes')->nullable();
            $table->enum('status', ['draft', 'in_progress', 'completed'])->default('draft');
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users');
        });

        Schema::create('stock_opname_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('opname_id');
            $table->unsignedBigInteger('product_id');
            $table->integer('system_stock');
            $table->integer('physical_stock');
            $table->integer('difference');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_difference', 12, 2);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('opname_id')->references('id')->on('stock_opnames')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
        Schema::dropIfExists('stock_opname_items');
    }
};
