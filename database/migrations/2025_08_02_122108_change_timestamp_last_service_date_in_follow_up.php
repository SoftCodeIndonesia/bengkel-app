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
        Schema::table('follow_ups', function (Blueprint $table) {
            $table->unsignedBigInteger('job_order_id');
            $table->timestamp('last_service_date')->change();
            $table->foreign('job_order_id')->references('id')->on('job_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('follow_ups', function (Blueprint $table) {
            //
        });
    }
};
