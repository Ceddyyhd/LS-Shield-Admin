<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InternalEmail extends Model
{
    protected $fillable = [
        'from_user_id',
        'to_user_id',
        'subject',
        'body',
        'read_at',
        'status'
    ];

    protected $dates = [
        'read_at',
        'created_at',
        'updated_at'
    ];

    public function sender()
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}