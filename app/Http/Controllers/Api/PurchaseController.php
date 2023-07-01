<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Purchase;
use App\Http\Controllers\Api\PurchaseDetailController;

class PurchaseController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $compra = new Purchase();
            $compra->fecha_compra = $request->fechaCompra;
            $compra->numero_documento = $request->numeroDocumento;
            $compra->provider_id = $request->idProveedor;
            $compra->save();

            $compraId = $compra->id;

            $detalle = new PurchaseDetailController();
            $detalle->store($request->items, $compraId);

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
