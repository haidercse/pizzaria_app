<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $tasks = [

            // ✅ FRONT SIDE TASKS - ANDEL
            ['name' => 'Mop and v.cleaner', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'andel'],
            ['name' => 'Main tools set-up', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'andel'],
            ['name' => 'Ovens: clean & plug', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'andel'],
            ['name' => 'Paper rolls refill', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'andel'],
            ['name' => 'Toilet paper and paper towels check', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'andel'],
            ['name' => 'Coffee machine on: 11:00', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'andel'],
            ['name' => 'Drinks fridge refill check', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'andel'],

            // ✅ FRONT SIDE TASKS - NUSLE
            ['name' => 'Check gastros are clean (oil, cherry tomatoes, sponges)', 'day_time' => 'evening', 'work_side' => 'front', 'place' => 'nusle'],
            ['name' => 'Take balls out from fridge at 11:00', 'day_time' => 'evening', 'work_side' => 'front', 'place' => 'nusle'],
            ['name' => 'Tables outside (if not cold)', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'nusle'],
            ['name' => 'Clean dust on shelves (lamps on if necessary)', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'nusle'],
            ['name' => 'Clean focaccia display and menus', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'nusle'],
            ['name' => 'Place the Trancio and Bucatine in the display', 'day_time' => 'morning', 'work_side' => 'front', 'place' => 'nusle'],

            // ✅ BACK SIDE TASKS - ANDEL
            ['name' => 'Ingredients check (freshness, amounts, pizza boxes)', 'day_time' => 'morning', 'work_side' => 'back', 'place' => 'andel'],
            ['name' => 'To-do list of the day (cleaning & catering)', 'day_time' => 'morning', 'work_side' => 'back', 'place' => 'andel'],
            ['name' => 'Check delivery menus (disable items if necessary)', 'day_time' => 'morning', 'work_side' => 'back', 'place' => 'andel'],
            ['name' => 'Eventual grocery shopping', 'day_time' => 'morning', 'work_side' => 'back', 'place' => 'andel'],
            ['name' => 'Empty the drying rack', 'day_time' => 'morning', 'work_side' => 'back', 'place' => 'andel'],

            // ✅ BACK SIDE TASKS - NUSLE
            ['name' => 'Take set of balls out from fridge at 11:00 (check all sets)', 'day_time' => 'morning', 'work_side' => 'back', 'place' => 'nusle'],
            ['name' => 'Most urgent preps', 'day_time' => 'evening', 'work_side' => 'back', 'place' => 'nusle'],
            ['name' => 'Water the basil (if necessary)', 'day_time' => 'evening', 'work_side' => 'back', 'place' => 'nusle'],
            ['name' => 'Cured meat boxes to MozzArt', 'day_time' => 'morning', 'work_side' => 'back', 'place' => 'nusle'],
            ['name' => 'Fridge temps check, place box under sink', 'day_time' => 'morning', 'work_side' => 'back', 'place' => 'nusle'],
        ];

        foreach ($tasks as $task) {
            Task::create($task);
        }
    }
}
