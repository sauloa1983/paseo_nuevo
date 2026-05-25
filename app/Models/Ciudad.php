<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';

    protected $fillable = ['id', 'nombre', 'whatsapp', 'imagen'];

    public function inmueble()
    {
        return $this->hasMany(Inmueble::class, 'ciudad');
    }
}
