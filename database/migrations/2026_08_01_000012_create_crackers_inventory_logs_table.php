<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('crackers_inventory_logs')) {
            Schema::create('crackers_inventory_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('product_id')->constrained('crackers_products')->cascadeOnDelete();
                $table->string('type')->default('manual_adjustment'); // addition, subtraction, order_deduction, manual_adjustment
                $table->integer('quantity');
                $table->integer('old_stock');
                $table->integer('new_stock');
                $table->string('notes')->nullable();
                $table->string('created_by')->default('Admin');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crackers_inventory_logs');
    }
};
