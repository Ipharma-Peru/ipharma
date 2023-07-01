<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductClass extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function productSubclasses()
    {
        return $this->hasMany('App\Models\ProductSubclass');
    }
}
