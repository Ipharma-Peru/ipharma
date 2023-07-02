<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

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

    /**
     * Evalua si la cantidad que se quiere descontar del stock
     * es mayor al stock disponible total del producto
     * true -> La cantidad es menor o igual al stock, CORRECTO
     * false -> La cantidad es mayor al stock, ILEGAL
     */
    public function stockIsSufficient(int $productId, int $cantidad)
    {
        return $this->getTotalStock($productId) >= $cantidad;
    }

    /**
     * Devuelve el stock total de un producto
     * recibe como parametro el ID del producto
     */
    public function getTotalStock(int $productId)
    {
        return Product::join('inventories','inventories.product_id','products.id')
                ->join('lots','lots.id','inventories.lot_id')
                ->select(
                    DB::raw('SUM(inventories.stock) as stock')
                )
                ->where('products.id', $productId)
                ->groupBy('products.id')
                ->pluck('stock')
                ->first();
    }

    /**
     * Metodo que permite reducir el stock de
     * un producto, ya sea por una venta
     * o movimiento de inventario, la
     * reduccion se realiza iniciando
     * por los lotes proximos a vencer
     */
    public function reduceStock(int $idProduct, int $cantidad)
    {
        $registros = $this->getStockRecordsByProduct($idProduct);

        foreach ($registros as $registro) {

            $inventory = Inventory::find($registro->inventory_id);
            $restante = $cantidad - $registro->stock;

            if ($restante > 0) {
                $inventory->stock = 0;
                $inventory->save();
                $cantidad = $restante;

            } else {
                $inventory->stock -= $cantidad;
                $inventory->save();
                break;
            }
        }
        // throw new RuntimeException('Cantidad mayor a stock')

    }

    /**
     * Retorna todos los registros con stock
     * de un producto
     */

    public function getStockRecordsByProduct(int $idProduct)
    {
        return Product::join('inventories', 'inventories.product_id','products.id')
                ->join('lots', 'lots.id','inventories.lot_id')
                ->select(
                    'lots.numero_lote',
                    'lots.fecha_vencimiento',
                    'inventories.stock',
                    'inventories.id as inventory_id'
                    )
                ->where('inventories.stock','>',0)
                ->where('products.id',$idProduct)
                ->orderBy('lots.fecha_vencimiento','ASC')
                ->get();
    }
}
