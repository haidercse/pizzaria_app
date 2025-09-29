<?php

namespace Database\Seeders;

use App\Models\EmployeeCheckout;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmployeeCheckoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first() ?? User::factory()->create([
            'name' => 'Demo Employee',
            'email' => 'demo@employee.com',
            'password' => bcrypt('password'),
        ]);

        // আজকের তারিখ থেকে ৫ দিনের জন্য ডেমো checkout data বানাই
        for ($i = 0; $i < 5; $i++) {
            $date = Carbon::now()->subDays($i);
            EmployeeCheckout::create([
                'employee_id'  => $user->id,
                'date'         => $date->toDateString(),
                'day'          => $date->format('l'),
                'place'        => ['andel', 'nusle', 'event'][array_rand(['andel', 'nusle', 'event'])],
                'worked_hours' => rand(4, 8) + 0.5, // random 4.5 - 8.5 hours
            ]);
        }
    }
}
