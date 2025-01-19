<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialCategory extends Model
{
    protected $fillable = ['name'];

    public function entries()
    {
        return $this->hasMany(FinancialEntry::class, 'category_id');
    }
}