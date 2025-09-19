<?php

namespace Database\Seeders;

use App\Models\DoughList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DoughListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DoughList::factory()->count(10)->create();
    }
}
