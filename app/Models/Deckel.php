<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Deckel extends Model
{
    protected $fillable = [
        'vehicle_id',
        'notiz',
        'betrag',
        'erstellt_von',
        'location'
    ];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}