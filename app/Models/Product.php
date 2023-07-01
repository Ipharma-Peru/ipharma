<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function productSubclass()
    {
        return $this->belongsTo('App\Models\ProductSubclass');
    }

    public function presentationDetail()
    {
        return $this->hasOne('App\Models\PresentationDetail');
    }

    public function productPrices()
    {
        return $this->hasMany('App\Models\ProductPrice');
    }

    public function laboratoy()
    {
        return $this->belongsTo('App\Models\Laboratory');
    }

    public function inventories()
    {
        return $this->hasMany('App\Models\Inventory');
    }

    public function presentation()
    {
        return $this->belongsTo('App\Models\Presentation');
    }

    public function activeSubstances()
    {
        return $this->belongsToMany('App\Models\ActiveSubstance');
    }

    public function pharmaActions()
    {
        return $this->belongsToMany('App\Models\PharmaAction');
    }

    public function unit()
    {
        return $this->belongsTo('App\Models\Unit');
    }

    public function affectationType()
    {
        return $this->belongsTo('App\Models\AffectationType');
    }

    public function priceType()
    {
        return $this->belongsTo('App\Models\PriceType');
    }
}
