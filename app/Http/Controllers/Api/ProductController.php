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
use App\Models\PresentationDetail;
use App\Models\Product;
use App\Models\ProductPrice;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function getProductsSearch(Request $request)
    {
        $search = $request->search;
        return Product::join('presentations', 'presentations.id','products.presentation_id')
        ->join('product_prices','product_prices.id','products.id')
        ->join('laboratories','laboratories.id','products.laboratory_id')
        ->leftjoin('inventories','inventories.product_id','products.id')
        ->join('lots','inventories.lot_id','lots.id')
        ->select(
            'products.id as product_id',
            'products.codigo',
            'products.descripcion',
            'products.activo',
            'products.fraccionable',
            'presentations.presentacion',
            'product_prices.precio_unidad',
            'product_prices.precio_blister',
            'product_prices.precio_caja',
            'laboratories.nombre_laboratorio',
            'inventories.stock',
            'lots.numero_lote',
            'lots.fecha_vencimiento'
            // DB::raw('SUM(inventories.stock) as stock'),
            )
        ->where('products.descripcion','like', '%'. $search .'%')
        ->where('products.deleted',0)
        ->where('presentations.deleted',0)
        ->where('product_prices.deleted',0)
        // ->groupBy(
        //     'products.codigo',
        //     'products.descripcion',
        //     'products.activo',
        //     'presentations.presentacion',
        //     'product_prices.precio_unidad',
        //     'product_prices.precio_blister',
        //     'product_prices.precio_caja',
        //     'laboratories.nombre_laboratorio',
        //     // 'inventories.stock',
        //     // 'numero_lote'
        //     )
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

    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $product = new Product;
            $product->codigo = $this->generateProductCode();
            $product->descripcion = $request->description;
            $product->activo = true;
            $product->fraccionable = $request->fraccionable;
            $product->deleted = false;
            $product->presentation_id = $request->selectedPresentacion;
            $product->laboratory_id = $request->selectedLaboratorio;
            $product->product_subclass_id = $request->selectedSubclase;
            $product->unit_id = 1; // TODO
            $product->affectation_type_id = $request->tipoAfectacion;
            $product->price_type_id = 1; // TODO
            $product->save();

            $productId = $product->id;
            $price = new ProductPrice();
            $price->precio_caja = $request->pvpx;
            $price->precio_blister = $request->pvpBlister;
            $price->precio_unidad = $request->pvpFraccion;
            $price->activo = true;
            $price->deleted = false;
            $price->product_id = $productId;
            $price->save();

            $presentation = new PresentationDetail();
            $presentation->unidades_por_caja = $request->unidadesByCaja;
            $presentation->unidades_por_blister = $request->unidadesByBlister;
            $presentation->deleted = false;
            $presentation->product_id = $productId;
            $presentation->save();

            $idPrincipios = array_column($request->selectedPrincipios,'id');
            $idFarmacologicas = array_column($request->selectedactionPharma,'id');
            $product->activeSubstances()->attach($idPrincipios);
            $product->pharmaActions()->attach($idFarmacologicas);

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

    public function generateProductCode()
    {
        $code = Product::where('deleted', 0)->select('codigo')->orderBy('codigo', 'DESC')->first();
        $code = (int)$code->codigo + 1;
        $code = str_pad($code, 8, '0', STR_PAD_LEFT);
        return $code;
    }

    public function getProductsForSale(Request $request)
    {
        return Product::select('id','codigo','descripcion')
            ->where('descripcion','like', '%'. $request->search .'%')
            ->orderBy('descripcion','ASC')
            ->get();
    }
}
