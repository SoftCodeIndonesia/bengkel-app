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
        Schema::create('follow_ups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_vehicle_id');
            $table->date('last_service_date');
            $table->boolean('contacted')->default(false);
            $table->date('contact_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->foreign('customer_vehicle_id')->references('id')->on('customer_vehicle');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('follow_ups');
    }
};
