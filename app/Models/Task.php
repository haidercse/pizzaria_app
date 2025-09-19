<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'name',
        'place',
        'assign_by',
        'date',
        'time',
        'day_time',
        'work_side',
        'created_by',
        'completed_at',
        'is_done'
    ];

    protected $casts = [
        'is_done' => 'array',
    ];
    public function getSubTasksAttribute()
    {
        $parts = preg_split('/,|\d+\.\s*/', $this->name, -1, PREG_SPLIT_NO_EMPTY);
        return array_values(array_map('trim', $parts)); // reset index
    }

    public function completions()
    {
        return $this->hasMany(TaskCompletion::class);
    }
}
