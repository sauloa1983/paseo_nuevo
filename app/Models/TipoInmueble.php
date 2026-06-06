<?php
namespace App\Models;

use App\Models\Concerns\PreventsDeletionWhenLinkedToInmuebles;
use Illuminate\Database\Eloquent\Model;

class TipoInmueble extends Model
{
    use PreventsDeletionWhenLinkedToInmuebles;

    public $timestamps = false;

    protected $table = 'tipo_inmueble';
    protected $fillable = ['id', 'tipo']; // ajusta

    const UPDATED_AT = 'fecha_modif';

    public function inmueble()
    {
        return $this->hasMany(Inmueble::class, 'tipo_fk');
    }
}
