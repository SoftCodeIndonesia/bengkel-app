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

        Schema::table('customer_vehicle', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('job_orders', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
            $table->string('product_name')->nullable();
            $table->string('product_description')->nullable();
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('sales', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('sales_items', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('supplies', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('supply_items', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('products', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('vehicles', function (Blueprint $table) {
            $table->softDeletes(); // Ini akan menambahkan kolom deleted_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('table_name', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menghapus kolom deleted_at
        });

        Schema::table('customer_vehicle', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
            $table->dropColumn('product_name');
            $table->dropColumn('product_description');
        });
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('purchases', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('purchase_items', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('sales', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('sales_items', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('supplies', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('supply_items', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('customers', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropSoftDeletes(); // Ini akan menambahkan kolom deleted_at
        });
    }
};
