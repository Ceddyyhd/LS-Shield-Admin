<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    protected $fillable = [
        'key_name',
        'display_name',
        'category',
        'description',
        'stock',
        'is_consumable'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_equipment')->withPivot('quantity');
    }

    public function logs()
    {
        return $this->hasMany(EquipmentLog::class);
    }
}