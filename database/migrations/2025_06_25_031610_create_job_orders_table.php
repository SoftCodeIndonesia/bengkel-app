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
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->string('unique_id')->unique();
            $table->foreignId('customer_vehicle_id')->constrained('customer_vehicle')->onDelete('cascade');
            $table->double('km');
            $table->timestamp('service_at');
            $table->enum('status', ['estimation', 'draft', 'progress', 'completed', 'cancelled'])->default('draft');
            $table->double('subtotal')->default(0);
            $table->enum('diskon_unit', ['percentage', 'nominal'])->nullable();
            $table->double('diskon_value')->nullable();
            $table->double('total')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_orders');
    }
};
