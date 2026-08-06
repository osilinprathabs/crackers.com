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
                if (!Schema::hasColumn('crackers_orders', 'payment_proof')) {
                    $table->string('payment_proof')->nullable()->after('payment_method');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('crackers_orders')) {
            Schema::table('crackers_orders', function (Blueprint $table) {
                if (Schema::hasColumn('crackers_orders', 'payment_proof')) {
                    $table->dropColumn('payment_proof');
                }
            });
        }
    }
};
