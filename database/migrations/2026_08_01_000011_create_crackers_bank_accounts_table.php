<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('crackers_bank_accounts')) {
            Schema::create('crackers_bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('bank_name');
                $table->string('account_holder');
                $table->string('account_number');
                $table->string('ifsc_code');
                $table->string('branch_name')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });

            // Seed initial bank account
            DB::table('crackers_bank_accounts')->insert([
                'bank_name' => 'State Bank of India',
                'account_holder' => 'Crackers Traders Pvt Ltd',
                'account_number' => '123456789012',
                'ifsc_code' => 'SBIN0001234',
                'branch_name' => 'Sivakasi Main Branch',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crackers_bank_accounts');
    }
};
