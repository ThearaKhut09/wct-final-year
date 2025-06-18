<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            // iPhones
            [
                'name' => 'iPhone 15 Pro Max',
                'description' => 'The latest iPhone with A17 Pro chip, titanium design, and advanced camera system.',
                'price' => 1199.99,
                'stock' => 50,
                'image' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400',
                'sku' => 'IPHONE15PROMAX',
                'category_ids' => [1, 2] // Electronics, Mobile Phones
            ],
            [
                'name' => 'iPhone 15',
                'description' => 'The standard iPhone 15 with USB-C and improved cameras.',
                'price' => 899.99,
                'stock' => 75,
                'image' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400',
                'sku' => 'IPHONE15',
                'category_ids' => [1, 2]
            ],
            [
                'name' => 'iPhone 14',
                'description' => 'Previous generation iPhone with excellent performance.',
                'price' => 699.99,
                'stock' => 30,
                'image' => 'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=400',
                'sku' => 'IPHONE14',
                'category_ids' => [1, 2]
            ],
            
            // Clothing
            [
                'name' => 'Premium Cotton T-Shirt',
                'description' => 'Soft and comfortable cotton t-shirt available in multiple colors.',
                'price' => 29.99,
                'stock' => 100,
                'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=400',
                'sku' => 'COTTON-TSHIRT',
                'category_ids' => [3]
            ],
            [
                'name' => 'Denim Jeans',
                'description' => 'Classic blue denim jeans with modern fit.',
                'price' => 79.99,
                'stock' => 60,
                'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=400',
                'sku' => 'DENIM-JEANS',
                'category_ids' => [3]
            ],
            [
                'name' => 'Leather Jacket',
                'description' => 'Genuine leather jacket with classic design.',
                'price' => 199.99,
                'stock' => 25,
                'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=400',
                'sku' => 'LEATHER-JACKET',
                'category_ids' => [3]
            ],
            
            // Shoes
            [
                'name' => 'Running Sneakers',
                'description' => 'Comfortable running shoes with advanced cushioning.',
                'price' => 129.99,
                'stock' => 80,
                'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400',
                'sku' => 'RUNNING-SNEAKERS',
                'category_ids' => [4]
            ],
            [
                'name' => 'Casual Loafers',
                'description' => 'Elegant leather loafers for casual wear.',
                'price' => 89.99,
                'stock' => 40,
                'image' => 'https://images.unsplash.com/photo-1449824913935-59a10b8d2000?w=400',
                'sku' => 'CASUAL-LOAFERS',
                'category_ids' => [4]
            ],
            [
                'name' => 'High-Top Sneakers',
                'description' => 'Stylish high-top sneakers for urban fashion.',
                'price' => 109.99,
                'stock' => 55,
                'image' => 'https://images.unsplash.com/photo-1551107696-a4b0c5a0d9a2?w=400',
                'sku' => 'HIGHTOP-SNEAKERS',
                'category_ids' => [4]
            ],
            
            // Accessories
            [
                'name' => 'Wireless Earbuds',
                'description' => 'Premium wireless earbuds with noise cancellation.',
                'price' => 149.99,
                'stock' => 70,
                'image' => 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?w=400',
                'sku' => 'WIRELESS-EARBUDS',
                'category_ids' => [1, 5]
            ],
            [
                'name' => 'Smartwatch',
                'description' => 'Feature-rich smartwatch with health monitoring.',
                'price' => 299.99,
                'stock' => 35,
                'image' => 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400',
                'sku' => 'SMARTWATCH',
                'category_ids' => [1, 5]
            ],
            [
                'name' => 'Designer Sunglasses',
                'description' => 'Stylish sunglasses with UV protection.',
                'price' => 159.99,
                'stock' => 45,
                'image' => 'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=400',
                'sku' => 'DESIGNER-SUNGLASSES',
                'category_ids' => [5]
            ]
        ];

        foreach ($products as $productData) {
            $categoryIds = $productData['category_ids'];
            unset($productData['category_ids']);
            
            $product = \App\Models\Product::updateOrCreate(
                ['sku' => $productData['sku']],
                $productData
            );
            
            // Sync categories (this will replace existing relationships)
            $product->categories()->sync($categoryIds);
        }
    }
}
