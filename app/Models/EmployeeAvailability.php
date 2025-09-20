<?php

namespace App\Models;

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
        'note',
    ];

    // relation with user
    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
