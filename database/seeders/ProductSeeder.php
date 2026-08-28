<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'product_name' => 'K-Vision Receiver',
            'category' => 'Receiver',
            'stock' => 10,
            'price' => 25.00,
        ]);

        Product::create([
            'product_name' => 'Parabola 6 Feet',
            'category' => 'Parabola',
            'stock' => 5,
            'price' => 80.00,
        ]);

        Product::create([
            'product_name' => 'LNB Ku Band',
            'category' => 'LNB',
            'stock' => 15,
            'price' => 12.00,
        ]);
    }
}