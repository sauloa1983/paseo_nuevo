<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Barrio extends Model
{

    public $incrementing = true; // Si el código es autoincremental

    public $timestamps = false; // Si no tienes campos de timestamps

    protected $table = 'barrios';
    protected $primaryKey = 'codigo_barrio';
    protected $keyType = 'int'; // Si el código es un entero
    protected $fillable = ['nombre', 'ciudad_fk','fecha_modif']; // Ajusta según tus campos


    const UPDATED_AT = 'fecha_modif';

    protected $casts = [
        'fecha_modif' => 'date',
    ];

    public function inmueble()
    {
        return $this->hasMany(Inmueble::class, 'barrio_fk');
    }

    public function municipio()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad_fk');
    }
}
