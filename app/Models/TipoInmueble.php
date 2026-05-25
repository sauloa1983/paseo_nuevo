<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TipoInmueble extends Model
{

    public $timestamps = false;

    protected $table = 'tipo_inmueble';
    protected $fillable = ['id', 'tipo']; // ajusta

    const UPDATED_AT = 'fecha_modif';

    public function inmueble()
    {
        return $this->hasMany(Inmueble::class, 'tipo_fk');
    }
}
