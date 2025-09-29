<?php

namespace Database\Seeders;

use App\Models\FlourDistribution;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FlourDistributionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [3, 4.72, 3.78, 0.94, 7.7, 2.7, 2],
            [10, 15.74, 12.59, 3.15, 25.7, 9.0, 3],
            [11, 17.31, 13.85, 3.46, 28.3, 9.9, 3],
            [12, 18.89, 15.11, 3.78, 30.9, 10.8, 3],
            [13, 20.46, 16.37, 4.09, 33.5, 11.7, 3],
            [14, 22.04, 17.63, 4.41, 36.0, 12.6, 4],
            [15, 23.61, 18.89, 4.72, 38.6, 13.5, 4],
            [16, 25.18, 20.15, 5.04, 41.2, 14.5, 4],
        ];
        foreach ($data as $row) {
            FlourDistribution::create([
                'water_l' => $row[0],
                'total_flour' => $row[1],
                'tipo_00' => $row[2],
                'tipo_1' => $row[3],
                'dough_kg' => $row[4],
                'cajas' => $row[5],
                'divide_boxes' => $row[6],
            ]);
        }
    }
}
