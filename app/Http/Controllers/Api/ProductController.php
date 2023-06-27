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

class ProductController extends Controller
{
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
}
