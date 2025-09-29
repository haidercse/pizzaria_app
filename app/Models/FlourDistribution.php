<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FlourDistribution extends Model
{
    protected $fillable = [
        'water_l',
        'total_flour',
        'tipo_00',
        'tipo_1',
        'dough_kg',
        'cajas',
        'divide_boxes'
    ];
}
