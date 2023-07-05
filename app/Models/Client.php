<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function person()
    {
        return $this->belongsTo('App\Models\Person');
    }

    public function sales()
    {
        return $this->hasMany('App\Models\Sale');
    }
}
