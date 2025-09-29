<?php

namespace Database\Seeders;

use App\Models\Prep;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PrepSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SPECIAL PIZZAS
        Prep::create([
            'name' => 'Baciata',
            'ingredients' => 'Mozzarella 70g, Cherry Tomatoes 60g, Grana, Oil, Prosciutto 50g, Flavoured Ricotta 70g, Basil leaves, Honey, Mint, Basil',
            'process' => 'Before baking: add mozzarella, cherry tomatoes, grana, oil. After baking: add prosciutto, flavoured ricotta, basil. Flavoured ricotta prepared with ricotta, honey, mint, basil.'
        ]);

        Prep::create([
            'name' => 'Adelita',
            'ingredients' => 'Eggplants 160g, Grana, Oil, Ricotta salata 20g, Basil leaves',
            'process' => 'Before baking: eggplants, grana, oil. After baking: ricotta salata, basil, oil.'
        ]);

        // Garlic Paste
        Prep::create([
            'name' => 'Garlic Paste',
            'ingredients' => 'Roasted garlic, Water, Olive oil, Salt, Chili peppers',
            'process' => 'Roast garlic 20/30 min at 180°, peel, blend with water, oil, salt, chili until smooth.'
        ]);

        // Roasted Potatoes
        Prep::create([
            'name' => 'Roasted Potatoes',
            'ingredients' => 'Potatoes, Rosemary, Thyme, Garlic, Black pepper, Salt, Olive oil',
            'process' => 'Wash, cube, rinse, season with oil/spices. Bake 200° for 30/40 min (¾ cooked).'
        ]);

        // Polpetta Mix
        Prep::create([
            'name' => 'Polpetta Mix',
            'ingredients' => 'Parsley leaves 10g, Garlic 4g',
            'process' => 'Finely chop parsley and garlic after removing internal sprout.'
        ]);

        // Pumpkin Cream
        Prep::create([
            'name' => 'Pumpkin Cream',
            'ingredients' => 'Pumpkin Hokkaido, Rosemary, Thyme, Garlic powder, Garlic cloves, Olive oil, Salt, Black pepper',
            'process' => 'Bake seasoned cubes 200° for 20/30 min with garlic. Blend with olive oil + water.'
        ]);

        // Sugo (Tomato Sauce)
        Prep::create([
            'name' => 'Sugo',
            'ingredients' => 'Tomatoes 5Kg, Olive oil 115g, Basil stems 15g, Basil leaves 10g, Garlic 23g, Salt, Sugar',
            'process' => 'Blend tomatoes with salt, fry garlic + basil stems in oil, add tomatoes + sugar + basil leaves, cook until reduced by half.'
        ]);

        // Red Peppers Pesto
        Prep::create([
            'name' => 'Red Peppers Pesto',
            'ingredients' => 'Roasted peppers 500g, Sunflower seeds 25g, Roasted garlic, Chili pepper, Rosemary, Olive oil, Salt',
            'process' => 'Roast peppers at 210° for 30 min, peel, blend with other ingredients.'
        ]);

        // Lemon Oil
        Prep::create([
            'name' => 'Lemon Oil',
            'ingredients' => 'Olive oil 60%, Lemon juice 40%, Salt, Black pepper',
            'process' => 'Blend all ingredients until creamy.'
        ]);

        // Pistachios
        Prep::create([
            'name' => 'Salted Pistachios',
            'ingredients' => 'Pistachios 200g, Salt 7g',
            'process' => 'Mix pistachios with salt.'
        ]);

        // Tomato Sauce (simple)
        Prep::create([
            'name' => 'Tomato Sauce',
            'ingredients' => '1 can peeled tomatoes, Salt 17g',
            'process' => 'Mix tomatoes with salt.'
        ]);

        // Dried Tomatoes Pesto
        Prep::create([
            'name' => 'Dried Tomatoes Pesto',
            'ingredients' => 'Dried tomatoes 250g, Oil 60g, Water 150g, Sunflower seeds 20g, Garlic 1g, Cheese mix 20g, Basil leaves 5g, Lemon juice 15g, Salt 2g',
            'process' => 'Blend all ingredients until creamy, adjust with water if needed.'
        ]);

        // Pea's Cream
        Prep::create([
            'name' => 'Pea\'s Cream',
            'ingredients' => 'Peas 900g, Pea water 250g, Lemon juice 70g, Olive oil 70g, Salt 8g, Black pepper',
            'process' => 'Cook peas 6/7 min, cool, blend with lemon juice, oil, water, salt, pepper.'
        ]);

        // Eggplant's Cream
        Prep::create([
            'name' => 'Eggplant\'s Cream',
            'ingredients' => 'Eggplant 800g, Olive oil 70g, Basil 8g, Garlic 1g, Salt 8g, Black pepper',
            'process' => 'Roast eggplants 45-60 min, peel, blend with oil, basil, garlic, salt, pepper.'
        ]);
    }
    
}
