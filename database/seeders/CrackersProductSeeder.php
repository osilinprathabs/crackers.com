<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CrackersProduct;

class CrackersProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // Sparklers
            [
                'name' => '10cm Electric Sparklers',
                'category' => 'Sparklers',
                'price' => 120.00,
                'discount_price' => 85.00,
                'stock' => 500,
                'unit' => 'Box (10 Pcs)',
                'description' => 'Bright golden sparklers perfect for kids and family celebrations.',
                'is_featured' => true,
            ],
            [
                'name' => '30cm Color Sparklers (Green & Red)',
                'category' => 'Sparklers',
                'price' => 250.00,
                'discount_price' => 180.00,
                'stock' => 300,
                'unit' => 'Box (5 Pcs)',
                'description' => 'Vibrant long-lasting multi-color sparklers.',
                'is_featured' => true,
            ],
            // Flower Pots
            [
                'name' => 'Flower Pots Special (Big)',
                'category' => 'Flower Pots',
                'price' => 350.00,
                'discount_price' => 260.00,
                'stock' => 200,
                'unit' => 'Box (10 Pcs)',
                'description' => 'High fountain golden sparks with sparkling star effects.',
                'is_featured' => true,
            ],
            [
                'name' => 'Color Flower Pots Tri-Color',
                'category' => 'Flower Pots',
                'price' => 450.00,
                'discount_price' => 320.00,
                'stock' => 150,
                'unit' => 'Box (10 Pcs)',
                'description' => 'Emits three distinct sparkling color fountains.',
                'is_featured' => false,
            ],
            // Chakkars
            [
                'name' => 'Ground Chakkars Deluxe',
                'category' => 'Chakkars',
                'price' => 280.00,
                'discount_price' => 195.00,
                'stock' => 400,
                'unit' => 'Box (10 Pcs)',
                'description' => 'Smooth spinning ground wheels emitting brilliant silver rings.',
                'is_featured' => true,
            ],
            [
                'name' => 'Spinning Wheel Spinner Supreme',
                'category' => 'Chakkars',
                'price' => 390.00,
                'discount_price' => 280.00,
                'stock' => 250,
                'unit' => 'Box (10 Pcs)',
                'description' => 'Extra long spinning time with whistling sound.',
                'is_featured' => false,
            ],
            // Rockets
            [
                'name' => 'Multi-Shot Color Rockets',
                'category' => 'Rockets',
                'price' => 500.00,
                'discount_price' => 375.00,
                'stock' => 200,
                'unit' => 'Box (10 Pcs)',
                'description' => 'Shoots high into the sky bursting into multi-colored palm bursts.',
                'is_featured' => true,
            ],
            [
                'name' => 'Whistling Aerial Rockets',
                'category' => 'Rockets',
                'price' => 650.00,
                'discount_price' => 480.00,
                'stock' => 150,
                'unit' => 'Box (10 Pcs)',
                'description' => 'High altitude rockets with piercing whistle sound.',
                'is_featured' => false,
            ],
            // Sound Crackers
            [
                'name' => '1000 Wala Garland Crackers',
                'category' => 'Sound Crackers',
                'price' => 950.00,
                'discount_price' => 720.00,
                'stock' => 100,
                'unit' => 'Box (1 Pc)',
                'description' => 'Traditional loud festive garland cracker roll.',
                'is_featured' => true,
            ],
            [
                'name' => '5000 Wala Grand Celebration Roll',
                'category' => 'Sound Crackers',
                'price' => 2800.00,
                'discount_price' => 2100.00,
                'stock' => 50,
                'unit' => 'Box (1 Pc)',
                'description' => 'Mega celebration roll for grand Diwali and festival occasions.',
                'is_featured' => true,
            ],
            // Gift Boxes & Combos
            [
                'name' => 'Diwali Grand Family Cracker Gift Box (35 Items)',
                'category' => 'Gift Boxes',
                'price' => 3500.00,
                'discount_price' => 2499.00,
                'stock' => 80,
                'unit' => 'Box Set',
                'description' => 'Complete family assortment including Sparklers, Pots, Chakkars, Rockets & Fancy items.',
                'is_featured' => true,
            ],
            [
                'name' => 'Kids Safe Celebration Box (15 Items)',
                'category' => 'Gift Boxes',
                'price' => 1800.00,
                'discount_price' => 1299.00,
                'stock' => 120,
                'unit' => 'Box Set',
                'description' => 'Low-smoke, noiseless and colorful items curated specially for young kids.',
                'is_featured' => true,
            ],
        ];

        foreach ($products as $p) {
            CrackersProduct::updateOrCreate(['name' => $p['name']], $p);
        }
    }
}
