<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Packing extends Model
{

    protected $fillable = [
        'po_number',
        'user_id',
        'tanggal',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'id_customer', 'id');
    }

    public function produk()
    {
        return $this->belongsTo(Product::class, 'id_produk', 'id');
    }
}
