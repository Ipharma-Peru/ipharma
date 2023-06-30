<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LegalPerson;

class LegalPersonController extends Controller
{
    public function store(array $data)
    {
        $nombreComercial = isset($data['nombre_comercial']) ? strtoupper($data['nombre_comercial']) : null;

        $legalPerson = new LegalPerson();
        $legalPerson->ruc = $data['ruc'];
        $legalPerson->razon_social = $data['razon_social'];
        $legalPerson->nombre_comercial = $nombreComercial;
        $legalPerson->person_id = $data['person_id'];
        $legalPerson->save();
    }
}
