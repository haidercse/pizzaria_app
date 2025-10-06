<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            UserSeeder::class,
            DoughListSeeder::class,
            TaskSeeder::class,
            EmployeeAvailabilitySeeder::class,
            EmployeeCheckoutSeeder::class,
            PrepSeeder::class,
            YeastSaltSeeder::class,
            FlourDistributionSeeder::class,
            EventSeeder::class,
            PhaseTableSeeder::class
        ]);
    }
}
