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

    /**
     * Obtiene el detalle del documento para su envio
     * a Sunat
     */
    public function getDocumentDetail(int $saleId)
    {
        return SaleDetail::join('sales','sale_details.sale_id','sales.id')
                ->join('products','products.id','sale_details.product_id')
                ->join('product_prices','product_prices.id','products.id')
                ->join('price_types','price_types.id','products.price_type_id')
                ->join('tax_rates','tax_rates.id','sales.tax_rate_id')
                ->join('units','units.id','products.unit_id')
                ->join('affectation_types','affectation_types.id','products.affectation_type_id')
                ->select(
                    'sale_details.id',
                    'products.codigo as codigo_producto',
                    'products.descripcion',
                    'sale_details.cantidad',
                    'sale_details.cantidad_is_fraccion as fraccion',
                    'product_prices.precio_unidad',
                    'product_prices.precio_caja',
                    'price_types.codigo as tipo_precio',
                    'tax_rates.valor_igv',
                    'units.codigo as unidad_medida',
                    'affectation_types.codigo',
                    'affectation_types.codigo_afectacion',
                    'affectation_types.tipo_afectacion',
                    'affectation_types.nombre_afectacion'
                )
                ->where('sales.id',$saleId)
                ->orderBy('sale_details.id','ASC')
                ->get();
    }
}
