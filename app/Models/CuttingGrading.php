<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CuttingGrading extends Model
{
    protected $fillable = [
        'id_supplier',
        'ilc',
        'ilc_cutting',
        'berat',
        'sisa_berat',
        'no_loin',
        'grade'
    ];
}
