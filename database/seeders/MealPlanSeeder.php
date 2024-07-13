<?php

namespace Database\Seeders;

use App\Models\MealPlan;
use App\Models\Recipe;
use Illuminate\Database\Seeder;

class MealPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MealPlan::factory()
            ->count(10)
            ->hasAttached(
                Recipe::factory()
                    ->count(6)
                    ->hasItems(10),
                ['servings' => 3]
            )
            ->create();
    }
}
