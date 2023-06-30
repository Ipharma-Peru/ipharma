<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Client;
use App\Http\Controllers\Api\NaturalPersonController;

class ClientController extends Controller
{
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $person = new PersonController;
            $idPerson = $person->store(1);
            
            $provider = new Client();
            $provider->person_id = $idPerson;
            $provider->save();

            $data = [
                'numero_documento' => $request->numero_documento,
                'nombres' => $request->nombres,
                'apellido_paterno' => $request->apellido_paterno,
                'apellido_materno' => $request->apellido_materno,
                'fecha_nacimiento' => $request->fecha_nacimiento,
                'person_id' => $idPerson
            ];

            $naturalPerson = new NaturalPersonController();
            $naturalPerson->store($data);

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
