<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Provider;
use App\Models\LegalPerson;
use App\Http\Controllers\Api\PersonController;
use App\Http\Controllers\Api\LegalPersonController;

class ProviderController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $person = new PersonController;
            $idPerson = $person->store(3);
            
            $provider = new Provider;
            $provider->person_id = $idPerson;
            $provider->save();

            $data = [
                'ruc' => $request->ruc,
                'razon_social' => $request->razon_social,
                'nombre_comercial' => $request->nombre_comercial,
                'person_id' => $idPerson
            ];

            $legalPerson = new LegalPersonController;
            $legalPerson->store($data);

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

    public function searchProvider(Request $request)
    {
        $search = $request->search;
        return LegalPerson::join('people','people.id','legal_people.person_id')
            ->join('providers','providers.person_id','people.id')
            ->select(
                'legal_people.ruc',
                'legal_people.razon_social',
                'providers.id'
                )
            ->where('legal_people.razon_social','like', '%'. $search .'%')
            ->orderBy('legal_people.razon_social', 'ASC')
            ->get();
    }
}
