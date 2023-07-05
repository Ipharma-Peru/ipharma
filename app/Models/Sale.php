<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function currency()
    {
        return $this->belongsTo('App\Models\Currency');
    }

    public function taxRate()
    {
        return $this->belongsTo('App\Models\TaxRate');
    }

    public function saleStatus()
    {
        return $this->belongsTo('App\Models\SaleStatus');
    }

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    public function saleDetails()
    {
        return $this->hasMany('App\Models\SaleDetail');
    }

    public function invoiceStatus()
    {
        return $this->hasOne('App\Models\InvoiceStatus');
    }
}
