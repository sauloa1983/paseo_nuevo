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
    protected $primaryKey = 'cedula';  // ← ESTA LÍNEA
    public $incrementing = false;      // ← + esta
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

    public function getFilamentName(): string  // ← ESTO RESUELVE
    {
        return trim("{$this->nombres} {$this->apellidos}");
    }

    public function inmuebles()
    {
        return $this->hasMany(Property::class, 'asesor', 'cedula');
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
