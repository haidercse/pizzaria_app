<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmployeeAvailabilitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->get(); // সব user
        $today = Carbon::today();

        foreach ($users as $user) {
            for ($i = 0; $i < 7; $i++) {
                $date = $today->copy()->addDays($i)->format('Y-m-d');

                $preferred = collect(['morning', 'evening', 'full_day', 'custom'])->random();

                $startTime = null;
                $endTime   = null;

                if ($preferred === 'custom') {
                    // Opening = 08:00, Closing = 22:00 এর মধ্যে random time
                    $startHour = rand(8, 18); // যাতে closing এর আগে থাকে
                    $startMin  = collect([0, 30])->random();
                    $endHour   = rand($startHour + 1, 22);
                    $endMin    = collect([0, 30])->random();

                    $startTime = sprintf('%02d:%02d:00', $startHour, $startMin);
                    $endTime   = sprintf('%02d:%02d:00', $endHour, $endMin);
                }

                DB::table('employee_availabilities')->insert([
                    'employee_id'   => $user->id,
                    'date'          => $date,
                    'preferred_time' => $preferred,
                    'start_time'    => $startTime,
                    'end_time'      => $endTime,
                    'note'          => null,
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ]);
            }
        }
    }
}
