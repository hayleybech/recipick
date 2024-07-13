<template>
    <jet-form-section>
        <template #title>
            Recipes
        </template>

        <template #description>
            Start adding recipes to your meal plan.
        </template>

        <template #form>

            <meal-plan-recipes-form-row
                v-for="recipe in recipes"
                :recipe="recipe"
                :key="recipe.id"
                :all-recipes="allRecipes"
                :editing="false"
                v-on:updateRecipe="updateRecipe"
                v-on:deleteRecipe="deleteRecipe"
            />

            <meal-plan-recipes-form-row
                :key="'add_recipe'"
                :all-recipes="allRecipes"
                :editing="true"
                v-on:storeRecipe="storeRecipe"
            />

            <p>{{ recipes }}</p>

        </template>
    </jet-form-section>
</template>

<script>
import JetFormSection from "@/Jetstream/FormSection";
import MealPlanRecipesFormRow from "@/Pages/MealPlans/MealPlanRecipesFormRow";

export default {
    name: "MealPlanRecipesForm",
    components: {
        MealPlanRecipesFormRow,
        JetFormSection,
    },
    props: ['allRecipes'],
    data() {
        return {
            'recipes' : [
                {
                    'id': 1,
                    'name': 'Butter Chicken',
                    'pivot': {
                        'servings': 2,
                        'meal': 'Dinner'
                    }
                },
                {
                    'id': 3,
                    'name': 'Pizza',
                    'pivot': {
                        'servings': 4,
                        'meal': 'Lunch',
                    }
                }
            ]
        }
    },
    methods: {
        storeRecipe(recipe){
            this.recipes.push(recipe);
        },

        updateRecipe(recipe) {
            this.recipes[this.recipes.findIndex((item)=>item.id===recipe.id)] = recipe;
        },

        deleteRecipe(recipe) {
            this.recipes.splice(this.recipes.findIndex((item)=>item.id===recipe.id), 1);
        }
    }
}
</script>

<style scoped>

</style>
