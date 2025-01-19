<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialEntry extends Model
{
    protected $fillable = [
        'type',
        'category',
        'note',
        'amount',
        'user_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}