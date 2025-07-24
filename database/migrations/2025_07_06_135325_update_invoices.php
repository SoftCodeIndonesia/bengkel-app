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
        Schema::table('invoices', function (Blueprint $table) {
            $table->renameColumn('service_at', 'date');
            $table->dropForeign(['order_id']);
            $table->dropForeign(['customer_vehicle_id']);
            $table->dropColumn(['order_number', 'customer_vehicle_id', 'order_id']);

            $table->string('unique_id')->unique();
            $table->enum('tipe', ['sales', 'services'])->default('services');
            $table->bigInteger('reference_id');
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->string('customer_name');
            $table->text('customer_address');
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
