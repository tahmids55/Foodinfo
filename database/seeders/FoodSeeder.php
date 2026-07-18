<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Food;
use App\Models\Category;
use App\Models\Nutrient;
use App\Models\HealthStatus;
use Faker\Factory as Faker;

class FoodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $categories = Category::all();
        $nutrients = Nutrient::all();
        $healthStatuses = HealthStatus::all();

        // Let's create an array of 100 realistic-sounding food names
        $foodNames = [
            'Apple', 'Banana', 'Orange', 'Strawberry', 'Grape', 'Watermelon', 'Mango', 'Pineapple', 'Kiwi', 'Peach',
            'Broccoli', 'Carrot', 'Spinach', 'Tomato', 'Cucumber', 'Bell Pepper', 'Onion', 'Garlic', 'Potato', 'Sweet Potato',
            'Chicken Breast', 'Beef Steak', 'Pork Chop', 'Turkey', 'Lamb', 'Duck', 'Venison', 'Bacon', 'Sausage', 'Ham',
            'Salmon', 'Tuna', 'Shrimp', 'Cod', 'Crab', 'Lobster', 'Oyster', 'Mussel', 'Squid', 'Octopus',
            'Milk', 'Cheddar Cheese', 'Yogurt', 'Butter', 'Cream', 'Mozzarella', 'Parmesan', 'Eggs', 'Cottage Cheese', 'Ice Cream',
            'Brown Rice', 'White Rice', 'Oats', 'Quinoa', 'Whole Wheat Bread', 'Pasta', 'Corn', 'Barley', 'Millet', 'Buckwheat',
            'Almonds', 'Walnuts', 'Peanuts', 'Cashews', 'Pistachios', 'Chia Seeds', 'Flaxseeds', 'Sunflower Seeds', 'Pumpkin Seeds', 'Sesame Seeds',
            'Lentils', 'Chickpeas', 'Black Beans', 'Kidney Beans', 'Pinto Beans', 'Soybeans', 'Navy Beans', 'Green Peas', 'Lima Beans', 'Edamame',
            'Coffee', 'Green Tea', 'Black Tea', 'Orange Juice', 'Apple Juice', 'Almond Milk', 'Soy Milk', 'Coconut Water', 'Lemonade', 'Smoothie',
            'Chocolate Bar', 'Potato Chips', 'Popcorn', 'Cookies', 'Brownie', 'Cake', 'Gummy Bears', 'Ice Pop', 'Donut', 'Muffin'
        ];

        foreach ($foodNames as $index => $name) {
            $category = $categories->random();
            
            $food = Food::create([
                'category_id' => $category->id,
                'name' => $name,
                'scientific_name' => $faker->word . ' ' . $faker->word,
                'description' => $faker->paragraph(2),
                'serving_size' => $faker->randomElement(['100g', '1 cup', '1 piece', '1 slice', '1 oz']),
                'calories' => $faker->randomFloat(2, 20, 800),
                'image' => null // Can be seeded later or left null
            ]);

            // Assign random nutrients
            $randomNutrients = $nutrients->random(rand(5, 15));
            foreach ($randomNutrients as $nutrient) {
                $food->nutrients()->attach($nutrient->id, [
                    'amount' => $faker->randomFloat(2, 0.1, 50)
                ]);
            }

            // Assign random health statuses
            $randomStatuses = $healthStatuses->random(rand(1, 4));
            foreach ($randomStatuses as $status) {
                $food->healthStatuses()->attach($status->id);
            }
        }
    }
}
