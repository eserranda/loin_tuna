<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cutting extends Model
{
    protected $fillable = [
        'id_supplier',
        'ilc',
        'ilc_cutting',
        // 'ekspor',
        'inspection',
    ];
}
