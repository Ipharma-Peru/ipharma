<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\LotController;
use App\Http\Controllers\Api\InventoryController;
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
            $idProducto = $item['idProducto'];
            $cantidad = $item['cantidad'];

            $detalle = new PurchaseDetail();
            $detalle->cantidad = $cantidad;
            $detalle->precio_unitario = $item['precioUnitario'];
            $detalle->purchase_id = $compraId;
            $detalle->product_id = $idProducto;
            $detalle->lot_id = $loteId;
            $detalle->save();

            // Actualizar el stock disponible
            $inventory = new InventoryController();
            $data = (object)[
                'stock_total' => $inventory->calculateTotalUnitsByBoxes($idProducto, $cantidad),
                'product_id' => $idProducto,
                'lote_id' => $loteId
            ];

            $inventory->store($data);
        }
    }
}
