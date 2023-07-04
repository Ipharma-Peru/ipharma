<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvoiceSeries;
use Illuminate\Http\Request;

class InvoiceSeriesController extends Controller
{
    /**
     * Retorna el correlativo de la
     * serie indicada
     */
    public function getCorrelativeBySerie(string $serie)
    {
        return InvoiceSeries::where('serie', $serie)
            ->where('activo', true)
            ->value('correlativo');
    }

    public function getCorrelativeBySerieFront(Request $request)
    {
        return InvoiceSeries::where('serie', $request->serie)
            ->where('activo', true)
            ->value('correlativo');
    }

    /**
     * Se obtiene el correlativo y se incrementa
     * solo para uso interno y controlando que
     * la emision del documento sea exitosa
     */
    public function getAndIncreaseCorrelative(string $serie)
    {
        $invoiceSeries = InvoiceSeries::where('serie', $serie)
            ->where('activo', true)
            ->lockForUpdate()
            ->first();
        $actual = $invoiceSeries->correlativo;
        $invoiceSeries->correlativo++;
        $invoiceSeries->save();
        
        return $actual;
    }

    public function getVoucherData(int $salesId)
    {
        return InvoiceSeries::join('invoice_types','invoice_types.id','invoice_series.invoice_type_id')
            ->join('sales','sales.invoice_series_id','invoice_series.id')
            ->join('currencies','currencies.id','sales.currency_id')
            ->join('sale_statuses','sale_statuses.id','sales.sale_status_id')
            ->select(
                'invoice_types.codigo as tipo_documento',
                'invoice_series.serie',
                'sales.correlativo',
                'sales.fecha_emision',
                'currencies.codigo as moneda'
            )
            ->where('sales.id',$salesId)
            ->where('sale_statuses.estado','REGISTRADO')
            ->first();
    }
}
