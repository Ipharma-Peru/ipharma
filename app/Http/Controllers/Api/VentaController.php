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
        return [
            'marca' => $this->getProductByType($request->codigo, 'MARCA'),
            'generico' => $this->getProductByType($request->codigo, 'GENERICO')
        ];
    }

    public function getProductByType(string $codigo, string $tipoProducto)
    {
        $product = Product::where('codigo', $codigo)->first();
        $principiosId = $product->activeSubstances->pluck('id');
        return Product::join('active_substance_product','products.id','active_substance_product.product_id')
        ->join('product_prices','product_prices.id','products.id')
        ->join('laboratories','laboratories.id','products.laboratory_id')
        ->join('inventories','inventories.product_id','products.id')
        ->join('presentations','presentations.id','products.presentation_id')
        ->join('product_subclasses','products.product_subclass_id','product_subclasses.id')
        ->select(
            'products.id',
            'products.codigo',
            'products.descripcion as product_description',
            'products.activo',
            'presentations.presentacion',
            'product_prices.precio_unidad',
            'product_prices.precio_blister',
            'product_prices.precio_caja',
            'laboratories.nombre_laboratorio',
            DB::raw('SUM(inventories.stock) as stock'),
            'product_subclasses.descripcion'
        )
        ->where('product_subclasses.descripcion', $tipoProducto)
        ->whereIn('active_substance_product.active_substance_id', $principiosId)
        ->groupBy(
            'products.id',
            'products.codigo',
            'products.descripcion',
            'products.activo',
            'presentations.presentacion',
            'product_prices.precio_unidad',
            'product_prices.precio_blister',
            'product_prices.precio_caja',
            'laboratories.nombre_laboratorio',
            'product_subclasses.descripcion'
            )
        ->get();
    }
}
