<?php

namespace Database\Factories;

use App\Models\DoughList;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DoughList>
 */
class DoughListFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = DoughList::class;
    
    public function definition(): array
    {
        return [
            'dough_litter' => $this->faker->numberBetween(10, 20),
            'dough_total_weight' => $this->faker->randomFloat(2, 30, 50), // 2 decimal places, min 30, max 50
            'dough_num_of_cajas' => $this->faker->numberBetween(5, 10),
            'day' => $this->faker->dayOfWeek(),
            'date' => Carbon::now()->startOfWeek(),
            'status' => $this->faker->randomElement(['1', '0']),
        ];
    }
}
