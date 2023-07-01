<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActiveSubstance;
use Illuminate\Support\Facades\DB;

class ActiveSubstanceController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $presentation = new ActiveSubstance();
            $presentation->nombre = strtoupper($request->nombre);
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
