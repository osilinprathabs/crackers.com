<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('crackers_orders')) {
            Schema::create('crackers_orders', function (Blueprint $table) {
                $table->id();
                $table->string('order_number')->unique();
                $table->foreignId('customer_id')->nullable()->constrained('customers')->nullOnDelete();
                $table->string('customer_name');
                $table->string('customer_phone');
                $table->string('customer_email')->nullable();
                $table->text('delivery_address');
                $table->string('city')->nullable();
                $table->string('pincode')->nullable();
                $table->decimal('subtotal', 10, 2);
                $table->decimal('discount', 10, 2)->default(0);
                $table->decimal('grand_total', 10, 2);
                $table->string('payment_method')->default('COD'); // COD, UPI, NetBanking
                $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
                $table->enum('status', ['pending', 'processing', 'dispatched', 'delivered', 'cancelled'])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crackers_orders');
    }
};
