<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'admin_id',
        'title',
        'body',
        'type',
    ];

    public function admin() {
        return $this->belongsTo(Admin::class);
    }
}
