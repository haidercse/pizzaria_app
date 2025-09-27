<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DayTask extends Model
{
    protected $fillable = ['day_of_week', 'task_name'];
}
