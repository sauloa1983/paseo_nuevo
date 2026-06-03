<?php

namespace App\Models;

use App\Models\Concerns\HasEtiquetasComerciales;
use App\Models\Concerns\HasVideoPrincipal;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasEtiquetasComerciales;
    use HasVideoPrincipal;
    protected $table = 'inmuebles';
    public $timestamps = false;

    protected $fillable = [
        'id','codigo', 'tipo_fk', 'venta', 'direccion', 'ciudad',
        'valor_venta', 'propietario', 'area_construida',
        'no_alcobas', 'no_banos', 'fecha_captacion',
        'contract_end_date', 'badge_status',
    ];

    protected $casts = [
        'contract_end_date' => 'date',
    ];

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
