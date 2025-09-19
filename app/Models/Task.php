<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name', 'place', 'assign_by', 'date', 'time', 'day_time', 'work_side', 'created_by', 'completed_at'
    ];

    protected $casts = [
        'date' => 'date',
        'completed_at' => 'datetime',
    ];
    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }
}
