<?php

namespace App\Models;

use App\Models\Concerns\HasEtiquetasComerciales;
use App\Models\Concerns\HasVideoPrincipal;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Inmueble extends Model
{
    use HasEtiquetasComerciales;
    use HasVideoPrincipal;
    protected $table = 'inmuebles';

    protected $fillable = ['id', 'codigo','tipo_fk','arriendo','venta','destacado','contract_end_date','badge_status','estado','iva','fecha_captacion','valor_arriendo','valor_venta','direccion','ciudad','barrio_fk','unidad','conjunto_cerrado','lobby','ascensor','piscina','juegos','gimnasio','sauna','turco','salon_social','estrato','administracion','area_construida','sala_comedor','no_alcobas','alcoba_servicio','no_closets','hall','estudio','balcon','terraza','salon_n','no_salon','bodega','no_bodega','oficina','no_oficina','tipo_cocina','ubicacion','acceso','no_banos','patio','mirador','cancha','bbq','zona_ropas','bano_servicio','garajes','parq_moto','parq_comunal','vigilancia','observaciones','propietario','tel_casa_prop','tel_of_prop','cel_prop','direccion_prop','barrio_prop','ciudad_prop','asesor','edificio','calentador','aire_acondicionado','foto1','foto2','foto3','foto4','foto5','foto6','foto7','foto8','foto9','foto10','foto11','foto12','video','visitas','latitud','longitud','fecha_modif','tipo_modif','persona_modif','link_fotos_360'];

    protected $casts = [
        'contract_end_date' => 'date',
    ];

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

    /**
     * Excluye inmuebles con datos críticos incompletos / "N/A":
     * deben tener tipo (para el título) y al menos un precio válido (> 0).
     */
    public function scopeConDatosCompletos($query)
    {
        return $query
            ->whereNotNull('tipo_fk')
            ->where('tipo_fk', '!=', 0)
            ->where(function ($q) {
                $q->where('valor_arriendo', '>', 0)
                  ->orWhere('valor_venta', '>', 0);
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
