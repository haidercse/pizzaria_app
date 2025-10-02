<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function availabilities()
    {
        return $this->hasMany(EmployeeAvailability::class, 'employee_id', 'id');
    }

    public function shiftAssignments()
    {
        return $this->hasMany(ShiftAssignment::class, 'employee_id', 'id');
    }
    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }
    public function checkouts()
    {
        return $this->hasMany(\App\Models\EmployeeCheckout::class, 'employee_id');
    }
    public function contract()
    {
        return $this->hasOne(\App\Models\Contract::class);
    }
}
