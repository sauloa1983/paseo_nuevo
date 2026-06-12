<?php

namespace App\Filament\Resources\Usuarios\Schemas;

use App\Models\Cargo;
use App\Models\Usuario;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UsuariosForm
{
    public const DEFAULT_PASSWORD = 'paseoespana';

    private static function normalizeFotoDiskPath(mixed $state): ?string
    {
        if (is_array($state)) {
            $state = Arr::first($state);
        }

        if (! is_string($state) || blank($state)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($state));
        $path = ltrim(str_replace(['storage/', 'public/'], '', $path), '/');
        $path = ltrim(str_replace('usuarios/', '', $path), '/');

        return filled($path) ? 'usuarios/' . $path : null;
    }

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
            'avif' => 'image/avif',
            default => 'image/jpeg',
        };
    }

    private static function suggestUsuario(?string $nombres, ?string $apellidos): string
    {
        $primerNombre = Str::of($nombres ?? '')
            ->trim()
            ->explode(' ')
            ->filter()
            ->first();

        $apellidosNormalizados = Str::of($apellidos ?? '')
            ->trim()
            ->ascii()
            ->upper()
            ->replaceMatches('/\s+/', '');

        if (blank($primerNombre) || $apellidosNormalizados->isEmpty()) {
            return '';
        }

        $inicial = Str::substr(
            Str::upper(Str::ascii((string) $primerNombre)),
            0,
            1,
        );

        return $inicial . $apellidosNormalizados;
    }

    private static function usuarioExists(string $usuario, ?int $ignoreId = null): bool
    {
        $query = Usuario::query()->where('usuario', $usuario);

        if (filled($ignoreId)) {
            $query->where('id', '!=', $ignoreId);
        }

        return $query->exists();
    }

    public static function suggestUniqueUsuario(?string $nombres, ?string $apellidos, ?int $ignoreId = null): string
    {
        $base = self::suggestUsuario($nombres, $apellidos);

        if (blank($base)) {
            return '';
        }

        $usuario = $base;
        $suffix = 1;

        while (self::usuarioExists($usuario, $ignoreId)) {
            $suffix++;
            $usuario = $base . $suffix;
        }

        return $usuario;
    }

    private static function syncUsuarioFromNombre(Get $get, Set $set, mixed $livewire): void
    {
        if ($get('usuario_manually_edited')) {
            return;
        }

        $set(
            'usuario',
            self::suggestUniqueUsuario(
                $get('nombres'),
                $get('apellidos'),
                $livewire->record?->id ?? null,
            ),
        );
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            Tabs::make('Formulario inmueble')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Información general')
                        ->schema([
                            Grid::make([
                                'default' => 1,
                                'lg' => 12,
                            ])
                                ->columnSpanFull()
                                ->schema([
                                    Section::make('Foto de perfil')
                                        ->description('Formatos JPG, PNG o WebP. Máximo 5 MB.')
                                        ->columnSpan([
                                            'default' => 1,
                                            'lg' => 3,
                                        ])
                                        ->schema([
                                            FileUpload::make('foto')
                                                ->hiddenLabel()
                                                ->avatar()
                                                ->disk('public')
                                                ->directory('usuarios')
                                                ->visibility('public')
                                                ->alignment(Alignment::Center)
                                                ->placeholder('Subir foto')
                                                ->columnSpanFull()
                                                ->previewable()
                                                ->imageEditor()
                                                ->circleCropper()
                                                ->fetchFileInformation(false)
                                                ->extraAttributes([
                                                    'class' => 'usuario-foto-upload',
                                                ])
                                                ->getUploadedFileUsing(function (
                                                    FileUpload $component,
                                                    string $file,
                                                    string | array | null $storedFileNames,
                                                ): ?array {
                                                    $path = self::normalizeFotoDiskPath($file) ?? $file;
                                                    $storage = $component->getDisk();

                                                    if (! $storage->exists($path)) {
                                                        return null;
                                                    }

                                                    return [
                                                        'name' => (is_array($storedFileNames)
                                                            ? ($storedFileNames[$file] ?? null)
                                                            : $storedFileNames) ?? basename($path),
                                                        'size' => $storage->size($path),
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
                                                    if (blank($state)) {
                                                        return;
                                                    }

                                                    $disk = $component->getDisk();
                                                    $path = self::normalizeFotoDiskPath($state);

                                                    if (blank($path) || ! $disk->exists($path)) {
                                                        $component->rawState([]);

                                                        return;
                                                    }

                                                    $component->rawState([(string) Str::uuid() => $path]);
                                                })
                                                ->dehydrateStateUsing(function (mixed $state): ?string {
                                                    return self::normalizeFotoDiskPath($state);
                                                })
                                                ->maxSize(5120)
                                                ->acceptedFileTypes([
                                                    'image/jpeg',
                                                    'image/png',
                                                    'image/webp',
                                                    'image/avif',
                                                ])
                                                ->validationMessages([
                                                    'image' => 'Debe ser una imagen válida.',
                                                    'max' => 'La imagen no puede ser mayor a 5 MB.',
                                                ]),
                                        ]),

                                    Grid::make(2)
                                        ->columnSpan([
                                            'default' => 1,
                                            'lg' => 9,
                                        ])
                                        ->schema([
                                            TextInput::make('cedula')
                                                ->label('Cédula')
                                                ->required()
                                                ->numeric()
                                                ->rule('digits_between:6,12')
                                                ->unique(
                                                    table: Usuario::class,
                                                    column: 'cedula',
                                                    ignoreRecord: true,
                                                )
                                                ->validationMessages([
                                                    'required' => 'La cédula es obligatoria.',
                                                    'numeric' => 'La cédula debe ser un número.',
                                                    'unique' => 'Esa cédula ya existe.',
                                                    'digits_between' => 'La cédula debe tener entre 6 y 12 dígitos.',
                                                ]),

                                            Hidden::make('usuario_manually_edited')
                                                ->default(fn ($livewire): bool => ! ($livewire instanceof CreateRecord))
                                                ->dehydrated(false),

                                            TextInput::make('nombres')
                                                ->label('Nombres')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (Get $get, Set $set, $livewire): void {
                                                    self::syncUsuarioFromNombre($get, $set, $livewire);
                                                })
                                                ->validationMessages([
                                                    'required' => 'Los nombres son obligatorios.',
                                                ]),

                                            TextInput::make('apellidos')
                                                ->label('Apellidos')
                                                ->required()
                                                ->maxLength(255)
                                                ->live(onBlur: true)
                                                ->afterStateUpdated(function (Get $get, Set $set, $livewire): void {
                                                    self::syncUsuarioFromNombre($get, $set, $livewire);
                                                })
                                                ->validationMessages([
                                                    'required' => 'Los apellidos son obligatorios.',
                                                ]),

                                            TextInput::make('telefonos')
                                                ->label('Teléfonos')
                                                ->required()
                                                ->maxLength(255)
                                                ->validationMessages([
                                                    'required' => 'Los teléfonos son obligatorios.',
                                                ]),

                                            TextInput::make('email')
                                                ->label('Email')
                                                ->email()
                                                ->required()
                                                ->maxLength(255)
                                                ->unique(
                                                    table: Usuario::class,
                                                    column: 'email',
                                                    ignoreRecord: true,
                                                )
                                                ->validationMessages([
                                                    'required' => 'El correo es obligatorio.',
                                                    'email' => 'Debe ser un correo válido.',
                                                    'unique' => 'Ese correo ya está registrado.',
                                                ]),

                                            Select::make('cargo')
                                                ->label('Cargo')
                                                ->options(
                                                    Cargo::query()
                                                        ->where('id', '!=', 1)
                                                        ->pluck('nombre', 'id')
                                                )
                                                ->searchable()
                                                ->required()
                                                ->validationMessages([
                                                    'required' => 'El cargo es obligatorio.',
                                                ]),

                                            TextInput::make('direccion')
                                                ->label('Dirección')
                                                ->maxLength(255)
                                                ->columnSpanFull(),

                                            Section::make('')
                                                ->schema([])
                                                ->extraAttributes([
                                                    'class' => 'border-t border-gray-300 my-2',
                                                ])
                                                ->columnSpanFull(),

                                            Grid::make([
                                                'default' => 1,
                                                'md' => 2,
                                            ])
                                                ->columnSpanFull()
                                                ->schema([
                                                    TextInput::make('usuario')
                                                        ->label('Usuario')
                                                        ->required()
                                                        ->maxLength(255)
                                                        ->live(onBlur: true)
                                                        ->afterStateUpdated(function (?string $state, Get $get, Set $set, $livewire): void {
                                                            $suggested = self::suggestUniqueUsuario(
                                                                $get('nombres'),
                                                                $get('apellidos'),
                                                                $livewire->record?->id ?? null,
                                                            );

                                                            $set(
                                                                'usuario_manually_edited',
                                                                filled($state) && $state !== $suggested,
                                                            );
                                                        })
                                                        ->unique(
                                                            table: Usuario::class,
                                                            column: 'usuario',
                                                            ignoreRecord: true,
                                                        )
                                                        ->helperText('Se genera automáticamente con la inicial del nombre y los apellidos. Si lo edita, debe ser único.')
                                                        ->validationMessages([
                                                            'required' => 'El usuario es obligatorio.',
                                                            'unique' => 'Ese nombre de usuario ya existe.',
                                                        ]),

                                                    Select::make('vigente')
                                                        ->label('Estado')
                                                        ->options([
                                                            1 => 'Activo',
                                                            0 => 'Inactivo',
                                                        ])
                                                        ->required()
                                                        ->validationMessages([
                                                            'required' => 'El estado es obligatorio.',
                                                        ]),
                                                ]),

                                            Grid::make([
                                                'default' => 1,
                                                'md' => 2,
                                            ])
                                                ->columnSpanFull()
                                                ->schema([
                                                    TextInput::make('password')
                                                        ->label('Contraseña')
                                                        ->password()
                                                        ->revealable()
                                                        ->default(fn ($livewire): ?string => $livewire instanceof CreateRecord
                                                            ? self::DEFAULT_PASSWORD
                                                            : null)
                                                        ->dehydrated(fn (?string $state, $livewire): bool => $livewire instanceof CreateRecord
                                                            || filled($state))
                                                        ->same('passwordConfirmation')
                                                        ->maxLength(255)
                                                        ->helperText(fn ($livewire): string => $livewire instanceof CreateRecord
                                                            ? 'Por defecto: ' . self::DEFAULT_PASSWORD . '.'
                                                            : 'Dejar vacío para mantener la contraseña actual.')
                                                        ->validationMessages([
                                                            'same' => 'Las contraseñas no coinciden.',
                                                        ]),

                                                    TextInput::make('passwordConfirmation')
                                                        ->label('Confirmar contraseña')
                                                        ->password()
                                                        ->revealable()
                                                        ->default(fn ($livewire): ?string => $livewire instanceof CreateRecord
                                                            ? self::DEFAULT_PASSWORD
                                                            : null)
                                                        ->dehydrated(false)
                                                        ->maxLength(255),
                                                ]),
                                        ]),
                                ]),
                        ]),
                ]),
        ]);
    }
}
