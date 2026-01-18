<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'client_id',
        'appointment_date',
        'appointment_time',
        'duration_minutes',
        'session_type',
        'status',
        'notes',
    ];

    protected $casts = [
        'appointment_date' => 'date',
        'appointment_time' => 'datetime',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
