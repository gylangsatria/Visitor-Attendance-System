<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'access_level', 'phone', 'address', 'avatar'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'access_level' => 'integer',
    ];

    public function isAdmin()
    {
        return $this->access_level === 1;
    }

    public function isEditor()
    {
        return $this->access_level === 2;
    }

    public function isViewer()
    {
        return in_array($this->access_level, [3, 4]);
    }

    public function canEdit()
    {
        return in_array($this->access_level, [1, 2]);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function registeredVisitors()
    {
        return $this->hasMany(Visitor::class, 'registered_by');
    }

    public function getAccessLevelNameAttribute()
    {
        $levels = [
            1 => 'Administrator',
            2 => 'Editor',
            3 => 'Staff',
            4 => 'Attendee'
        ];
        return $levels[$this->access_level] ?? 'Unknown';
    }
}