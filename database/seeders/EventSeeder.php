<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::insert([
            ['event_date' => '2025-10-05', 'note' => '160g balls x 100', 'created_at' => now(), 'updated_at' => now()],
            ['event_date' => '2025-10-12', 'note' => 'Big catering order - 200 pizzas', 'created_at' => now(), 'updated_at' => now()],
            ['event_date' => '2025-10-20', 'note' => 'Private party - 50 guests', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
