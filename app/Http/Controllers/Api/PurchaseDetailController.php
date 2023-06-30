<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\LotController;
use App\Models\PurchaseDetail;

class PurchaseDetailController extends Controller
{
    public function store(array $items, int $compraId)
    {
        foreach ($items as $item) {
            $lote = new LotController();
            $data = [
                'numero_lote' => $item['lote'],
                'fecha_vencimiento' => $item['fechaVencimiento'],
                'fecha_produccion' => $item['fechaProduccion'] ?? null
            ];

            $loteId = $lote->store($data);

            $detalle = new PurchaseDetail();
            $detalle->cantidad = $item['cantidad'];
            $detalle->precio_unitario = $item['precioUnitario'];
            $detalle->purchase_id = $compraId;
            $detalle->product_id = $item['idProducto'];
            $detalle->lot_id = $loteId;
            $detalle->save();
        }
    }
}
