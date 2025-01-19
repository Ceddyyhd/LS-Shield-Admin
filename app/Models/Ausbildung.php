<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ausbildung extends Model
{
    use HasFactory;

    protected $table = 'ausbildungen'; // Specify the table name

    protected $fillable = [
        'name',
        'content'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_ausbildungen')
                    ->withPivot('rating');
    }
}