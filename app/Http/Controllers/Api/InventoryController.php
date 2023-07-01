<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;

class InventoryController extends Controller
{
    /**
     * Metodo que permite ingresar un nuevo registro
     * a la tabla inventario, la cantidad debe ser
     * la cantidad total unitaria que sera sumada
     * al stock disponible
     */
    public function store(object $data)
    {
        $inventory = new Inventory();
        $inventory->stock = $data->stock_total;
        $inventory->product_id = $data->product_id;
        $inventory->lot_id = $data->lote_id;
        $inventory->save();
    }

    /**
     * Metodo que permite calcular el total de
     * unidades, recibe como parametro el
     * numero de cajas (compras)
     */
    public function calculateTotalUnitsByBoxes(int $idProduct, int $numberOfBox)
    {
        $product = Product::find($idProduct);
        $unitsPerBox = $product->presentationDetail->unidades_por_caja;
        if ($unitsPerBox == null || $unitsPerBox == 0) {
            $total = $numberOfBox;
        } else {
            $total = $unitsPerBox * $numberOfBox;
        }

        return $total;
    }
}
