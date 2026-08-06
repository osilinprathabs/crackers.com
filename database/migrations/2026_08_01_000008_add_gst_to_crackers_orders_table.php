<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('crackers_orders')) {
            Schema::table('crackers_orders', function (Blueprint $table) {
                if (!Schema::hasColumn('crackers_orders', 'gst_rate')) {
                    $table->decimal('gst_rate', 5, 2)->default(18.00)->after('subtotal');
                }
                if (!Schema::hasColumn('crackers_orders', 'gst_amount')) {
                    $table->decimal('gst_amount', 10, 2)->default(0.00)->after('gst_rate');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('crackers_orders')) {
            Schema::table('crackers_orders', function (Blueprint $table) {
                $table->dropColumn(['gst_rate', 'gst_amount']);
            });
        }
    }
};
