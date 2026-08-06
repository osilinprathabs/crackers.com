<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('crackers_settings')) {
            Schema::create('crackers_settings', function (Blueprint $table) {
                $table->id();
                $table->decimal('gst_percentage', 5, 2)->default(18.00);
                $table->boolean('enable_cod')->default(true);
                $table->boolean('enable_upi')->default(true);
                $table->string('upi_id')->nullable()->default('crackers@upi');
                $table->string('upi_qr_code')->nullable();
                $table->boolean('enable_bank_transfer')->default(true);
                $table->string('bank_name')->nullable()->default('State Bank of India');
                $table->string('account_number')->nullable()->default('123456789012');
                $table->string('ifsc_code')->nullable()->default('SBIN0001234');
                $table->string('account_holder')->nullable()->default('Crackers Traders Pvt Ltd');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crackers_settings');
    }
};
