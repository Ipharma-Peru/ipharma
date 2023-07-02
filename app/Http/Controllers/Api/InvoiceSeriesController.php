<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\InvoiceSeries;

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
}
