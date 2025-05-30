<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receiving extends Model
{
    protected $fillable = [
        'ilc',
        'id_supplier',
        'invoice_number',
        'tanggal',
        'inspection',
        'used',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
