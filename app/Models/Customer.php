<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'user_id',
        'kode',
        'phone',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'jalan',
        'image',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
