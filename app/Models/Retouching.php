<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retouching extends Model
{

    protected $fillable = [
        'tanggal',
        'id_supplier',
        'ilc',
        'ilc_cutting',
        'no_loin',
        'berat',
        'sisa_berat',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
