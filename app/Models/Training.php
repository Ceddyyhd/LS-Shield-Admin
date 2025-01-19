<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    protected $fillable = [
        'grund',
        'info',
        'leitung',
        'datum_zeit'
    ];

    protected $casts = [
        'datum_zeit' => 'datetime'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'training_user');
    }
}