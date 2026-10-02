<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('crackers_bank_accounts')) {
            Schema::table('crackers_bank_accounts', function (Blueprint $table) {
                if (!Schema::hasColumn('crackers_bank_accounts', 'upi_id')) {
                    $table->string('upi_id')->nullable()->after('branch_name');
                }
                if (!Schema::hasColumn('crackers_bank_accounts', 'qr_code')) {
                    $table->string('qr_code')->nullable()->after('upi_id');
                }
            });
        }

        if (Schema::hasTable('bank_accounts')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                if (!Schema::hasColumn('bank_accounts', 'upi_id')) {
                    $table->string('upi_id')->nullable()->after('branch_name');
                }
                if (!Schema::hasColumn('bank_accounts', 'qr_code')) {
                    $table->string('qr_code')->nullable()->after('upi_id');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('crackers_bank_accounts')) {
            Schema::table('crackers_bank_accounts', function (Blueprint $table) {
                if (Schema::hasColumn('crackers_bank_accounts', 'upi_id')) {
                    $table->dropColumn(['upi_id', 'qr_code']);
                }
            });
        }

        if (Schema::hasTable('bank_accounts')) {
            Schema::table('bank_accounts', function (Blueprint $table) {
                if (Schema::hasColumn('bank_accounts', 'upi_id')) {
                    $table->dropColumn(['upi_id', 'qr_code']);
                }
            });
        }
    }
};
