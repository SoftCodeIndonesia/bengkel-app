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
        Schema::create('expense_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type')->default('operational'); // operational, salary, utility
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('invoice_number')->unique()->nullable();
            $table->unsignedBigInteger('expense_category_id');
            $table->decimal('amount', 12, 2);
            $table->text('description')->nullable();
            $table->string('payment_method')->default('cash');
            $table->unsignedBigInteger('recorded_by');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('expense_category_id')->references('id')->on('expense_categories');
            $table->foreign('recorded_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
