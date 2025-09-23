<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmployeeAvailability extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'date',
        'preferred_time',
        'start_time',
        'end_time',
        'user_start_time',
        'user_end_time',
        'note',
        'hours',
    ];

    public function getCalculatedHoursAttribute()
    {
        if ($this->start_time && $this->end_time) {
            $start = Carbon::parse($this->start_time);
            $end = Carbon::parse($this->end_time);
            if ($end->lt($start)) {
                $end->addDay(); // overnight shift
            }
            return $end->floatDiffInHours($start);
        }
        return 0;
    }

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
