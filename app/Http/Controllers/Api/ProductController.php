<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ProductClass;
use App\Models\Presentation;
use App\Models\Laboratory;
use App\Models\ProductSubclass;
use App\Models\ActiveSubstance;
use App\Models\PharmaAction;
use App\Models\Product;

class ProductController extends Controller
{
    public function getProductsSearch(Request $request)
    {
        $search = $request->search;
        return Product::join('presentations', 'presentations.id','products.presentation_id')
        ->join('product_prices','product_prices.id','products.id')
        ->join('laboratories','laboratories.id','products.laboratory_id')
        ->join('inventories','inventories.product_id','products.id')
        ->join('lots','inventories.lot_id','lots.id')
        ->select(
            'products.codigo',
            'products.descripcion',
            'products.activo',
            'presentations.presentacion',
            'product_prices.precio_unidad',
            'product_prices.precio_blister',
            'product_prices.precio_caja',
            'laboratories.nombre_laboratorio',
            'inventories.stock',
            'lots.numero_lote'
            )
        ->where('products.descripcion','like', '%'. $search .'%')
        ->where('products.deleted',0)
        ->where('presentations.deleted',0)
        ->where('product_prices.deleted',0)
        ->get();
    }

    public function getInitialData()
    {
        return [
            'clases' => ProductClass::where('deleted', 0)->select('id','codigo','descripcion')->get(),
            'laboratorios' => Laboratory::where('deleted', 0)->select('id','codigo','nombre_laboratorio')->get(),
            'presentaciones' => Presentation::where('deleted', 0)->select('id','presentacion')->get(),
            'principios' => ActiveSubstance::where('deleted', 0)->select('id','nombre')->get(),
            'acciones' => PharmaAction::where('deleted', 0)->select('id','nombre')->get()
        ];
    }

    public function getSubclassesByIdClass(Request $request)
    {
        $id = $request->idClass;
        return [
            'items' => ProductSubclass::where('product_class_id',$id)->select('id','codigo','descripcion')->get()
        ];
    }

    public function getAllProducts(Request $request)
    {
        return Product::join('presentations', 'presentations.id','products.presentation_id')
        ->join('product_prices','product_prices.id','products.id')
        ->join('laboratories','laboratories.id','products.laboratory_id')
        // ->join('inventories','inventories.product_id','products.id')
        // ->join('lots','inventories.lot_id','lots.id')
        ->join('product_subclasses','product_subclasses.id','products.product_subclass_id')
        ->join('product_classes','product_subclasses.product_class_id','product_classes.id')
        ->select(
            'products.id',
            'products.codigo',
            'products.descripcion',
            'products.activo',
            'product_classes.descripcion AS clase',
            'product_subclasses.descripcion AS subclase',
            'presentations.presentacion',
            'product_prices.precio_unidad',
            'product_prices.precio_blister',
            'product_prices.precio_caja',
            'laboratories.codigo AS codigo_laboratorio',
            'laboratories.nombre_laboratorio'
            )
        ->where('products.deleted',0)
        ->where('presentations.deleted',0)
        ->where('product_prices.deleted',0)
        ->get();
    }
}
