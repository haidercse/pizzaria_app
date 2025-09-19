<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskCompletion extends Model
{
    protected $table = 'task_completions';
    protected $fillable = [
        'task_id',
        'user_id',
        'date',
        'completed',
    ];
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
