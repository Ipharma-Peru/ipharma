<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Person;

class PersonController extends Controller
{
    public function store(int $tipoDocument)
    {

        $person = new Person();
        $person->document_type_id = $tipoDocument;
        $person->save();

        return $person->id;
    }
}
