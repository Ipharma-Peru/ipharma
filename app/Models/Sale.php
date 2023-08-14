<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    public static function listarVentas($desde,$hasta){

        return static::select(
            'ser.serie',
            'sales.correlativo',
            'prod.descripcion',
            'sales.fecha_emision',
            'det.cantidad',
            DB::raw('round((det.precio_unitario / 1.18), 2) as valor_unitario'),
            'det.precio_unitario',
            DB::raw('(round((det.precio_unitario / 1.18), 2) * det.cantidad) as valor_total'),
            DB::raw('(det.precio_unitario * det.cantidad) as importe_totalsale_detailssale_details'),
            DB::raw('((det.precio_unitario - round((det.precio_unitario / 1.18), 2)) * det.cantidad) as igv')
        )
        ->rightJoin('sale_details as det', 'sales.id', '=', 'det.sale_id')
        ->join('invoice_series as ser', 'sales.invoice_series_id', '=', 'ser.id')
        ->join('products as prod', 'prod.id', '=', 'det.product_id')
        ->whereBetween('sales.fecha_emision', [$desde,$hasta])
        ->orderBy('sales.fecha_emision', 'desc');
    }
}
