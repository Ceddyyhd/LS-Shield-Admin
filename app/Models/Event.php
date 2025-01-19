<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $table = 'eventplanung';

    protected $fillable = [
        'vorname_nachname',
        'telefonnummer',
        'anfrage',
        'datum_uhrzeit',
        'status',
        'summernote_content',
        'team_verteilung',
        'datum_uhrzeit_event',
        'ort',
        'event_lead',
        'event',
        'anmerkung'
    ];

    public function registeredEmployees()
    {
        return $this->hasMany(EventRegistration::class, 'event_id');
    }

    public function teamMembers()
    {
        return $this->belongsToMany(User::class, 'event_mitarbeiter_anmeldung', 'event_id', 'employee_id');
    }
}