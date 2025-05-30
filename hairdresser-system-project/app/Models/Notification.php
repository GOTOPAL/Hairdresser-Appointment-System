<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{

    protected $fillable = [
        'hairdresser_id',
        'client_id',
        'title',
        'message',
        'is_read', // bu alan da varsa ekleyebilirsin
        'appointment_id',
        'is_global'
    ];
    public function hairdresser()
    {
        return $this->belongsTo(Hairdresser::class);
    }
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

}
