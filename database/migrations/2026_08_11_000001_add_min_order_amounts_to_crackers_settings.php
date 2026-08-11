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
        if (Schema::hasTable('crackers_settings')) {
            Schema::table('crackers_settings', function (Blueprint $table) {
                if (!Schema::hasColumn('crackers_settings', 'min_retail_order_amount')) {
                    $table->decimal('min_retail_order_amount', 10, 2)->default(0.00)->after('gst_percentage');
                }
                if (!Schema::hasColumn('crackers_settings', 'min_wholesale_order_amount')) {
                    $table->decimal('min_wholesale_order_amount', 10, 2)->default(0.00)->after('min_retail_order_amount');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('crackers_settings')) {
            Schema::table('crackers_settings', function (Blueprint $table) {
                if (Schema::hasColumn('crackers_settings', 'min_retail_order_amount')) {
                    $table->dropColumn('min_retail_order_amount');
                }
                if (Schema::hasColumn('crackers_settings', 'min_wholesale_order_amount')) {
                    $table->dropColumn('min_wholesale_order_amount');
                }
            });
        }
    }
};
