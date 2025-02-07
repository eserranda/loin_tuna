<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Inspection extends Model
{
    protected $fillable = [
        'ilc',
        'stage',
        'uji_lab',
        'tekstur',
        'bau',
        'es',
        'suhu',
        'keterangan',
        'hasil',
    ];
}
