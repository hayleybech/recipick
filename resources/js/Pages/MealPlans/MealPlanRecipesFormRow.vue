<template>
        <div class="col-span-12 sm:col-span-2">
            <template v-if="! editEnabled">
                <span class="text-xs text-gray-500 py-2 mt-1">{{ recipe.name }}</span>
            </template>
            <template v-else>
                <jet-label for="recipe" value="Recipe" />
                <select v-model="recipe.id" id="recipe_id" name="recipe_id" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm overflow-ellipsis    mt-1 block w-full text-xs">
                    <option v-for="currentRecipe in allRecipes" :value="currentRecipe.id" :selected="currentRecipe.id === recipe.id">{{ currentRecipe.name }}</option>
                </select>
                <!--<jet-input-error :message="form.errors.recipe_id" class="mt-2" />-->
            </template>
        </div>

        <div class="col-span-12 sm:col-span-1">
            <template v-if="! editEnabled">
                <span class="text-xs text-gray-500 py-2 mt-1">{{ recipe.pivot.servings }}</span>
            </template>
            <template v-else>
                <jet-label for="servings" value="Servings" />
                <jet-input id="servings" type="number" min="1" max="255" class="mt-1 block w-full text-xs" v-model="recipe.pivot.servings" />
                <!--<jet-input id="quantity" type="number" min="1" max="255" class="mt-1 block w-full text-xs" v-model="form.quantity" />-->
                <!--<jet-input-error :message="form.errors.quantity" class="mt-2" />-->
            </template>
        </div>

        <div class="col-span-12 sm:col-span-1">
            <template v-if="! editEnabled">
                <span class="text-xs text-gray-500 py-2 mt-1">{{ recipe.pivot.meal }}</span>
            </template>
            <template v-else>
                <jet-label for="meal" value="Meal" />
                <select v-model="recipe.pivot.meal" id="meal" name="meal" class="border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm    mt-1 block w-full text-xs">
                    <option v-for="meal in meals" :selected="meal === recipe.pivot.meal">{{ meal }}</option>
                </select>
                <!--<jet-input-error :message="form.errors.meal" class="mt-2" />-->
            </template>
        </div>

        <div class="col-span-12 sm:col-span-2">
            <template v-if="editEnabled">
                <jet-label value="&nbsp;" />
            </template>

            <div class="flex flex-row gap-x-1 mt-1">
                <template v-if="! editEnabled">
                    <jet-secondary-button type="button" class="text-xs flex-col flex-grow" @click="destroy">
                        Remove
                    </jet-secondary-button>
                    <jet-button type="button" class="text-xs flex-col flex-grow" @click="editEnabled = true">
                        Edit
                    </jet-button>
                </template>
                <template v-else>
                    <jet-button type="button" class="text-xs flex-col flex-grow" @click="store">
                        Save
                    </jet-button>
                </template>
            </div>
        </div>
</template>

<script>
import JetButton from "@/Jetstream/Button";
import JetInput from "@/Jetstream/Input";
import JetInputError from "@/Jetstream/InputError";
import JetLabel from "@/Jetstream/Label";
import JetSecondaryButton from "@/Jetstream/SecondaryButton";

const NullRecipe = {
    'id': null,
    'name': null,
    'pivot': {
        'servings': null,
        'meal': null
    }
}

export default {
    name: "MealPlanRecipesFormRow",
    props: {
        'recipe': {
            'default': NullRecipe
        },
        'allRecipes': {
            'required': true,
        },
        'editing': {}
    },
    components: {
        JetButton,
        JetInput,
        JetInputError,
        JetLabel,
        JetSecondaryButton,
    },
    data() {
        return {
            'meals': ['Breakfast', 'Lunch', 'Dinner', 'Dessert', 'Other'],
            editEnabled: this.editing,
        }
    },
    methods: {
        store() {
            this.recipe.name = this.allRecipes.find((item)=>item.id===this.recipe.id).name;
            if(this.recipe.id){
                this.$emit('updateRecipe', this.recipe);
                this.editEnabled = false;
            } else {
                this.$emit('storeRecipe', this.recipe);
                //this.recipe = NullRecipe;
            }
        },
        destroy(){
            this.$emit('deleteRecipe', this.recipe);
        }
    },
}
</script>

<style scoped>

</style>
