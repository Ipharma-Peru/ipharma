<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lot;

class LotController extends Controller
{
    public function store(array $data)
    {
        $lote = new Lot();
        $lote->numero_lote = $data['numero_lote'];
        $lote->fecha_vencimiento = $data['fecha_vencimiento'];
        $lote->fecha_produccion = $data['fecha_produccion'];
        $lote->save();

        return $lote->id;
    }
}
