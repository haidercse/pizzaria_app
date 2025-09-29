<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prep extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'ingredients', 'process', 'picture'
    ];
}
