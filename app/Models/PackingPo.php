<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PackingPo extends Model
{
    protected $fillable = [
        'po_number',
        'id_product',
        'total_qty',
        'total_weight',
        'progress',
    ];

    public function produk()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
