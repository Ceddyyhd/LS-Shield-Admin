<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentLog extends Model
{
    protected $fillable = ['user_id', 'equipment_id', 'quantity', 'action', 'changed_by'];

    // Add missing user relationship
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function changer()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}