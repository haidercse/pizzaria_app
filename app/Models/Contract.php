<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'start_date',
        'end_date',
        'hourly_rate',
        'bank_account',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
