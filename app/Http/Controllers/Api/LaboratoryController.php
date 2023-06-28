<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Laboratory;
use Illuminate\Support\Facades\DB;

class LaboratoryController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $presentation = new Laboratory();
            $presentation->codigo = strtoupper($request->codigo);
            $presentation->nombre_laboratorio = strtoupper($request->nombre_laboratorio);
            $presentation->deleted = false;
            $presentation->save();

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
}
