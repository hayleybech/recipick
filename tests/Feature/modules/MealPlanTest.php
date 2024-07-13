<?php

namespace Tests\Feature\modules;

use App\Models\MealPlan;
use App\Models\Recipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\Assert;
use Tests\TestCase;

class MealPlanTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_meal_plans(): void
    {
        // Arrange
        $this->actingAs($user = User::factory()->create());
        MealPlan::factory()
            ->count(10)
            ->hasAttached(
                Recipe::factory()->count(6),
                ['servings' => 3]
            )
            ->create();

        // Act
        $response = $this->get(route('mealPlans.index'));

        // Assert
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('MealPlans/Index')
            ->has('mealPlans', 10, fn(Assert $page) => $page
                ->has('name')
                ->has('start_date')
                ->where('recipes_count', 6)
                ->etc()
            )
        );
    }

    public function test_user_can_view_create_meal_plan_form(): void
    {
        // Arrange
        $this->actingAs($user = User::factory()->create());

        // Act
        $response = $this->get(route('mealPlans.create'));

        // Assert
        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page->component('MealPlans/Create'));
    }

    public function test_user_can_store_a_meal_plan(): void
    {
        // Arrange
        $this->actingAs($user = User::factory()->create());
        $recipes = Recipe::factory()->count(2)->create();

        // Act
        $response = $this->post(route('mealPlans.store'), [
            'name'          => 'This Week',
            'start_date'    => today(),
            'recipes'       => [
                [
                    'id'        => $recipes[0]->id,
                    'servings'  => 2,
                ],
                [
                    'id'        => $recipes[1]->id,
                    'servings'  => 3,
                ]
            ],
        ]);

        // Assert
        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('mealPlans.show', ['mealPlan' => MealPlan::value('id')]));
        $this->assertDatabaseHas('meal_plans', [
            'name'          => 'This Week',
            'start_date'    => today(),
        ]);
        $mealPlan = MealPlan::all()->first();
        $this->assertDatabaseHas('meal_plan_recipe', [
            'meal_plan_id'  => $mealPlan->id,
            'recipe_id'     => $recipes[0]->id,
            'servings'      => 2,
        ]);
        $this->assertDatabaseHas('meal_plan_recipe', [
            'meal_plan_id'  => $mealPlan->id,
            'recipe_id'     => $recipes[1]->id,
            'servings'      => 3,
        ]);
    }

    public function test_user_can_view_a_meal_plan(): void
    {
        // Arrange
        $this->actingAs($user = User::factory()->create());

        $mealPlan = MealPlan::factory()
            ->hasAttached(
                Recipe::factory()->count(6),
                ['servings' => 3]
            )
            ->create();

        // Act
        $response = $this->get(route('mealPlans.show', $mealPlan));

        // Assert
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('MealPlans/Show'));
        $response->assertInertia(fn (Assert $page) => $page->component('MealPlans/Show')
            ->has('mealPlan', fn(Assert $page) => $page
                ->where('name', $mealPlan->name)
                ->has('recipes', 6, fn(Assert $page) => $page
                    ->has('name')
                    ->etc()
                )
                ->etc()
            )
        );
    }

    public function test_user_can_view_edit_meal_plan_form(): void
    {
        // Arrange
        $this->actingAs($user = User::factory()->create());
        $mealPlan = MealPlan::factory()->create();

        // Act
        $response = $this->get(route('mealPlans.edit', $mealPlan));

        // Assert
        $response->assertOk();
        $response->assertInertia(fn (Assert $page) => $page->component('MealPlans/Edit')
            ->has('mealPlan', fn(Assert $page) => $page
                ->where('name', $mealPlan->name)
                ->etc()
            )
        );
    }

    public function test_user_can_update_a_meal_plan(): void
    {
        // Arrange
        $this->actingAs($user = User::factory()->create());
        $mealPlan = MealPlan::factory()
            ->hasAttached(
                Recipe::factory()->count(6),
                ['servings' => 3]
            )
            ->create();
        $recipe = Recipe::factory()->create();

        // Act
        $response = $this->put(route('mealPlans.update', $mealPlan), [
            'name'          => 'This Week',
            'start_date'    => today(),
            'recipes'       => [
                [
                    'id'            => $recipe->id,
                    'servings'      => 2,
                ]
            ]
        ]);

        // Assert
        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('mealPlans.show', ['mealPlan' => $mealPlan->id]));
        $this->assertDatabaseHas('meal_plans', [
            'name'          => 'This Week',
            'start_date'    => today(),
        ]);
        $this->assertDatabaseCount('meal_plan_recipe', 1);
        $this->assertDatabaseHas('meal_plan_recipe', [
            'meal_plan_id'  => $mealPlan->id,
            'recipe_id'     => $recipe->id,
            'servings'      => 2,
        ]);
    }

    public function test_user_can_delete_a_meal_plan(): void
    {
        // Arrange
        $this->actingAs($user = User::factory()->create());
        $mealPlan = MealPlan::factory()->create();

        // Act
        $response = $this->delete(route('mealPlans.destroy', $mealPlan));

        // Assert
        $this->assertDeleted($mealPlan);
        $response->assertSessionHasNoErrors()
            ->assertRedirect(route('mealPlans.index'));
    }
}
