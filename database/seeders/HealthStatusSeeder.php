<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\HealthStatus;

class HealthStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'Healthy',
            'Unhealthy',
            'Vegetarian',
            'Vegan',
            'High Protein',
            'Low Fat',
            'Low Carb',
            'Diabetic Friendly',
            'Heart Healthy',
            'Keto',
            'Gluten Free',
            'Lactose Free'
        ];

        foreach ($statuses as $status) {
            HealthStatus::create([
                'name' => $status,
                'slug' => Str::slug($status)
            ]);
        }
    }
}
