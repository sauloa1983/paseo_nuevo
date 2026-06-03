<?php

namespace App\Filament\Resources\Usuarios\Schemas;

use App\Models\Cargo;
use App\Models\Usuario;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Support\Enums\Alignment;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class UsuariosForm
{
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
        return '/storage/' . ltrim(str_replace('\\', '/', $diskPath), '/');
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
                                                ])
                                                ->disabledOn('edit')
                                                ->dehydrated(),

                                            TextInput::make('nombres')
                                                ->label('Nombres')
                                                ->required()
                                                ->maxLength(255)
                                                ->validationMessages([
                                                    'required' => 'Los nombres son obligatorios.',
                                                ]),

                                            TextInput::make('apellidos')
                                                ->label('Apellidos')
                                                ->required()
                                                ->maxLength(255)
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
                                                    'unique' => 'Ese usuario ya existe.',
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

                                            TextInput::make('usuario')
                                                ->label('Usuario')
                                                ->required()
                                                ->maxLength(255)
                                                ->unique(
                                                    table: Usuario::class,
                                                    column: 'usuario',
                                                    ignoreRecord: true,
                                                )
                                                ->validationMessages([
                                                    'required' => 'El usuario es obligatorio.',
                                                    'unique' => 'Ese usuario ya existe.',
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
                                ]),
                        ]),
                ]),
        ]);
    }
}
