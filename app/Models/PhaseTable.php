<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhaseTable extends Model
{
    protected $table = 'phase_tables';
    use HasFactory;

    protected $fillable = [
        'water_l',
        'phase1_tipo00',
        'phase2_tipo00',
        'phase2_tipo1',
        'first_15min',
        'second_8min',
        'third_8min',
        'fourth_8min',
    ];
}
