<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    protected $table = 'cargos';

    protected $fillable = [
        'nombre',
    ];

    public function usuarios()
    {
        return $this->belongsTo(Usuario::class, 'cargo');
    }
}
