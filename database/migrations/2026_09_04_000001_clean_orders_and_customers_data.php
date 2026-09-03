<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to clear existing test orders and customers for live deployment.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();

        if (Schema::hasTable('crackers_order_items')) {
            DB::table('crackers_order_items')->truncate();
        }

        if (Schema::hasTable('crackers_orders')) {
            DB::table('crackers_orders')->truncate();
        }

        if (Schema::hasTable('crackers_inventory_logs')) {
            DB::table('crackers_inventory_logs')->truncate();
        }

        if (Schema::hasTable('customers')) {
            DB::table('customers')->truncate();
        }

        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Truncate data cleanup cannot be reversed
    }
};
