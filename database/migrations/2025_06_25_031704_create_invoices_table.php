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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('job_orders')->onDelete('cascade');
            $table->string('order_number')->unique();
            $table->foreignId('customer_vehicle_id')->constrained('customer_vehicle')->onDelete('cascade');
            $table->timestamp('service_at');
            $table->enum('status', ['paid', 'unpaid'])->default('unpaid');
            $table->double('subtotal');
            $table->enum('diskon_unit', ['percentage', 'nominal'])->nullable();
            $table->double('diskon_value')->nullable();
            $table->double('total');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
