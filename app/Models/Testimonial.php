<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;


class Testimonial extends Model
{

    protected $fillable = [
        'nombre',
        'descripcion',
        'calificacion',
        'mensaje',
        'activo',
    ];

}
