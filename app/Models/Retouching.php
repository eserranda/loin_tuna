<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Retouching extends Model
{
    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier');
    }
}
