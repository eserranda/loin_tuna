<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ForwardTraceability extends Model
{
    protected $fillable = [
        'ilc',
        'tanggal_receiving',
        'tanggal_cutting',
        'tanggal_retouching',
        'tanggal_packang',
    ];
}
