<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('crackers_products')) {
            Schema::create('crackers_products', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('category')->index(); // Sparklers, Rockets, Flower Pots, Chakkars, Gift Boxes, Sound Crackers
                $table->decimal('price', 10, 2);
                $table->decimal('discount_price', 10, 2)->nullable();
                $table->string('image')->nullable();
                $table->integer('stock')->default(100);
                $table->string('unit')->default('Box'); // Box, Pack, Piece
                $table->text('description')->nullable();
                $table->boolean('is_featured')->default(false);
                $table->boolean('status')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crackers_products');
    }
};
