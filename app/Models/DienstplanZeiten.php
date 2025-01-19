<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DienstplanZeiten extends Model
{
    protected $table = 'dienstplan_zeiten';
    
    protected $fillable = [
        'event_id',
        'employee_id',
        'start_time',
        'end_time',
        'worked_hours'
    ];

    protected $dates = [
        'start_time',
        'end_time'
    ];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}