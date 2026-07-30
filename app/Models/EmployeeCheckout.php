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
        'nusle_total_tips',
        'andel_total_tips',
        'worked_hours',

    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
