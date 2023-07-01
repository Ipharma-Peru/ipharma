<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AffectationType extends Model
{
    use HasFactory;

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
