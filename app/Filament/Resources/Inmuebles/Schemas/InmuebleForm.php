<?php

namespace App\Filament\Resources\Inmuebles\Schemas;

use App\Models\FotoInmueble;
use App\Models\Inmueble;
use App\Models\Usuario;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use App\Services\ImageWatermarkService;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class InmuebleForm
{
    /**
     * Ruta relativa a la raíz del disco `public` (p. ej. fotos/archivo.jpg).
     * Filament usa esta ruta en exists() y en las URLs de vista previa.
     */
    private static function normalizeFotoDiskPath(mixed $state): ?string
    {
        if (is_array($state)) {
            $state = Arr::first($state);
        }

        if (! is_string($state) || blank($state)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($state));

        if (str_contains($path, '://')) {
            $path = (string) (parse_url($path, PHP_URL_PATH) ?? $path);
        }

        $path = ltrim(str_replace(['storage/', 'public/'], '', $path), '/');
        $path = ltrim(str_replace('fotos/', '', $path), '/');
        $path = basename($path);

        return filled($path) ? 'fotos/' . $path : null;
    }

    private static function publicStorageAbsolutePath(string $normalizedPath): string
    {
        return public_storage_file_path($normalizedPath);
    }

    /**
     * Resuelve una foto en public_html/storage/fotos.
     * Si solo existe en rutas legacy del servidor, la copia allí.
     */
    private static function resolveFotoForPublicDisk(?string $normalizedPath, bool $migrate = true): ?string
    {
        if (blank($normalizedPath)) {
            return null;
        }

        if (is_file(self::publicStorageAbsolutePath($normalizedPath))) {
            return $normalizedPath;
        }

        $disk = Storage::disk('public');

        if ($disk->exists($normalizedPath)) {
            return $normalizedPath;
        }

        $filename = ltrim(str_replace('fotos/', '', $normalizedPath), '/');

        $legacySources = [
            storage_path('app/public/' . $normalizedPath),
            storage_path('app/public/fotos/' . $filename),
            public_path('fotos/' . $filename),
        ];

        foreach ($legacySources as $absolutePath) {
            if (! is_file($absolutePath)) {
                continue;
            }

            if ($migrate) {
                $disk->put($normalizedPath, (string) file_get_contents($absolutePath));
            }

            return $normalizedPath;
        }

        $publicFotosDir = public_storage_root() . DIRECTORY_SEPARATOR . 'fotos';

        if (is_dir($publicFotosDir)) {
            foreach (scandir($publicFotosDir) ?: [] as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }

                if (strcasecmp($entry, $filename) === 0) {
                    return 'fotos/' . $entry;
                }
            }
        }

        return null;
    }

    private static function fotoExistsOnPublicDisk(string $normalizedPath): bool
    {
        if (public_storage_file_exists($normalizedPath)) {
            return true;
        }

        return Storage::disk('public')->exists($normalizedPath);
    }

    private static function fotoSizeOnPublicDisk(string $normalizedPath): int
    {
        $absolute = self::publicStorageAbsolutePath($normalizedPath);

        if (is_file($absolute)) {
            return (int) filesize($absolute);
        }

        $disk = Storage::disk('public');

        return $disk->exists($normalizedPath) ? (int) $disk->size($normalizedPath) : 0;
    }

    /**
     * URL pública en el sitio principal (public_html/storage), no en el subdominio del panel.
     */
    private static function fotoPublicUrl(string $diskPath): string
    {
        return admin_storage_url($diskPath) ?? '';
    }

    private static function fotoMimeType(string $diskPath): string
    {
        return match (strtolower(pathinfo($diskPath, PATHINFO_EXTENSION))) {
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            default => 'image/jpeg',
        };
    }

    /**
     * @return list<string>
     */
    public static function galeriaPathsFromRecord(Inmueble $inmueble): array
    {
        return $inmueble->fotoInmueble()
            ->orderBy('posicion')
            ->get()
            ->map(function (FotoInmueble $foto): ?string {
                $raw = $foto->getAttributes()['foto'] ?? null;

                return self::normalizeFotoDiskPath($raw ?? $foto->foto);
            })
            ->filter()
            ->values()
            ->all();
    }

    /**
     * Estado UUID => ruta en disco para FileUpload (compat. con EditInmueble).
     *
     * @return array<string, string>
     */
    public static function buildGaleriaFileUploadState(Inmueble $inmueble): array
    {
        $files = [];

        foreach (self::galeriaPathsFromRecord($inmueble) as $path) {
            $resolved = self::resolveFotoForPublicDisk($path);

            if (blank($resolved) || ! self::fotoExistsOnPublicDisk($resolved)) {
                continue;
            }

            $files[(string) Str::uuid()] = $resolved;
        }

        return $files;
    }

    /**
     * @param  array<int|string, mixed>  $paths
     */
    public static function syncGaleriaFotos(Inmueble $inmueble, array $paths): void
    {
        /** @var Collection<int, string> $orderedPaths */
        $orderedPaths = collect($paths)
            ->map(fn (mixed $path): ?string => self::normalizeFotoDiskPath($path))
            ->filter()
            ->values();

        /** @var Collection<string, FotoInmueble> $existingByPath */
        $existingByPath = $inmueble->fotoInmueble()
            ->get()
            ->mapWithKeys(function (FotoInmueble $foto): array {
                $path = self::normalizeFotoDiskPath($foto->foto);

                return $path ? [$path => $foto] : [];
            });

        foreach ($existingByPath as $path => $foto) {
            if (! $orderedPaths->contains($path)) {
                $foto->delete();
            }
        }

        foreach ($orderedPaths as $index => $path) {
            $posicion = $index + 1;

            if ($existingByPath->has($path)) {
                $existingByPath[$path]->update(['posicion' => $posicion]);

                continue;
            }

            FotoInmueble::create([
                'inmueble_fk' => $inmueble->id,
                'foto' => $path,
                'posicion' => $posicion,
            ]);
        }
    }

    private static function applyNonNegativeConstraints(TextInput $field): TextInput
    {
        return $field
            ->minValue(0)
            ->validationMessages([
                'min' => 'Este campo no puede tener valores negativos.',
            ])
            ->extraInputAttributes(['min' => '0'], merge: true)
            ->rule(function (): \Closure {
                return function (string $attribute, mixed $value, \Closure $fail): void {
                    if (blank($value)) {
                        return;
                    }

                    $normalized = is_numeric($value)
                        ? (float) $value
                        : (float) str_replace('.', '', (string) $value);

                    if ($normalized < 0) {
                        $fail('Este campo no puede tener valores negativos.');
                    }
                };
            });
    }

    private static function fieldToggle(string $name): Toggle
    {
        return Toggle::make($name)->inline(false);
    }

    private static function applyNonNegativeTextConstraints(TextInput $field): TextInput
    {
        return $field->rule(function (): \Closure {
            return function (string $attribute, mixed $value, \Closure $fail): void {
                if (blank($value)) {
                    return;
                }

                $value = trim((string) $value);

                if (str_starts_with($value, '-') || (is_numeric($value) && (float) $value < 0)) {
                    $fail('Este campo no puede tener valores negativos.');
                }
            };
        });
    }

    /**
     * @return list<string>
     */
    private static function existingGaleriaPathsFromLivewire(mixed $livewire): array
    {
        if (! is_object($livewire) || ! isset($livewire->record)) {
            return [];
        }

        if (! $livewire->record instanceof Inmueble) {
            return [];
        }

        return self::galeriaPathsFromRecord($livewire->record);
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            Tabs::make('Tabs')
                ->scrollable()
                ->tabs([
                    Tab::make('Datos generales')
                        ->schema([
                            Grid::make(1)
                            ->schema([
                                Grid::make([
                                    'default' => 1,  // Móviles pequeños
                                    'sm' => 2,       // ≥640px
                                    'lg' => 3,       // ≥1024px
                                    'xl' => 4,       // ≥1280px
                                ])
                                    ->schema([
                                        Grid::make([
                                                'default' => 1,  // Móviles pequeños
                                                'sm' => 2,       // ≥640px
                                                'lg' => 4,       // ≥1024px
                                                ])
                                            ->schema([
                                                self::fieldToggle('arriendo')->label('Arriendo'),
                                                self::fieldToggle('venta')->label('Venta'),
                                                self::fieldToggle('destacado')->label('Destacado'),
                                                self::fieldToggle('iva')->label('IVA'),
                                            ])
                                            ->columnSpanFull(),
                                        Select::make('badge_status')
                                            ->label('Etiqueta Comercial')
                                            ->options(Inmueble::BADGE_STATUS_OPCIONES)
                                            ->placeholder('Sin etiqueta')
                                            ->native(false)
                                            ->nullable()
                                            ->prefixIcon('heroicon-o-tag')
                                            ->helperText('Se muestra sobre la foto en la web pública.'),
                                        DatePicker::make('contract_end_date')
                                            ->label('Fecha de Desocupación / Vencimiento')
                                            ->native(false)
                                            ->displayFormat('d/m/Y')
                                            ->nullable()
                                            ->prefixIcon('heroicon-o-calendar-days')
                                            ->helperText('Selecciona la fecha en la que se entregará el inmueble para calcular automáticamente el tiempo de espera en la web.'),
                                        // IZQUIERDA: Campos principales
                                        Select::make('tipo_fk')
                                            ->label('Tipo inmueble')
                                            ->relationship('tipo_inmueble', 'tipo')
                                            //->searchable()
                                            ->preload()
                                            ->required()
                                            ->validationMessages([
                                                'required' => 'El tipo de inmueble es obligatorio.',  // ← Mensaje custom
                                            ])
                                            ->columnSpan(1),
                                        self::applyNonNegativeConstraints(
                                            TextInput::make('valor_arriendo')
                                                ->label('Valor arriendo')
                                                ->prefix('$')
                                                ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                                                ->stripCharacters('.')
                                                ->numeric()
                                                ->dehydrateStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : null),
                                        ),

                                        self::applyNonNegativeConstraints(
                                            TextInput::make('valor_venta')
                                                ->label('Valor venta')
                                                ->prefix('$')
                                                ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                                                ->stripCharacters('.')
                                                ->numeric()
                                                ->dehydrateStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : null),
                                        ),

                                        self::applyNonNegativeConstraints(
                                            TextInput::make('administracion')
                                                ->label('Valor administración')
                                                ->prefix('$')
                                                ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                                                ->stripCharacters('.')
                                                ->numeric()
                                                ->dehydrateStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : null),
                                        ),
                                        self::applyNonNegativeConstraints(
                                            TextInput::make('direccion')
                                                ->label('Dirección')
                                                //->columnSpanFull(),
                                        ),
                                        Select::make('ciudad')
                                            ->label('Ciudad')
                                            ->relationship(
                                                name: 'ciudadRelacion',
                                                titleAttribute: 'nombre',
                                                modifyQueryUsing: fn ($query) => $query
                                                    ->visibleInBuscador()
                                                    ->orderBy('nombre'),
                                            )
                                            ->preload()
                                            ->live()
                                            ->afterStateUpdated(fn (Set $set) => $set('barrio_fk', null))
                                            ->required()
                                            ->validationMessages([
                                                'required' => 'La ciudad es obligatoria.',  // ← Mensaje custom
                                            ]),
                                        Select::make('barrio_fk')
                                            ->label('Barrio')
                                            ->relationship(
                                                name: 'barrio',
                                                titleAttribute: 'nombre',
                                                modifyQueryUsing: fn ($query, Get $get) => $query
                                                    ->where('ciudad_fk', $get('ciudad'))
                                            )
                                            ->placeholder(fn (Get $get) => blank($get('ciudad'))
                                                ? 'Primero selecciona una ciudad'
                                                : 'Selecciona un barrio')
                                            ->preload()
                                            ->required()
                                            ->validationMessages([
                                                'required' => 'El barrio es obligatorio.',  // ← Mensaje custom
                                            ]),
                                        self::applyNonNegativeConstraints(
                                            TextInput::make('area_construida')
                                                ->label('Área construida (m²)')
                                                ->numeric(),
                                        ),
                                        Select::make('estrato')
                                            ->label('Estrato')
                                            ->options([
                                                1 => '1',
                                                2 => '2',
                                                3 => '3',
                                                4 => '4',
                                                5 => '5',
                                                6 => '6',
                                                10 => 'Comercial',
                                            ])
                                            ->formatStateUsing(fn ($state) => filled($state) ? (int) $state : null)
                                            ->native(false)
                                            ->placeholder('Seleccione estrato'),
                                        Select::make('ubicacion')
                                            ->label('Ubicación')
                                            ->options([
                                                '0' => 'Interior',
                                                '1' => 'Exterior',
                                            ]),
                                        Select::make('acceso')
                                            ->label('Acceso')
                                            ->options([
                                                '0' => 'Vehicular',
                                                '1' => 'Peatonal',
                                            ])
                                            ->placeholder('Seleccione el tipo de acceso')
                                            ->native(false),
                                        self::applyNonNegativeTextConstraints(
                                            TextInput::make('unidad')
                                                ->label('No. Piso'),
                                        ),

                                        self::fieldToggle('conjunto_cerrado')->label('Conjunto Cerrado'),

                                        self::fieldToggle('edificio')->label('Edificio'),

                                        Select::make('asesor')
                                            ->label('Asesor')
                                            ->options(function ($livewire) {
                                                $asesorActual = $livewire->record?->asesor ?? null;

                                                return Usuario::query()
                                                    ->where('cargo', '!=', 1)
                                                    ->where(function ($query) use ($asesorActual) {
                                                        $query->where('vigente', 1);

                                                        if (filled($asesorActual)) {
                                                            $query->orWhere('id', $asesorActual);
                                                        }
                                                    })
                                                    ->orderBy('nombres')
                                                    ->orderBy('apellidos')
                                                    ->get()
                                                    ->mapWithKeys(fn ($user) => [
                                                        $user->id => $user->nombres . ' ' . $user->apellidos,
                                                    ]);
                                            })
                                            ->searchable()
                                            ->preload()
                                            ->required()
                                            ->validationMessages([
                                                'required' => 'El asesor es obligatorio.',  // ← Mensaje custom
                                            ]),


                                    ])
                                    ->extraAttributes([
                                        'onkeydown' => "if (event.key === 'Enter') { event.preventDefault(); }",
                                    ])
                                ->columnSpan(1),

                                // DERECHA: Opciones rápidas
                                Grid::make(1) // Stack vertical toggles
                                    ->schema([
                                        self::fieldToggle('estado')
                                            ->live()
                                            ->label(fn ($state) => $state ? 'Activo' : 'Inactivo')
                                            ->onColor('success')
                                            ->offColor('danger')
                                            ->afterStateHydrated(function (Toggle $component, $state) {
                                                $component->state($state == 0);
                                            })
                                            ->dehydrateStateUsing(fn ($state) => $state ? 0 : 1),
                                    ])
                                    ->columnSpan(1),
                            ])
                        ])
                        ->columnSpanFull(),

                    Tab::make('Detalles del inmueble')
                        ->schema([
                            Grid::make(1)
                                ->schema([
                                    Grid::make([
                                        'default' => 1,  // Móviles pequeños
                                        'sm' => 2,       // ≥640px
                                        'md' => 3,       // ≥768px
                                        'lg' => 3,       // ≥1024px
                                        'xl' => 4,       // ≥1280px
                                    ])
                                        ->schema([
                                            self::applyNonNegativeConstraints(
                                                TextInput::make('no_alcobas')
                                                    ->label('Número de alcobas')
                                                    ->numeric(),
                                            ),
                                            self::applyNonNegativeConstraints(
                                                TextInput::make('no_closets')
                                                    ->label('Número de closets')
                                                    ->numeric(),
                                            ),
                                            self::applyNonNegativeConstraints(
                                                TextInput::make('no_banos')
                                                    ->label('Número de baños')
                                                    ->numeric(),
                                            ),
                                            self::fieldToggle('sala_comedor')->label('Sala Comedor'),
                                            self::fieldToggle('alcoba_servicio')->label('Alcoba de Servicio'),
                                            Select::make('tipo_cocina')
                                                ->label('Tipo Cocina')
                                                ->options([
                                                    'No tiene' => 'No Tiene',
                                                    'Integral' => 'Integral',
                                                    'Semintegral' => 'Semintegral',
                                                    'Tradicional' => 'Tradicional',
                                                ])
                                                ->placeholder('Seleccione el tipo de cocina')
                                                ->native(false),
                                            self::fieldToggle('hall')->label('Hall TV'),
                                            self::fieldToggle('estudio')->label('Estudio'),
                                            self::fieldToggle('mirador')->label('Mirador'),
                                            self::fieldToggle('balcon')->label('Balcón'),
                                            self::fieldToggle('patio')->label('Patio'),
                                            self::fieldToggle('zona_ropas')->label('Zona de Ropa'),
                                            self::fieldToggle('terraza')->label('Terraza'),
                                            self::fieldToggle('salon_n')->label('Salón'),
                                            self::applyNonNegativeConstraints(
                                                TextInput::make('no_salon')
                                                    ->label('Número de salones')
                                                    ->numeric(),
                                            ),
                                            self::fieldToggle('bodega')->label('Bodega'),
                                            self::applyNonNegativeConstraints(
                                                TextInput::make('no_bodega')
                                                    ->label('Número de bodegas')
                                                    ->numeric(),
                                            ),
                                            self::fieldToggle('oficina')->label('Oficina'),
                                            self::applyNonNegativeConstraints(
                                                TextInput::make('no_oficina')
                                                    ->label('Número de oficinas')
                                                    ->numeric(),
                                            ),
                                            self::fieldToggle('calentador')->label('Calentador'),
                                            self::fieldToggle('aire_acondicionado')->label('Aire Acondicionado'),
                                        ])
                                ])
                        ])
                        ->columnSpanFull(),


                    Tab::make('Detalles en la PH')
                        ->schema([
                            Grid::make([
                                'default' => 1,  // Móviles pequeños
                                'sm' => 2,       // ≥640px
                                'md' => 3,       // ≥768px
                                'lg' => 3,       // ≥1024px
                                'xl' => 4,       // ≥1280px
                                ])
                                ->schema([
                                    self::applyNonNegativeConstraints(
                                        TextInput::make('garajes')
                                            ->label('Número de parqueaderos')
                                            ->numeric(),
                                    ),
                                    self::fieldToggle('parq_moto')->label('Parqueadero para motos'),
                                    self::fieldToggle('parq_comunal')->label('Parqueadero comunal'),
                                    self::fieldToggle('lobby')->label('Lobby'),
                                    self::fieldToggle('ascensor')->label('Ascensor'),
                                    self::fieldToggle('vigilancia')->label('Celaduría'),
                                    self::fieldToggle('juegos')->label('Juegos Infantiles'),
                                    self::fieldToggle('salon_social')->label('Salón Social'),
                                    self::fieldToggle('gimnasio')->label('Gimnasio'),
                                    self::fieldToggle('piscina')->label('Piscina'),
                                    self::fieldToggle('sauna')->label('Sauna'),
                                    self::fieldToggle('turco')->label('Turco'),
                                    self::fieldToggle('cancha')->label('Cancha'),
                                    self::fieldToggle('bbq')->label('BBQ'),
                                ]),
                                Placeholder::make('detalles_separator')
                                    ->hiddenLabel()
                                    ->content(new HtmlString('<div class="pe-inmueble-detalles-divider" role="presentation"></div>'))
                                    ->columnSpanFull()
                                    ->dehydrated(false),

                                Textarea::make('observaciones')
                                    ->label('Observaciones')
                                    ->rows(4)
                                    ->columnSpanFull()
                                    ->placeholder('Notas internas o detalles adicionales del inmueble…'),
                        ])
                        ->columnSpanFull(),

                    Tab::make('Galería')
                        ->schema([
                            FileUpload::make('galeria_fotos')
                                ->label('Fotos del inmueble')
                                ->multiple()
                                ->reorderable()
                                ->appendFiles()
                                ->image()
                                ->disk('public')
                                ->directory('fotos')
                                ->visibility('public')
                                ->panelLayout('grid')
                                ->imagePreviewHeight('150')
                                ->removeUploadedFileButtonPosition('left top')
                                ->uploadButtonPosition('right top')
                                ->uploadProgressIndicatorPosition('right top')
                                ->loadingIndicatorPosition('right top')
                                ->extraAttributes([
                                    'class' => 'inmueble-galeria-upload',
                                ])
                                ->extraAlpineAttributes([
                                    'x-intersect:enter' => '$nextTick(() => document.dispatchEvent(new Event(\'visibilitychange\')))',
                                ])
                                ->columnSpanFull()
                                ->openable()
                                ->previewable()
                                ->deletable()
                                ->fetchFileInformation(false)
                                ->helperText('Arrastra varias fotos a la vez o reordénalas arrastrándolas. La primera foto será la portada.')
                                ->getUploadedFileUsing(function (
                                    FileUpload $component,
                                    string $file,
                                    string | array | null $storedFileNames,
                                ): ?array {
                                    $path = self::resolveFotoForPublicDisk(
                                        self::normalizeFotoDiskPath($file) ?? $file,
                                    );

                                    if (blank($path)) {
                                        return null;
                                    }

                                    if (! self::fotoExistsOnPublicDisk($path)) {
                                        return null;
                                    }

                                    return [
                                        'name' => (is_array($storedFileNames)
                                            ? ($storedFileNames[$file] ?? null)
                                            : $storedFileNames) ?? basename($path),
                                        'size' => self::fotoSizeOnPublicDisk($path),
                                        'type' => self::fotoMimeType($path),
                                        'url' => self::fotoPublicUrl($path),
                                    ];
                                })
                                ->getOpenableFileUrlUsing(
                                    fn (string $file): string => self::fotoPublicUrl(
                                        self::normalizeFotoDiskPath($file) ?? $file,
                                    ),
                                )
                                ->afterStateHydrated(function (FileUpload $component, string | array | null $state): void {
                                    $files = [];
                                    $pending = Arr::wrap($state);

                                    if ($pending === [] || $pending === [null]) {
                                        $pending = self::existingGaleriaPathsFromLivewire($component->getLivewire());
                                    }

                                    foreach ($pending as $key => $file) {
                                        if (! is_string($file) || blank($file)) {
                                            continue;
                                        }

                                        $path = self::resolveFotoForPublicDisk(
                                            self::normalizeFotoDiskPath($file),
                                        );

                                        if (blank($path) || ! self::fotoExistsOnPublicDisk($path)) {
                                            continue;
                                        }

                                        $files[is_numeric($key) ? (string) Str::uuid() : $key] = $path;
                                    }

                                    $component->rawState($files);
                                })
                                ->saveUploadedFileUsing(function (
                                    TemporaryUploadedFile $file,
                                    FileUpload $component,
                                ): string {
                                    $existingPaths = self::existingGaleriaPathsFromLivewire(
                                        $component->getLivewire(),
                                    );

                                    if (! $file instanceof TemporaryUploadedFile) {
                                        $path = self::normalizeFotoDiskPath($file);

                                        if ($path !== null && in_array($path, $existingPaths, true)) {
                                            return $path;
                                        }
                                    }

                                    return app(ImageWatermarkService::class)->applyToUploadedFile(
                                        $file,
                                        disk: 'public',
                                        directory: 'fotos',
                                    );
                                })
                                ->imageEditor()
                                ->required()
                                ->minFiles(1)
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/webp'])
                                ->validationMessages([
                                    'required' => 'Debes subir al menos una foto.',
                                    'min' => 'Debes subir al menos una foto.',
                                    'mimetypes' => 'El archivo debe ser un formato de imagen válido (JPEG, PNG, JPG).',
                                    'mimes' => 'Solo se permiten archivos con extensión .jpg, .jpeg o .png.',
                                ]),
                        ])
                        ->columnSpanFull(),

                    Tab::make('Video')
                        ->schema([
                            TextInput::make('video')
                                ->label('Enlace de video')
                                ->url()
                                ->nullable()
                                ->maxLength(500)
                                ->placeholder('Ej: https://www.youtube.com/watch?v=...')
                                ->helperText('Puedes pegar un enlace de YouTube o Vimeo.')
                                ->columnSpanFull(),
                        ])
                        ->columnSpanFull(),
                ])
                ->columnSpanFull(),
        ]);

    }

}

