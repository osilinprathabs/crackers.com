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
        if (Schema::hasTable('crackers_products')) {
            Schema::table('crackers_products', function (Blueprint $table) {
                if (!Schema::hasColumn('crackers_products', 'wholesale_max_qty')) {
                    $table->integer('wholesale_max_qty')->nullable()->after('wholesale_price');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('crackers_products')) {
            Schema::table('crackers_products', function (Blueprint $table) {
                if (Schema::hasColumn('crackers_products', 'wholesale_max_qty')) {
                    $table->dropColumn('wholesale_max_qty');
                }
            });
        }
    }
};
