<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'nama',
        'kode',
        'berat',
        'harga',
        'customer_group',
    ];
}
