<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Nutrient;

class NutrientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nutrients = [
            // Macronutrients
            ['name' => 'Protein', 'type' => 'Macronutrient', 'unit' => 'g'],
            ['name' => 'Carbohydrate', 'type' => 'Macronutrient', 'unit' => 'g'],
            ['name' => 'Fat', 'type' => 'Macronutrient', 'unit' => 'g'],
            ['name' => 'Fiber', 'type' => 'Macronutrient', 'unit' => 'g'],
            ['name' => 'Sugar', 'type' => 'Macronutrient', 'unit' => 'g'],
            
            // Vitamins
            ['name' => 'Vitamin A', 'type' => 'Vitamin', 'unit' => 'mcg'],
            ['name' => 'Vitamin B1 (Thiamin)', 'type' => 'Vitamin', 'unit' => 'mg'],
            ['name' => 'Vitamin B2 (Riboflavin)', 'type' => 'Vitamin', 'unit' => 'mg'],
            ['name' => 'Vitamin B3 (Niacin)', 'type' => 'Vitamin', 'unit' => 'mg'],
            ['name' => 'Vitamin B5 (Pantothenic Acid)', 'type' => 'Vitamin', 'unit' => 'mg'],
            ['name' => 'Vitamin B6', 'type' => 'Vitamin', 'unit' => 'mg'],
            ['name' => 'Vitamin B7 (Biotin)', 'type' => 'Vitamin', 'unit' => 'mcg'],
            ['name' => 'Vitamin B9 (Folate)', 'type' => 'Vitamin', 'unit' => 'mcg'],
            ['name' => 'Vitamin B12', 'type' => 'Vitamin', 'unit' => 'mcg'],
            ['name' => 'Vitamin C', 'type' => 'Vitamin', 'unit' => 'mg'],
            ['name' => 'Vitamin D', 'type' => 'Vitamin', 'unit' => 'mcg'],
            ['name' => 'Vitamin E', 'type' => 'Vitamin', 'unit' => 'mg'],
            ['name' => 'Vitamin K', 'type' => 'Vitamin', 'unit' => 'mcg'],
            
            // Minerals
            ['name' => 'Calcium', 'type' => 'Mineral', 'unit' => 'mg'],
            ['name' => 'Iron', 'type' => 'Mineral', 'unit' => 'mg'],
            ['name' => 'Magnesium', 'type' => 'Mineral', 'unit' => 'mg'],
            ['name' => 'Phosphorus', 'type' => 'Mineral', 'unit' => 'mg'],
            ['name' => 'Potassium', 'type' => 'Mineral', 'unit' => 'mg'],
            ['name' => 'Sodium', 'type' => 'Mineral', 'unit' => 'mg'],
            ['name' => 'Zinc', 'type' => 'Mineral', 'unit' => 'mg'],
            ['name' => 'Copper', 'type' => 'Mineral', 'unit' => 'mcg'],
            ['name' => 'Manganese', 'type' => 'Mineral', 'unit' => 'mg'],
            ['name' => 'Selenium', 'type' => 'Mineral', 'unit' => 'mcg'],
            ['name' => 'Iodine', 'type' => 'Mineral', 'unit' => 'mcg'],
            ['name' => 'Fluoride', 'type' => 'Mineral', 'unit' => 'mcg']
        ];

        foreach ($nutrients as $nutrient) {
            Nutrient::create($nutrient);
        }
    }
}
