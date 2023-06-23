<?php

namespace App\Http\Livewire\Caja;

use App\Models\Product;
use App\Models\Presentation;
use Livewire\Component;
use Illuminate\Support\Facades\DB;

class Venta extends Component
{
    public $articleKey;
    public $articles;
    public $sortBy = 'descripcion';
    public $order = 'asc';
    public $genericos=[], $comercial;
    public $laboratorySearch, $laboryGeneric="", $laboratoryCommercial;
    public function search()
    {
        $this->articles = Product::join('presentations', 'presentations.id', 'products.presentation_id')
            ->join('product_prices', 'product_prices.id', 'products.id')
            ->join('laboratories', 'laboratories.id', 'products.laboratory_id')
            ->join('inventories', 'inventories.product_id', 'products.id')
            ->select(
                'products.codigo',
                'products.descripcion',
                'products.activo',
                'presentations.presentacion',
                'product_prices.precio_unidad',
                'product_prices.precio_blister',
                'product_prices.precio_caja',
                'laboratories.nombre_laboratorio',
                'inventories.stock'
            )
            ->where('products.descripcion', 'like', '%' . $this->articleKey . '%')
            ->where('products.deleted', 0)
            ->where('presentations.deleted', 0)
            ->where('product_prices.deleted', 0)
            ->orderBy($this->sortBy, $this->order)->get();

    }
    public function render()
    {
        $this->search();
            //         $this->genericos= Product::join('presentations', 'presentations.id', 'products.presentation_id')
            // ->join('product_prices', 'product_prices.id', 'products.id')
            // ->join('laboratories', 'laboratories.id', 'products.laboratory_id')
            // ->join('inventories', 'inventories.product_id', 'products.id')
            // ->select(
            //     'products.codigo',
            //     'products.descripcion as name_product',
            //     'products.activo',
            //     'presentations.presentacion',
            //     'product_prices.precio_unidad',
            //     'product_prices.precio_blister',
            //     'product_prices.precio_caja',
            //     'laboratories.nombre_laboratorio',
            //     'inventories.stock'
            // )
            // ->where('products.descripcion', 'like', '%' . $this->articleKey . '%')
            // ->where('products.deleted', 0)
            // ->where('presentations.deleted', 0)
            // ->where('product_prices.deleted', 0)
            // ->orderBy($this->sortBy, $this->order)->get();
        return view('livewire.caja.venta');
        
    }

    public function getProductosPorTipo(string $codigo, string $tipo)
    {
        $product = Product::where('codigo', $codigo)->first();
        $principiosId = $product->activeSubstances->pluck('id');
        return Product::join('active_substance_product', 'products.id', 'active_substance_product.product_id')
            ->join('product_prices', 'product_prices.id', 'products.id')
            ->join('laboratories', 'laboratories.id', 'products.laboratory_id')
            ->join('inventories', 'inventories.product_id', 'products.id')
            ->join('presentations', 'presentations.id', 'products.presentation_id')
            ->join('product_subclasses', 'products.product_subclass_id', 'product_subclasses.id')
            ->select(
                'products.id',
                'products.codigo',
                'products.descripcion as name_product',
                'products.activo',
                'presentations.presentacion',
                'product_prices.precio_unidad',
                'product_prices.precio_blister',
                'product_prices.precio_caja',
                'laboratories.nombre_laboratorio',
                DB::raw('SUM(inventories.stock) as stock'),
                'product_subclasses.descripcion'
            )
            ->where('product_subclasses.descripcion', $tipo)
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

    public function setCodeArticle(string $codigo, string $laboratory)
    {
        $this->laboratorySearch = $laboratory;
        // $this->genericos=null;
        $this->comercial = $this->getProductosPorTipo($codigo, 'MARCA');
        $this->genericos = $this->getProductosPorTipo($codigo, 'GENERICO');
        // dd($this->genericos);
    }
    public function setLaboratory(string $laboratory){
        $this->laboryGeneric= $laboratory;

    }


}