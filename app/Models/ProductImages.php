<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    protected $fillable = [
        'id_product',
        'file_name',
        'file_path',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'id_product');
    }
}
