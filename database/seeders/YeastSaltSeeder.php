<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YeastSaltSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            ['water_l' => 3,  'y07' => 2.1, 'y08' => 2.4, 'y09' => 2.7, 'y10' => 3.0, 'y11' => 3.3, 'y12' => 3.6, 'y13' => 3.9, 'salt38' => 114, 'salt39' => 117],
            ['water_l' => 10, 'y07' => 7.0, 'y08' => 8.0, 'y09' => 9.0, 'y10' => 10.0, 'y11' => 11.0, 'y12' => 12.0, 'y13' => 13.0, 'salt38' => 380, 'salt39' => 390],
            ['water_l' => 11, 'y07' => 7.7, 'y08' => 8.8, 'y09' => 9.9, 'y10' => 11.0, 'y11' => 12.1, 'y12' => 13.2, 'y13' => 14.3, 'salt38' => 418, 'salt39' => 429],
            ['water_l' => 12, 'y07' => 8.4, 'y08' => 9.6, 'y09' => 10.8, 'y10' => 12.0, 'y11' => 13.2, 'y12' => 14.4, 'y13' => 15.6, 'salt38' => 456, 'salt39' => 468],
            ['water_l' => 13, 'y07' => 9.1, 'y08' => 10.4, 'y09' => 11.7, 'y10' => 13.0, 'y11' => 14.3, 'y12' => 15.6, 'y13' => 16.9, 'salt38' => 494, 'salt39' => 507],
            ['water_l' => 14, 'y07' => 9.8, 'y08' => 11.2, 'y09' => 12.6, 'y10' => 14.0, 'y11' => 15.4, 'y12' => 16.8, 'y13' => 18.2, 'salt38' => 532, 'salt39' => 546],
            ['water_l' => 15, 'y07' => 10.5, 'y08' => 12.0, 'y09' => 13.5, 'y10' => 15.0, 'y11' => 16.5, 'y12' => 18.0, 'y13' => 19.5, 'salt38' => 570, 'salt39' => 585],
            ['water_l' => 16, 'y07' => 11.2, 'y08' => 12.8, 'y09' => 14.4, 'y10' => 16.0, 'y11' => 17.6, 'y12' => 19.2, 'y13' => 20.8, 'salt38' => 608, 'salt39' => 624],
        ];

        DB::table('yeast_salts')->insert($data);
    }
}
