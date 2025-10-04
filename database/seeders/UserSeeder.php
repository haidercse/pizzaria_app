<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'haider.cse7644@gmail.com')->first();
        if (is_null($user)) {
            $user = User::create([
                'name' => 'Shaiful Islam Haider',
                'email' => 'haider.cse7644@gmail.com',
                'password' => Hash::make('12345678'),
                'is_superadmin' => 1,
            ]);
            $user->assignRole('super admin');
        }
    }
}
