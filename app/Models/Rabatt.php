<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Rabatt extends Model
{
    use HasFactory;

    protected $table = 'rabatts';

    protected $fillable = [
        'display_name',
        'description',
        'rabatt_percent',
        'created_by'
    ];

    protected $casts = [
        'rabatt_percent' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}