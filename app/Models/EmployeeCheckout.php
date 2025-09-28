<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeCheckout extends Model
{
    protected $fillable = [
        'employee_id',
        'date',
        'day',
        'place',
        'worked_hours',
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
