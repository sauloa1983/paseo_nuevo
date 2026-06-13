<?php

namespace App\Models;

use App\Models\Concerns\PreventsDeletionWhenLinkedToInmuebles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

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
        'latitud',
        'longitud',
        'mapa_embed',
        'matricula',
        'email',
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
            'latitud' => 'float',
            'longitud' => 'float',
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
                    'latitud' => filled($sede['latitud'] ?? null) ? (float) $sede['latitud'] : null,
                    'longitud' => filled($sede['longitud'] ?? null) ? (float) $sede['longitud'] : null,
                    'mapa_embed' => (string) ($sede['mapa_embed'] ?? ''),
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

    public function scopeWithOffice($query)
    {
        return $query->where('has_office', true);
    }

    public function scopeForContactPage($query)
    {
        return $query->withOffice();
    }

    public static function normalizeContactLocationKey(?string $nombre): string
    {
        return mb_strtolower(trim(Str::ascii($nombre ?? '')));
    }

    public static function isLocalesYPisos(?string $nombre): bool
    {
        return self::normalizeContactLocationKey($nombre) === self::normalizeContactLocationKey('Locales y pisos');
    }

    /**
     * @return Collection<int, self>
     */
    public static function contactPageCiudades(): Collection
    {
        $ciudades = static::query()
            ->forContactPage()
            ->orderBy('nombre')
            ->get()
            ->reject(fn (self $ciudad): bool => self::isLocalesYPisos($ciudad->nombre));

        $bogota = $ciudades->filter(fn (self $ciudad): bool => self::isBogota($ciudad->nombre));
        $otras = $ciudades->reject(fn (self $ciudad): bool => self::isBogota($ciudad->nombre));

        return $otras->concat($bogota)->values();
    }

    public function contactLocationKey(): string
    {
        return self::normalizeContactLocationKey($this->nombre);
    }

    /**
     * @return list<array<string, mixed>>
     */
    public function normalizedSedesList(): array
    {
        if (! $this->tiene_multiples_sedes || blank($this->sedes) || ! is_array($this->sedes)) {
            return [];
        }

        return array_values(array_filter(
            $this->sedes,
            fn (mixed $sede): bool => is_array($sede) && filled($sede['nombre_sede'] ?? null),
        ));
    }

    public function hasMoreThanTwoContactSedes(): bool
    {
        return count($this->normalizedSedesList()) > 2;
    }

    /**
     * Sedes visibles en la página de contacto.
     * Con más de 2 sedes solo se usa la primera.
     *
     * @return list<array<string, mixed>>
     */
    public function contactSedesRelevantes(): array
    {
        $sedes = $this->normalizedSedesList();

        if ($sedes === []) {
            return [];
        }

        if (count($sedes) > 2) {
            return [$sedes[0]];
        }

        return $sedes;
    }

    /**
     * @return list<array<string, mixed>>
     */
    protected function contactSedesForData(): array
    {
        if (! $this->tiene_multiples_sedes || blank($this->sedes)) {
            return [];
        }

        if ($this->hasMoreThanTwoContactSedes()) {
            return $this->contactSedesRelevantes();
        }

        return $this->normalizedSedesList();
    }

    public function contactDireccion(): string
    {
        if ($this->tiene_multiples_sedes && filled($this->sedes)) {
            if ($this->hasMoreThanTwoContactSedes()) {
                $primera = $this->contactSedesRelevantes()[0] ?? null;

                return $primera ? trim((string) ($primera['direccion'] ?? '')) : '';
            }

            foreach ($this->contactSedesForData() as $sede) {
                $direccion = trim((string) ($sede['direccion'] ?? ''));

                if (filled($direccion)) {
                    return $direccion;
                }
            }
        }

        return trim((string) ($this->direccion ?? ''));
    }

    public function contactMapAddress(): string
    {
        $direccion = $this->contactDireccion();

        if (blank($direccion)) {
            return '';
        }

        $ciudad = trim((string) $this->nombre);

        return filled($ciudad) ? $direccion.', '.$ciudad : $direccion;
    }

    public function contactMapQuery(): string
    {
        $direccion = $this->contactDireccion();

        if (blank($direccion)) {
            return '';
        }

        $direccion = preg_replace('/\bNo\.\s*/iu', '#', $direccion) ?? $direccion;

        $parts = array_filter([
            trim($direccion),
            trim((string) $this->nombre),
            $this->contactMapDepartment(),
            'Colombia',
        ]);

        return implode(', ', $parts);
    }

    protected function contactMapDepartment(): ?string
    {
        if (self::isBogota($this->nombre)) {
            return 'Cundinamarca';
        }

        return 'Santander';
    }

    public function contactMapaEmbed(): ?string
    {
        if ($this->tiene_multiples_sedes && filled($this->sedes)) {
            if ($this->hasMoreThanTwoContactSedes()) {
                $primera = $this->contactSedesRelevantes()[0] ?? null;
                $embed = $primera ? trim((string) ($primera['mapa_embed'] ?? '')) : '';

                return filled($embed) ? $embed : null;
            }

            foreach ($this->contactSedesForData() as $sede) {
                $embed = trim((string) ($sede['mapa_embed'] ?? ''));

                if (filled($embed)) {
                    return $embed;
                }
            }
        }

        $embed = trim((string) ($this->mapa_embed ?? ''));

        return filled($embed) ? $embed : null;
    }

    /**
     * @return array{0: ?float, 1: ?float}
     */
    public function contactMapCoordinates(): array
    {
        if ($this->tiene_multiples_sedes && filled($this->sedes)) {
            if ($this->hasMoreThanTwoContactSedes()) {
                $primera = $this->contactSedesRelevantes()[0] ?? null;

                if ($primera && filled($primera['latitud'] ?? null) && filled($primera['longitud'] ?? null)) {
                    return [(float) $primera['latitud'], (float) $primera['longitud']];
                }

                return [null, null];
            }

            foreach ($this->contactSedesForData() as $sede) {
                $lat = $sede['latitud'] ?? null;
                $lng = $sede['longitud'] ?? null;

                if (filled($lat) && filled($lng)) {
                    return [(float) $lat, (float) $lng];
                }
            }
        }

        if (filled($this->latitud) && filled($this->longitud)) {
            return [(float) $this->latitud, (float) $this->longitud];
        }

        return [null, null];
    }

    public function contactMapEmbedUrl(): string
    {
        $embed = $this->contactMapaEmbed();

        if (filled($embed)) {
            return $embed;
        }

        [$lat, $lng] = $this->contactMapCoordinates();

        if ($lat !== null && $lng !== null) {
            return static::googleMapsEmbedFromCoordinates($lat, $lng);
        }

        return static::googleMapsEmbedUrl($this->contactMapQuery());
    }

    public static function googleMapsEmbedFromCoordinates(float $lat, float $lng): string
    {
        return 'https://maps.google.com/maps?q='.rawurlencode($lat.','.$lng).'&hl=es&z=17&output=embed';
    }

    public static function googleMapsEmbedUrl(string $query): string
    {
        if (blank($query)) {
            return '';
        }

        return 'https://maps.google.com/maps?q='.rawurlencode($query).'&hl=es&z=16&output=embed';
    }

    public function contactTelefono(): string
    {
        if ($this->tiene_multiples_sedes && filled($this->sedes)) {
            if ($this->hasMoreThanTwoContactSedes()) {
                $primera = $this->contactSedesRelevantes()[0] ?? null;

                return $primera ? trim((string) ($primera['telefono'] ?? '')) : '';
            }

            foreach ($this->contactSedesForData() as $sede) {
                $telefono = trim((string) ($sede['telefono'] ?? ''));

                if (filled($telefono)) {
                    return $telefono;
                }
            }
        }

        return trim((string) ($this->telefono ?? ''));
    }

    /**
     * @return list<string>
     */
    public function contactPhones(): array
    {
        return self::parsePhones($this->contactTelefono());
    }

    public static function contactPhoneHref(string $phone): string
    {
        $digits = preg_replace('/\D+/', '', $phone) ?? '';

        if (strlen($digits) === 10) {
            return 'tel:+57'.$digits;
        }

        if (strlen($digits) === 7) {
            return 'tel:+576'.$digits;
        }

        return 'tel:'.$digits;
    }

    /**
     * @param  list<string>  $phones
     */
    public static function formatContactPhonesHtml(array $phones): string
    {
        if ($phones === []) {
            return 'Sin teléfono registrado.';
        }

        return collect($phones)
            ->map(function (string $phone, int $index): string {
                $sep = $index > 0 ? ' | ' : '';

                return $sep.'<a href="'.e(self::contactPhoneHref($phone)).'">'.e($phone).'</a>';
            })
            ->join('');
    }

    /**
     * @param  Collection<int, self>|null  $ciudades
     * @return array<string, array{address: string, map_embed: string, phones: list<string>, phones_html: string}>
     */
    public static function contactOfficeLocations(?Collection $ciudades = null): array
    {
        $ciudades ??= static::contactPageCiudades();

        $locations = [];

        foreach ($ciudades as $ciudad) {
            $phones = $ciudad->contactPhones();

            $locations[$ciudad->contactLocationKey()] = [
                'address' => $ciudad->contactMapAddress(),
                'map_embed' => filled($ciudad->contactMapAddress()) ? $ciudad->contactMapEmbedUrl() : '',
                'phones' => $phones,
                'phones_html' => static::formatContactPhonesHtml($phones),
            ];
        }

        return $locations;
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

    /** @var list<string> Orden fijo de ciudades en el footer (Nuestras oficinas). */
    private const FOOTER_OFFICE_ORDER = [
        'bucaramanga',
        'piedecuesta',
        'floridablanca',
        'giron',
        'bogota',
    ];

    /**
     * Ciudades con oficina física, formateadas para el footer (tooltips).
     *
     * @return Collection<int, array<string, mixed>>
     */
    public static function footerOffices(): Collection
    {
        $ciudades = static::query()
            ->where('has_office', true)
            ->get();

        $orderIndex = array_flip(self::FOOTER_OFFICE_ORDER);

        return $ciudades
            ->sortBy(function (self $ciudad) use ($orderIndex): int {
                $key = mb_strtolower(trim(Str::ascii((string) ($ciudad->nombre ?? ''))));

                return $orderIndex[$key] ?? 999;
            })
            ->values()
            ->map(fn (self $ciudad) => $ciudad->toFooterOfficePayload());
    }

    private static function isBogota(?string $nombre): bool
    {
        if (blank($nombre)) {
            return false;
        }

        $normalized = mb_strtolower(trim(Str::ascii($nombre)));

        return $normalized === 'bogota';
    }

    /**
     * Oficinas de la ciudad para el tooltip del footer.
     * Con varias sedes: cada una con nombre y sus datos debajo.
     * Con una sola oficina: un bloque sin encabezado de sucursal.
     *
     * @return array{
     *     city: string,
     *     offices: list<array{
     *         name: ?string,
     *         address: string,
     *         phones: list<string>,
     *         license: string
     *     }>
     * }
     */
    public function toFooterOfficePayload(): array
    {
        $offices = [];

        if ($this->tiene_multiples_sedes && filled($this->sedes)) {
            foreach ($this->sedes as $sede) {
                if (! is_array($sede) || blank($sede['nombre_sede'] ?? null)) {
                    continue;
                }

                $offices[] = [
                    'name' => (string) $sede['nombre_sede'],
                    'address' => trim((string) ($sede['direccion'] ?? '')),
                    'phones' => self::parsePhones($sede['telefono'] ?? null),
                    'license' => trim((string) ($sede['matricula'] ?? '')),
                ];
            }
        }

        if ($offices === []) {
            $offices[] = [
                'name' => null,
                'address' => trim((string) ($this->direccion ?? '')),
                'phones' => self::parsePhones($this->telefono),
                'license' => trim((string) ($this->matricula ?? '')),
            ];
        }

        $offices = array_values(array_filter(
            $offices,
            fn (array $office): bool => filled($office['name'])
                || $office['address'] !== ''
                || $office['phones'] !== []
                || $office['license'] !== '',
        ));

        return [
            'city' => $this->nombre,
            'offices' => $offices,
        ];
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

        return collect($this->normalizedSedesList())
            ->map(fn (array $sede): array => [
                'nombre' => (string) $sede['nombre_sede'],
                'direccion' => (string) ($sede['direccion'] ?? ''),
                'telefono' => (string) ($sede['telefono'] ?? ''),
                'email' => (string) ($sede['email'] ?? ''),
                'matricula' => (string) ($sede['matricula'] ?? ''),
            ])
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

    /**
     * Opciones del formulario de contacto: una por ciudad o una por sede.
     *
     * @return Collection<int, array{value: string, label: string}>
     */
    public static function contactFormOfficeOptions(): Collection
    {
        return static::contactPageCiudades()
            ->flatMap(fn (self $ciudad): array => $ciudad->contactFormOfficeChoices())
            ->values();
    }

    /**
     * @return list<array{value: string, label: string}>
     */
    public function contactFormOfficeChoices(): array
    {
        if ($this->tiene_multiples_sedes && filled($this->sedes)) {
            $choices = [];

            foreach ($this->contactSedesRelevantes() as $index => $sede) {
                if (blank($this->resolveSedeInboxEmail($sede))) {
                    continue;
                }

                $sedeIndex = $this->hasMoreThanTwoContactSedes()
                    ? array_search($sede, $this->normalizedSedesList(), true)
                    : $index;

                $choices[] = [
                    'value' => 'sede:'.$this->id.':'.(int) $sedeIndex,
                    'label' => $this->nombre.' - '.$sede['nombre_sede'],
                ];
            }

            return $choices;
        }

        if (blank($this->contactInboxEmail())) {
            return [];
        }

        return [[
            'value' => "ciudad:{$this->id}",
            'label' => $this->nombre,
        ]];
    }

    public static function resolveContactFormEmail(string $selection): ?string
    {
        if (preg_match('/^ciudad:(\d+)$/', $selection, $matches)) {
            $ciudad = static::find((int) $matches[1]);

            return $ciudad?->contactInboxEmail();
        }

        if (preg_match('/^sede:(\d+):(\d+)$/', $selection, $matches)) {
            $ciudad = static::find((int) $matches[1]);

            return $ciudad?->contactSedeEmailAt((int) $matches[2]);
        }

        return null;
    }

    public static function resolveContactFormLabel(string $selection): ?string
    {
        foreach (static::contactFormOfficeOptions() as $option) {
            if ($option['value'] === $selection) {
                return $option['label'];
            }
        }

        return null;
    }

    public function contactSedeEmailAt(int $index): ?string
    {
        if (! $this->tiene_multiples_sedes || blank($this->sedes)) {
            return null;
        }

        $sede = $this->sedes[$index] ?? null;

        if (! is_array($sede)) {
            return null;
        }

        return $this->resolveSedeInboxEmail($sede);
    }

    public function contactInboxEmail(): ?string
    {
        $email = trim((string) ($this->email ?? ''));

        return filled($email) ? $email : null;
    }

    /**
     * @param  array<string, mixed>  $sede
     */
    protected function resolveSedeInboxEmail(array $sede): ?string
    {
        $email = trim((string) ($sede['email'] ?? ''));

        return filled($email) ? $email : null;
    }
}
