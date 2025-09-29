<?php

namespace Database\Seeders;

use App\Models\PhaseTable;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PhaseTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [3, 3.30, 0.48, 0.94, 0.71, 0.36, 0.18, 0.18],
            [10, 11.00, 1.59, 3.15, 2.37, 1.19, 0.59, 0.59],
            [11, 12.10, 1.75, 3.46, 2.61, 1.30, 0.65, 0.65],
            [12, 13.20, 1.91, 3.78, 2.84, 1.42, 0.71, 0.71],
            [13, 14.30, 2.07, 4.09, 3.08, 1.54, 0.77, 0.77],
            [14, 15.40, 2.23, 4.41, 3.32, 1.66, 0.83, 0.83],
            [15, 16.50, 2.39, 4.72, 3.56, 1.78, 0.89, 0.89],
            [16, 17.60, 2.55, 5.04, 3.79, 1.90, 0.95, 0.95],
        ];

         foreach ($data as $row) {
            PhaseTable::create([
                'water_l' => $row[0],
                'phase1_tipo00' => $row[1],
                'phase2_tipo00' => $row[2],
                'phase2_tipo1' => $row[3],
                'first_15min' => $row[4],
                'second_8min' => $row[5],
                'third_8min' => $row[6],
                'fourth_8min' => $row[7],
            ]);
        }
    }
}
