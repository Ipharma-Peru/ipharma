<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function getCompanyData()
    {
        return Company::join('people','people.id','companies.person_id')
                ->join('document_types','document_types.id','people.document_type_id')
                ->join('addresses','addresses.person_id','people.id')
                ->join('districts','addresses.district_id','districts.id')
                ->join('provinces','provinces.id','districts.province_id')
                ->join('departaments','departaments.id','provinces.departament_id')
                ->join('legal_people','people.id','legal_people.person_id')
                ->select(
                    'companies.id',
                    'document_types.codigo as tipo_documento',
                    'legal_people.ruc',
                    'legal_people.razon_social',
                    'legal_people.nombre_comercial',
                    'addresses.direccion',
                    'districts.distrito',
                    'districts.ubigeo',
                    'provinces.provincia',
                    'departaments.departamento',
                    'companies.usuario_secundario',
                    'companies.clave_secundaria'
                )
                ->first();
    }
}
