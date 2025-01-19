<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VacationType extends Model
{
    protected $fillable = [
        'name',
        'color'
    ];

    public function vacations()
    {
        return $this->hasMany(Vacation::class);
    }
}