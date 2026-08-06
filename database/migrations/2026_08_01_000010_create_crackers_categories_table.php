<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('crackers_categories')) {
            Schema::create('crackers_categories', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('slug')->unique();
                $table->string('icon')->nullable()->default('ri-sparkling-fill');
                $table->boolean('status')->default(true);
                $table->timestamps();
            });

            // Seed initial categories
            $categories = [
                ['name' => 'Sparklers', 'icon' => 'ri-magic-line'],
                ['name' => 'Flower Pots', 'icon' => 'ri-fire-line'],
                ['name' => 'Ground Chakkars', 'icon' => 'ri-restart-line'],
                ['name' => 'Sky Rockets', 'icon' => 'ri-rocket-line'],
                ['name' => 'Sound Crackers', 'icon' => 'ri-volume-up-line'],
                ['name' => 'Gift Boxes', 'icon' => 'ri-gift-line'],
            ];

            foreach ($categories as $cat) {
                DB::table('crackers_categories')->insert([
                    'name' => $cat['name'],
                    'slug' => Str::slug($cat['name']),
                    'icon' => $cat['icon'],
                    'status' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('crackers_categories');
    }
};
