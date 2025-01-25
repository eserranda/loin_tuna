<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductLog extends Model
{
    protected $fillable = [
        'ilc',
        'id_produk',
        'no_loin',
        'berat',
        // 'ekspor',
    ];

    public function produk()
    {
        return $this->belongsTo(Product::class, 'id_produk');
    }
}
