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
            $table->dropForeign(['movement_item_id']);
            $table->renameColumn('movement_item_id', 'item_id');
            $table->foreign('item_id')->references('id')->on('order_items');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supply_items', function (Blueprint $table) {
            $table->dropForeign(['item_id']);
            $table->renameColumn('item_id', 'movement_item_id');
            $table->foreign('movement_item_id')->references('id')->on('movement_items');
        });
    }
};
