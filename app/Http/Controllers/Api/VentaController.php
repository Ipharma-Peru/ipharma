<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{

    public function getProductsByCode(Request $request)
    {
        $product = Product::where('codigo', $request->codigo)->first();
        $principiosId = $product->activeSubstances->pluck('id');

        if (count($principiosId) === 0) {
            $categoriaId = $product->productSubclass->id;
            return [
                'marca' => $this->getProductByCategory($categoriaId)
            ];
        } else {
            return [
                'marca' => $this->getProductByActive($principiosId, 'MARCA'),
                'generico' => $this->getProductByActive($principiosId, 'GENERICO')
            ];
        }

    }

    protected function getProductByActive($principiosId, string $tipoProducto)
    {
        return Product::join('active_substance_product','products.id','active_substance_product.product_id')
        ->join('product_prices','product_prices.id','products.id')
        ->join('laboratories','laboratories.id','products.laboratory_id')
        ->join('inventories','inventories.product_id','products.id')
        ->join('presentations','presentations.id','products.presentation_id')
        ->join('product_subclasses','products.product_subclass_id','product_subclasses.id')
        ->join('lots','inventories.lot_id','lots.id')
        ->select(
            'products.id',
            'products.codigo',
            'products.descripcion',
            'products.activo',
            'products.fraccionable',
            'presentations.presentacion',
            'product_prices.precio_unidad',
            'product_prices.precio_blister',
            'product_prices.precio_caja',
            'laboratories.nombre_laboratorio',
            'lots.numero_lote',
            'lots.fecha_vencimiento'
            // DB::raw('SUM(inventories.stock) as stock'),
        )
        ->where('product_subclasses.descripcion', $tipoProducto)
        ->whereIn('active_substance_product.active_substance_id', $principiosId)
        // ->groupBy(
        //     'products.id',
        //     'products.codigo',
        //     'products.descripcion',
        //     'products.activo',
        //     'presentations.presentacion',
        //     'product_prices.precio_unidad',
        //     'product_prices.precio_blister',
        //     'product_prices.precio_caja',
        //     'laboratories.nombre_laboratorio',
        //     )
        ->get();
    }

    protected function getProductByCategory(int $subClassId)
    {
        return Product::join('product_prices','product_prices.id','products.id')
        ->join('laboratories','laboratories.id','products.laboratory_id')
        ->join('inventories','inventories.product_id','products.id')
        ->join('presentations','presentations.id','products.presentation_id')
        ->join('product_subclasses','products.product_subclass_id','product_subclasses.id')
        ->join('lots','inventories.lot_id','lots.id')
        ->select(
            'products.id',
            'products.codigo',
            'products.descripcion',
            'products.activo',
            'products.fraccionable',
            'presentations.presentacion',
            'product_prices.precio_unidad',
            'product_prices.precio_blister',
            'product_prices.precio_caja',
            'laboratories.nombre_laboratorio',
            'lots.numero_lote',
            'lots.fecha_vencimiento'
            // DB::raw('SUM(inventories.stock) as stock'),
        )
        ->where('products.product_subclass_id', $subClassId)
        // ->groupBy(
        //     'products.id',
        //     'products.codigo',
        //     'products.descripcion',
        //     'products.activo',
        //     'presentations.presentacion',
        //     'product_prices.precio_unidad',
        //     'product_prices.precio_blister',
        //     'product_prices.precio_caja',
        //     'laboratories.nombre_laboratorio',
        //     )
        ->get();
    }
}
