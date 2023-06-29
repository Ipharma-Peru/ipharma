<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LegalPerson;

class LegalPersonController extends Controller
{
    public function store(array $data)
    {
        $legalPerson = new LegalPerson();
        $legalPerson->ruc = $data['ruc'];
        $legalPerson->razon_social = $data['razon_social'];
        $legalPerson->nombre_comercial = $data['nombre_comercial'];
        $legalPerson->person_id = $data['person_id'];
        $legalPerson->save();
    }
}
