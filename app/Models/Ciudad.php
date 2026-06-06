<?php

namespace App\Models;

use App\Models\Concerns\PreventsDeletionWhenLinkedToInmuebles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Ciudad extends Model
{
    use PreventsDeletionWhenLinkedToInmuebles;
    protected $table = 'ciudades';

    protected $fillable = [
        'id',
        'nombre',
        'has_office',
        'telefono',
        'direccion',
        'matricula',
        'tiene_multiples_sedes',
        'visible_buscador',
        'sedes',
        'whatsapp',
        'whatsapp_arriendo',
        'whatsapp_venta',
        'imagen',
    ];

    protected function casts(): array
    {
        return [
            'has_office' => 'boolean',
            'tiene_multiples_sedes' => 'boolean',
            'visible_buscador' => 'boolean',
            'sedes' => 'array', // 👈 Crucial para que Filament guarde el JSON del Repeater
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (self $ciudad): void {
            $ciudad->sedes = $ciudad->normalizeSedesForStorage();
        });
    }

    /**
     * @return array<int, array<string, mixed>>|null
     */
    public function normalizeSedesForStorage(): ?array
    {
        if (! $this->tiene_multiples_sedes) {
            return null;
        }

        if (blank($this->sedes) || ! is_array($this->sedes)) {
            return null;
        }

        $sedes = collect($this->sedes)
            ->filter(fn (mixed $sede): bool => is_array($sede) && filled($sede['nombre_sede'] ?? null))
            ->map(function (array $sede): array {
                $dependencias = collect($sede['dependencias'] ?? [])
                    ->filter(fn (mixed $dep): bool => is_array($dep) && filled($dep['nombre_dependencia'] ?? null))
                    ->values()
                    ->all();

                return [
                    'nombre_sede' => (string) $sede['nombre_sede'],
                    'telefono' => (string) ($sede['telefono'] ?? ''),
                    'direccion' => (string) ($sede['direccion'] ?? ''),
                    'matricula' => (string) ($sede['matricula'] ?? ''),
                    'email' => (string) ($sede['email'] ?? ''),
                    'dependencias' => $dependencias,
                ];
            })
            ->values()
            ->all();

        return $sedes === [] ? null : $sedes;
    }

    public function scopeVisibleInBuscador($query)
    {
        return $query->where('visible_buscador', true);
    }

    public static function idsVisiblesEnBuscador(): array
    {
        return static::query()
            ->visibleInBuscador()
            ->pluck('id')
            ->map(fn ($id) => (int) $id)
            ->all();
    }

    public static function esVisibleEnBuscador(int|string|null $ciudadId): bool
    {
        if (blank($ciudadId)) {
            return false;
        }

        return static::query()
            ->whereKey((int) $ciudadId)
            ->visibleInBuscador()
            ->exists();
    }

    /**
     * Ciudades con oficina física, formateadas para el footer (tooltips).
     *
     * @return Collection<int, array<string, mixed>>
     */
    public static function footerOffices(): Collection
    {
        return static::query()
            ->where('has_office', true)
            ->get()
            ->sortByDesc(fn (self $ciudad): int => mb_strlen($ciudad->nombre))
            ->values()
            ->map(fn (self $ciudad) => $ciudad->toFooterOfficePayload());
    }

    /**
     * @return array<string, mixed>
     */
    public function toFooterOfficePayload(): array
    {
        $payload = [
            'city' => $this->nombre,
        ];

        if ($this->tiene_multiples_sedes && filled($this->sedes)) {
            $payload['sedes'] = collect($this->sedes)
                ->filter(fn (array $sede): bool => filled($sede['nombre_sede'] ?? null))
                ->map(fn (array $sede): array => [
                    'label' => (string) $sede['nombre_sede'],
                    'address' => (string) ($sede['direccion'] ?? ''),
                    'phones' => self::parsePhones($sede['telefono'] ?? null),
                    'email' => (string) ($sede['email'] ?? ''),
                    'license' => (string) ($sede['matricula'] ?? ''),
                    'dependencias' => collect($sede['dependencias'] ?? [])
                        ->filter(fn (array $dep): bool => filled($dep['nombre_dependencia'] ?? null))
                        ->map(fn (array $dep): array => [
                            'nombre' => (string) $dep['nombre_dependencia'],
                            'contacto' => (string) ($dep['contacto_nombre'] ?? ''),
                            'telefono' => (string) ($dep['telefono_contacto'] ?? ''),
                            'email' => (string) ($dep['email_contacto'] ?? ''),
                        ])
                        ->values()
                        ->all(),
                ])
                ->values()
                ->all();
        } else {
            $payload['address'] = (string) ($this->direccion ?? '');
            $payload['phones'] = self::parsePhones($this->telefono);
            $payload['license'] = (string) ($this->matricula ?? '');
        }

        return $payload;
    }

    public function hasContactSedes(): bool
    {
        return $this->contactSedesForDisplay()->isNotEmpty();
    }

    /**
     * Sedes del JSON para la vista de Centro de Ayuda.
     *
     * @return Collection<int, array<string, mixed>>
     */
    public function contactSedesForDisplay(): Collection
    {
        if (! $this->tiene_multiples_sedes || blank($this->sedes)) {
            return collect();
        }

        return collect($this->sedes)
            ->filter(fn (array $sede): bool => filled($sede['nombre_sede'] ?? null))
            ->map(fn (array $sede): array => [
                'nombre' => (string) $sede['nombre_sede'],
                'direccion' => (string) ($sede['direccion'] ?? ''),
                'telefono' => (string) ($sede['telefono'] ?? ''),
                'email' => (string) ($sede['email'] ?? ''),
                'matricula' => (string) ($sede['matricula'] ?? ''),
                'dependencias' => collect($sede['dependencias'] ?? [])
                    ->filter(fn (array $dep): bool => filled($dep['nombre_dependencia'] ?? null))
                    ->map(fn (array $dep): array => [
                        'nombre' => (string) $dep['nombre_dependencia'],
                        'contacto' => (string) ($dep['contacto_nombre'] ?? ''),
                        'telefono' => (string) ($dep['telefono_contacto'] ?? ''),
                        'email' => (string) ($dep['email_contacto'] ?? ''),
                    ])
                    ->values()
                    ->all(),
            ])
            ->filter(fn (array $sede): bool => ! empty($sede['dependencias']))
            ->values();
    }

    /**
     * @return list<string>
     */
    public static function parsePhones(?string $phones): array
    {
        if (blank($phones)) {
            return [];
        }

        $parts = preg_split('/[\s,·|\/;]+/', trim($phones)) ?: [];

        return array_values(array_filter(array_map('trim', $parts)));
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
