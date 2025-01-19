<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Salary extends Model
{
    protected $fillable = [
        'employee_id',
        'salary',
        'share',
        'tips'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}