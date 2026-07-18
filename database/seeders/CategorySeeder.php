<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Fruits',
            'Vegetables',
            'Meat & Poultry',
            'Seafood',
            'Dairy & Eggs',
            'Grains & Pasta',
            'Nuts & Seeds',
            'Legumes',
            'Beverages',
            'Snacks & Sweets'
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'description' => 'A variety of fresh and packaged ' . strtolower($category) . ' items.'
            ]);
        }
    }
}
