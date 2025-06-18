<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Electronics',
                'description' => 'Latest electronic devices and gadgets',
                'image' => 'https://images.unsplash.com/photo-1498049794561-7780e7231661?w=400'
            ],
            [
                'name' => 'Mobile Phones',
                'description' => 'Smartphones and mobile accessories',
                'image' => 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?w=400'
            ],
            [
                'name' => 'Clothing',
                'description' => 'Fashion and apparel for all',
                'image' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=400'
            ],
            [
                'name' => 'Shoes',
                'description' => 'Footwear for every occasion',
                'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400'
            ],
            [
                'name' => 'Accessories',
                'description' => 'Fashion accessories and jewelry',
                'image' => 'https://images.unsplash.com/photo-1492707892479-7bc8d5a4ee93?w=400'
            ]
        ];

        foreach ($categories as $category) {
            \App\Models\Category::updateOrCreate(
                ['name' => $category['name']],
                $category
            );
        }
    }
}
