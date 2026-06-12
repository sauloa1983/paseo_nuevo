<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;

//class Usuario extends Model
class Usuario extends Authenticatable implements FilamentUser, HasName
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';
    protected $primaryKey = 'id';  // ← ESTA LÍNEA
    public $incrementing = true;      // ← + esta
    protected $keyType = 'string';     // ← + esta

    protected $fillable = ['cedula', 'nombres', 'apellidos', 'direccion', 'telefonos', 'email', 'foto', 'usuario', 'cargo', 'vigente', 'password'];
    const UPDATED_AT = 'fecha_modif';

    protected $hidden = [
        'password',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return true;  // Admin completo
    }

    public function puedeGestionarUsuarios(): bool
    {
        return in_array((int) $this->cargo, [1, 2], true);
    }

    public function getFilamentName(): string  // ← ESTO RESUELVE
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }

    public function inmuebles()
    {
        return $this->hasMany(Inmueble::class, 'asesor', 'id');
    }

    public function cantidadInmueblesAsignados(): int
    {
        return $this->inmuebles()->count();
    }

    public function motivoNoEliminable(): ?string
    {
        $cantidad = $this->cantidadInmueblesAsignados();

        if ($cantidad === 0) {
            return null;
        }

        return "Este usuario tiene {$cantidad} inmueble(s) asignados como asesor. Reasigne los inmuebles antes de eliminarlo.";
    }

    public function cargos()
    {
        return $this->belongsTo(Cargo::class, 'cargo');
    }

    public function getNombresCompletosAttribute()
    {
        return $this->nombres . ' ' . $this->apellidos;
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (blank($this->foto)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($this->foto));
        $path = ltrim(str_replace(['storage/', 'public/'], '', $path), '/');

        return asset('storage/' . $path);
    }
}
