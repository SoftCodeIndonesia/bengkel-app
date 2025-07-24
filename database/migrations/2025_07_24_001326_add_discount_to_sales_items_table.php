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
        Schema::table('sales_items', function (Blueprint $table) {
            $table->decimal('discount_percentage', 12, 2)->default(0);
            $table->decimal('discount_nominal', 12, 2)->default(0);
            $table->decimal('price_after_discount', 12, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales_items', function (Blueprint $table) {
            $table->dropColumn(['discount_percentage', 'discount_nominal', 'price_after_discount']);
        });
    }
};
