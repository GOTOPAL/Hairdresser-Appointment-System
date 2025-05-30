<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hairdresser extends Model
{
    protected $fillable = ['user_id', 'status', 'photo', 'bio'];

    // Kullanıcı ile ilişki
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Kuaförün sunduğu hizmetler (pivot tablo: hairdresser_service)
    public function services()
    {
        return $this->belongsToMany(Service::class, 'hairdresser_service');
    }

    // Randevular
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}
