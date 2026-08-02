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
        Schema::table('order_items', function (Blueprint $table) {
            $table->decimal('fee_value', 5, 2)
                ->default(0)
                ->after('diskon_value');

            $table->decimal('fee_amount', 12, 2)
                ->default(0)
                ->after('fee_value');
        });

        Schema::table('job_orders', function (Blueprint $table) {
            $table->decimal('ppn_value', 5, 2)
                ->default(0)
                ->after('diskon_value');

            $table->decimal('ppn_amount', 12, 2)
                ->default(0)
                ->after('ppn_value');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn([
                'fee_value',
                'fee_amount',
            ]);
        });

        Schema::table('job_orders', function (Blueprint $table) {
            $table->dropColumn([
                'ppn_value',
                'ppn_amount',
            ]);
        });
    }
};
