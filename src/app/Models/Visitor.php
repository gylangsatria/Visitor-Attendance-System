<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Visitor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'id_card_number', 'company', 
        'purpose', 'person_to_meet', 'check_in_time', 'check_out_time', 
        'status', 'registered_by'
    ];

    protected $casts = [
        'check_in_time' => 'datetime',
        'check_out_time' => 'datetime'
    ];

    public function registrar()
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}