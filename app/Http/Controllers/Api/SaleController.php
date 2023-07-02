<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\SaleDetailController;
use App\Http\Controllers\Api\InvoiceSeriesController;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SaleController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $correlativo = new InvoiceSeriesController();
            $numeroCorrelativo = $correlativo->getAndIncreaseCorrelative('B001'); // TODO: Implementar logica

            $sale = new Sale();
            $sale->invoice_series_id = 1; // TODO: Actualizar envio desde el front
            $sale->fecha_emision = $request->fecha_emision;
            $sale->correlativo = $numeroCorrelativo;
            $sale->client_id = $request->idCliente;
            $sale->company_id = 1; //TODO
            $sale->currency_id = 1; //TODO
            $sale->tax_rate_id = 1; //TODO
            $sale->sale_status_id = 1; //TODO
            $sale->user_id = Auth::id(); //TODO
            // $sale->user_id = 1;
            $sale->save();
            
            $saleId = $sale->id;

            $saleDetails = new SaleDetailController();
            $saleDetails->registerSalesItems($request->items, $saleId);

            DB::commit();
            return ['status' => true];

        } catch (\Exception $e) {
            DB::rollBack();
            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();

            return [
                'status' => false,
                'message' => $errorMessage,
                'code' => $errorCode
            ];
        }

    }

}
