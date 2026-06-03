<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    protected $table = 'ciudades';

    protected $fillable = [
        'id',
        'nombre',
        'has_office',
        'whatsapp',
        'whatsapp_arriendo',
        'whatsapp_venta',
        'imagen',
    ];

    protected function casts(): array
    {
        return [
            'has_office' => 'boolean',
        ];
    }

    public function inmueble()
    {
        return $this->hasMany(Inmueble::class, 'ciudad');
    }

    public function contacts()
    {
        return $this->hasMany(OfficeContact::class, 'ciudad_id')->orderBy('sort_order');
    }
}
