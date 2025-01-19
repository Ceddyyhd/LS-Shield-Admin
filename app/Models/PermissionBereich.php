<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionBereich extends Model
{
    use HasFactory;
    protected $table = 'permission_bereiche';
    protected $fillable = [
        'name',
        'display_name',
    ];
}