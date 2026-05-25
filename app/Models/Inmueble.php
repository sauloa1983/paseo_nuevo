<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inmueble extends Model
{
    protected $table = 'inmuebles';

    protected $fillable = ['id', 'codigo','tipo_fk','arriendo','venta','destacado','estado','iva','fecha_captacion','valor_arriendo','valor_venta','direccion','ciudad','barrio_fk','unidad','conjunto_cerrado','lobby','ascensor','piscina','juegos','gimnasio','sauna','turco','salon_social','estrato','administracion','area_construida','sala_comedor','no_alcobas','alcoba_servicio','no_closets','hall','estudio','balcon','terraza','salon_n','no_salon','bodega','no_bodega','oficina','no_oficina','tipo_cocina','ubicacion','acceso','no_banos','patio','mirador','cancha','bbq','zona_ropas','bano_servicio','garajes','parq_moto','parq_comunal','vigilancia','observaciones','propietario','tel_casa_prop','tel_of_prop','cel_prop','direccion_prop','barrio_prop','ciudad_prop','asesor','edificio','calentador','aire_acondicionado','foto1','foto2','foto3','foto4','foto5','foto6','foto7','foto8','foto9','foto10','foto11','foto12','video','visitas','latitud','longitud','fecha_modif','tipo_modif','persona_modif','link_fotos_360'];

    /*const CREATED_AT = 'fecha_crea';*/
    const CREATED_AT = 'fecha_captacion';
    const UPDATED_AT = 'fecha_modif';

    protected static function booted()
    {
        static::creating(function ($inmueble) {
            // Bloqueamos la tabla brevemente para leer el máximo sin que nadie más se meta
            $ultimoCodigo = DB::table('inmuebles')->max('codigo') ?? 0;
            $inmueble->codigo = $ultimoCodigo + 1;
        });
    }

    public function fotoInmueble()
    {
        return $this->hasMany(FotoInmueble::class, 'inmueble_fk', 'id')
            ->orderBy('posicion', 'asc');
    }

    public function tipo_inmueble()
    {
        return $this->belongsTo(TipoInmueble::class, 'tipo_fk');
    }

    public function ciudadRelacion()
    {
        return $this->belongsTo(Ciudad::class, 'ciudad');
    }

    public function barrio()
    {
        return $this->belongsTo(Barrio::class, 'barrio_fk', 'codigo_barrio');
    }

    public function userAsesor()
    {
        return $this->belongsTo(Usuario::class, 'cedula');
    }

}
