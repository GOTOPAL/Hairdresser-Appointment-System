<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = ['name', 'price'];

    public function hairdressers()
    {
        return $this->belongsToMany(Hairdresser::class, 'hairdresser_service');
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
