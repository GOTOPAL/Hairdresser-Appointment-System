<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Mass assignable fields
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // eklendi
        'phone_number',
    ];

    /**
     * Hidden fields when serialized
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * İlişkiler
     */

    public function client()
    {
        return $this->hasOne(Client::class);
    }

    public function hairdresser()
    {
        return $this->hasOne(Hairdresser::class);
    }
    public function hairdresserProfile()
    {
        return $this->hasOne(Hairdresser::class);
    }
    public function clientProfile()
    {
        return $this->hasOne(Client::class);
    }


}
