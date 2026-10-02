<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('crackers_bank_accounts')) {
            Schema::table('crackers_bank_accounts', function (Blueprint $table) {
                if (!Schema::hasColumn('crackers_bank_accounts', 'is_primary')) {
                    $table->boolean('is_primary')->default(false)->after('qr_code');
                }
            });

            // Set the first active bank account as primary if no primary exists
            $hasPrimary = DB::table('crackers_bank_accounts')->where('is_primary', true)->exists();
            if (!$hasPrimary) {
                $firstBank = DB::table('crackers_bank_accounts')->orderBy('id', 'asc')->first();
                if ($firstBank) {
                    DB::table('crackers_bank_accounts')->where('id', $firstBank->id)->update(['is_primary' => true]);
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('crackers_bank_accounts')) {
            Schema::table('crackers_bank_accounts', function (Blueprint $table) {
                if (Schema::hasColumn('crackers_bank_accounts', 'is_primary')) {
                    $table->dropColumn('is_primary');
                }
            });
        }
    }
};
