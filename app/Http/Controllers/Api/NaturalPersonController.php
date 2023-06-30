<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\NaturalPerson;

class NaturalPersonController extends Controller
{
    public function store(array $data)
    {
        $legalPerson = new NaturalPerson();
        $legalPerson->numero_documento = $data['numero_documento'];
        $legalPerson->nombres = strtoupper($data['nombres']);
        $legalPerson->apellido_paterno = strtoupper($data['apellido_paterno']);
        $legalPerson->apellido_materno = strtoupper($data['apellido_materno']);
        $legalPerson->fecha_nacimiento = $data['fecha_nacimiento'];
        $legalPerson->person_id = $data['person_id'];
        $legalPerson->save();
    }
}
