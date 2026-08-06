<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('crackers_order_items')) {
            Schema::create('crackers_order_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained('crackers_orders')->cascadeOnDelete();
                $table->foreignId('product_id')->nullable()->constrained('crackers_products')->nullOnDelete();
                $table->string('product_name');
                $table->decimal('unit_price', 10, 2);
                $table->integer('quantity');
                $table->decimal('total_price', 10, 2);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crackers_order_items');
    }
};
