<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    protected $table = 'event_mitarbeiter_anmeldung';
    
    protected $fillable = ['event_id', 'employee_id', 'notizen'];

    public function employee()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function event()
    {
        return $this->belongsTo(Event::class, 'event_id');
    }

    public function dienstplan()
    {
        return $this->hasOne(DienstplanZeiten::class, 'employee_id', 'employee_id')
                    ->where('event_id', $this->event_id);
    }
}