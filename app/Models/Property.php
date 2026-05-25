<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    protected $table = 'inmuebles';
    public $timestamps = false;

    protected $fillable = [
        'id','codigo', 'tipo_fk', 'venta', 'direccion', 'ciudad',
        'valor_venta', 'propietario', 'area_construida',
        'no_alcobas', 'no_banos', 'fecha_captacion'
    ];

    public function tipo_inmueble()
    {
        return $this->belongsTo(TipoInmueble::class, 'tipo_fk');
    }

    public function barrio()
    {
        return $this->belongsTo(Barrio::class, 'barrio_fk', 'codigo_barrio');
    }

    public function ciudad()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad');
    }

    /** Alias compatible con Inmueble y vistas compartidas */
    public function ciudadRelacion()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad');
    }

    public function fotos()
    {
        return $this->hasMany(
            'App\Models\FotoInmueble',
            'inmueble_fk',   // campo de fotos_inmuebles
            'id'         // campo de inmuebles
        )->orderBy('posicion') ;// si existe, si no, quítalo
        //->limit(3); //Limitar
    }

    // Relación con Asesor
    public function asesorData()
    {
        return $this->belongsTo(Usuario::class, 'asesor', 'cedula');
    }

    /*protected $primaryKey = 'id';*/
}
