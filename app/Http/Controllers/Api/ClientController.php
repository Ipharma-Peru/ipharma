<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\NaturalPersonController;
use App\Models\Client;
use App\Models\NaturalPerson;

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

    public function search(Request $request)
    {
        try {
            $search = $request->document;
            return NaturalPerson::join('people', 'people.id','natural_people.person_id')
            ->join('clients','clients.person_id','people.id')
            ->select(
                'clients.id',
                'natural_people.numero_documento',
                DB::raw("CONCAT(
                        natural_people.nombres, ' ',
                        natural_people.apellido_paterno, ' ',
                        natural_people.apellido_materno) AS nombre")
                )
            ->where('natural_people.numero_documento','like', '%'. $search .'%')
            ->get();

        } catch (\Exception $e) {

            $errorMessage = $e->getMessage();
            $errorCode = $e->getCode();

            return [
                'status' => false,
                'message' => $errorMessage,
                'code' => $errorCode
            ];
        }
    }

    public function searchById(mixed $clientId)
    {
        return NaturalPerson::join('people', 'people.id','natural_people.person_id')
        ->join('clients','clients.person_id','people.id')
        ->leftJoin('addresses','addresses.person_id', 'people.id')
        ->select(
            'clients.id',
            'natural_people.numero_documento',
            DB::raw("CONCAT(
                    natural_people.nombres, ' ',
                    natural_people.apellido_paterno, ' ',
                    natural_people.apellido_materno) AS nombre"),
            'addresses.direccion'
            )
        ->where('clients.id', $clientId)
        ->first();
    }
}
