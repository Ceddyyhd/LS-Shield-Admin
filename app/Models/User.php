<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Attendance;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'umail',
        'nummer',
        'kontonummer',
        'role_id',
        'password',
        'profile_image',
        'waffenschein',
        'licenses',
        'gekuendigt',
        'bewerber',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'licenses' => 'array',
        'gekuendigt' => 'boolean',
        'bewerber' => 'boolean',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function ausbildungen()
    {
        return $this->belongsToMany(Ausbildung::class, 'user_ausbildungen')
                    ->withPivot('rating');
    }

    public function equipment()
    {
        return $this->belongsToMany(Equipment::class, 'user_equipment')->withPivot('quantity');
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->role->permissions);
    }
    public function salary()
{
    return $this->hasOne(Salary::class, 'employee_id');
}
public function salaryHistory()
{
    return $this->hasMany(SalaryHistory::class, 'employee_id')->orderBy('created_at', 'desc');
}
public function attendances()

    {

        return $this->hasMany(Attendance::class);

    }

public function isPresent()
{
    $lastAttendance = $this->attendances()->latest()->first();
    return $lastAttendance && $lastAttendance->status === 'present';
}
}