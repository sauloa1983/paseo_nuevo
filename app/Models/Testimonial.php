<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Testimonial extends Model
{

    protected $fillable = [
        'nombre',
        'descripcion',
        'mensaje',
        'activo',
    ];

}
