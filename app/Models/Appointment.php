<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'user_id', 'property_id', 'appt_name', 'appt_phone', 
        'appointment_date', 'appointment_time', 'status'
    ];

    protected $casts = [
        'appointment_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class, 'property_id');
    }
}
