<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function documentType()
    {
        return $this->belongsTo('App\Models\DocumentType');
    }

    public function naturalPerson()
    {
        return $this->hasOne('App\Models\NaturalPerson');
    }

    public function legalPerson()
    {
        return $this->hasOne('App\Models\LegalPerson');
    }

    public function provider()
    {
        return $this->hasOne('App\Models\Provider');
    }
}
