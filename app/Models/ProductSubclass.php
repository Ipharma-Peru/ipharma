<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSubclass extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function productClass()
    {
        return $this->belongsTo('App\Models\ProductClass');
    }

    public function products()
    {
        return $this->hasMany('App\Models\Product');
    }
}
