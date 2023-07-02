<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceType extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function invoiceSeries()
    {
        return $this->hasMany('App\Models\InvoiceSeries');
    }
}
