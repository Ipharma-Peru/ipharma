<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceSeries extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function invoiceType()
    {
        return $this->belongsTo('App\Models\InvoiceType');
    }

    public function invoiceNumber()
    {
        return $this->belongsTo('App\Models\InvoiceNumber');
    }

    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }
}
