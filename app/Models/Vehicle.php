<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    protected $fillable = [
        'model',
        'license_plate',
        'location',
        'next_inspection',
        'notes',
        'decommissioned',
        'turbo_tuning',
        'engine_tuning',
        'transmission_tuning',
        'brake_tuning'
    ];

    protected $casts = [
        'next_inspection' => 'date',
        'decommissioned' => 'boolean',
        'turbo_tuning' => 'boolean',
        'engine_tuning' => 'boolean',
        'transmission_tuning' => 'boolean',
        'brake_tuning' => 'boolean'
    ];

    public function logs()
    {
        return $this->hasMany(VehicleLog::class);
    }
}