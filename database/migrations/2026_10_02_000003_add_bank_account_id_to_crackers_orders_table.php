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
        if (!Schema::hasColumn('crackers_orders', 'bank_account_id')) {
            Schema::table('crackers_orders', function (Blueprint $table) {
                $table->unsignedBigInteger('bank_account_id')->nullable()->after('payment_method');
                $table->foreign('bank_account_id')->references('id')->on('crackers_bank_accounts')->onDelete('set null');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('crackers_orders', 'bank_account_id')) {
            Schema::table('crackers_orders', function (Blueprint $table) {
                $table->dropForeign(['bank_account_id']);
                $table->dropColumn('bank_account_id');
            });
        }
    }
};
