<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{

    protected $fillable = [
        'client_id', 'hairdresser_id', 'service_id', 'date', 'time', 'status'
    ];



    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function hairdresser() {
        return $this->belongsTo(Hairdresser::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function review() {
        return $this->hasOne(Review::class);
    }}
