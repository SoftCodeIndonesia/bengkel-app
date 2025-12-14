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
        Schema::table('supply_items', function (Blueprint $table) {
            $table->unsignedBigInteger('item_id')->nullable()->change();
            $table->unsignedBigInteger('sales_item_id')->nullable();
            $table->foreign('sales_item_id')->references('id')->on('sales_items')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supply_items', function (Blueprint $table) {
            //
        });
    }
};
