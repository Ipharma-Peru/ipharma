<?php

namespace App\Http\Controllers\Api;

use RuntimeException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\InventoryController;
use App\Models\SaleDetail;

class SaleDetailController extends Controller
{
    /**
     * Registra los items de la venta y se encarga de realizar
     * las comrpobaciones y descuentos correspondientes del
     * stock
     */
    public function registerSalesItems(array $items, int $saleId)
    {
        foreach ($items as $item) {

            $cantidadTotal = $this->getCantidadTotal($item);

            $this->validateStockItem($item, $cantidadTotal);

            $this->registerItem($item, $saleId);

            $inventory = new InventoryController();
            $inventory->reduceStock($item['idProducto'], $cantidadTotal);
        }

    }

    private function registerItem(array $item, int $saleId)
    {
        $detalle = new SaleDetail();
        $detalle->cantidad = $item['cantidad'];
        $detalle->cantidad_is_fraccion = $item['fraccion'];
        $detalle->precio_unitario = $item['precio'];
        $detalle->product_id = $item['idProducto'];
        $detalle->sale_id = $saleId;
        $detalle->save();
    }

    /**
     * Obtiene la cantidad total del producto en unidades
     */
    private function getCantidadTotal(array $item)
    {
        $idProduct = $item['idProducto'];
        $fraccion = $item['fraccion'];
        $unidades = $item['cantidad'];

        return $this->calculateTotalUnits($idProduct, $unidades, $fraccion);
    }

    /**
     * Valida que la cantidad que se intenta vender
     * sea menor o igual al stock disponible
     */
    public function validateStockItem(array $item, int $cantidadTotal)
    {
        $idProduct = $item['idProducto'];

        $inventory = new InventoryController();

        if ( ! $inventory->stockIsSufficient($idProduct, $cantidadTotal)) {
            throw new RuntimeException('Cantidad a vender es mayor al stock');
        }
    }

    /**
     * Calcula el total de unidades que se quiere vender
     * si en el modulo de ventas se selecciona 1 caja,
     * y el producto se vende en fracciones, este
     * metodo retornará la cantidad unitaria a
     * descontar del inventario
     */
    public function calculateTotalUnits(int $idProduct, int $unidades, bool $fraccion)
    {
        $inventory = new InventoryController();
        return ($fraccion == true) ? $unidades : $inventory->calculateTotalUnitsByBoxes($idProduct, $unidades);
    }
}
