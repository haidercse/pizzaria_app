<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoughList extends Model
{
     use HasFactory;
     protected $fillable = [
        'dough_litter',
        'dough_total_weight',
        'dough_num_of_cajas',
        'day',
        'date',
    ];
    protected $table = 'dough_list';
}
