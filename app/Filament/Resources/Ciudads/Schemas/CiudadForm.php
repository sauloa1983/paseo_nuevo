<?php

namespace App\Filament\Resources\Ciudads\Schemas;

use App\Filament\Resources\Ciudads\RelationManagers\ContactsRelationManager;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\EditRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class CiudadForm
{
    /**
     * Ruta relativa a la raíz del disco `public` (p. ej. ciudades/archivo.jpg).
     */
    private static function normalizeImagenDiskPath(mixed $state): ?string
    {
        if (is_array($state)) {
            $state = Arr::first($state);
        }

        if (! is_string($state) || blank($state)) {
            return null;
        }

        $path = str_replace('\\', '/', trim($state));
        $path = ltrim(str_replace(['storage/', 'public/'], '', $path), '/');
        $path = ltrim(str_replace('ciudades/', '', $path), '/');

        return filled($path) ? 'ciudades/' . $path : null;
    }

    private static function imagenPublicUrl(string $diskPath): string
    {
        return '/storage/' . ltrim(str_replace('\\', '/', $diskPath), '/');
    }

    private static function imagenMimeType(string $diskPath): string
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
                Tabs::make('Tabs')
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make('Información de la Sede')
                            ->icon('heroicon-o-building-office-2')
                            ->schema([
                                TextInput::make('nombre')
                                    ->label('Nombre de la Ciudad')
                                    ->required()
                                    ->maxLength(100)
                                    ->unique(ignoreRecord: true)
                                    ->columnSpanFull()
                                    ->validationMessages([
                                        'required' => 'El nombre de la ciudad es obligatorio.',
                                        'max' => 'El nombre de la ciudad no puede tener más de 100 caracteres.',
                                        'unique' => 'Ya existe una ciudad con este nombre. Por favor, elige otro nombre.',
                                    ]),

                                FileUpload::make('imagen')
                                    ->label('Imagen de la Ciudad')
                                    ->image()
                                    ->disk('public')
                                    ->directory('ciudades')
                                    ->visibility('public')
                                    ->imagePreviewHeight('180px')
                                    ->panelLayout('compact')
                                    ->columnSpanFull()
                                    ->openable()
                                    ->previewable()
                                    ->downloadable()
                                    ->fetchFileInformation(false)
                                    ->getUploadedFileUsing(function (
                                        FileUpload $component,
                                        string $file,
                                        string | array | null $storedFileNames,
                                    ): ?array {
                                        $path = self::normalizeImagenDiskPath($file) ?? $file;
                                        $storage = $component->getDisk();

                                        if (! $storage->exists($path)) {
                                            return null;
                                        }

                                        return [
                                            'name' => (is_array($storedFileNames)
                                                ? ($storedFileNames[$file] ?? null)
                                                : $storedFileNames) ?? basename($path),
                                            'size' => $storage->size($path),
                                            'type' => self::imagenMimeType($path),
                                            'url' => self::imagenPublicUrl($path),
                                        ];
                                    })
                                    ->getOpenableFileUrlUsing(
                                        fn (string $file): string => self::imagenPublicUrl(
                                            self::normalizeImagenDiskPath($file) ?? $file,
                                        ),
                                    )
                                    ->afterStateHydrated(function (FileUpload $component, string | array | null $state): void {
                                        if (blank($state)) {
                                            return;
                                        }

                                        $disk = $component->getDisk();
                                        $path = self::normalizeImagenDiskPath($state);

                                        if (blank($path) || ! $disk->exists($path)) {
                                            $component->rawState([]);

                                            return;
                                        }

                                        $component->rawState([(string) Str::uuid() => $path]);
                                    })
                                    ->dehydrateStateUsing(function (mixed $state): ?string {
                                        return self::normalizeImagenDiskPath($state);
                                    })
                                    ->maxSize(5120)
                                    ->acceptedFileTypes([
                                        'image/jpeg',
                                        'image/png',
                                        'image/webp',
                                        'image/avif',
                                    ])
                                    ->validationMessages([
                                        'image' => 'El archivo debe ser una imagen válida.',
                                        'max' => 'La imagen no puede ser mayor a 5MB.',
                                    ]),

                                Section::make('Datos de contacto')
                                    ->description('Información principal de la ciudad cuando opera con una sola sede.')
                                    ->schema([
                                        Grid::make(3)
                                            ->schema([
                                                TextInput::make('telefono')
                                                    ->label('Teléfono')
                                                    ->tel()
                                                    ->maxLength(50),

                                                TextInput::make('direccion')
                                                    ->label('Dirección')
                                                    ->maxLength(255)
                                                    ->columnSpan(2),
                                            ]),

                                        TextInput::make('matricula')
                                            ->label('Matrícula')
                                            ->maxLength(100)
                                            ->placeholder('Ej: M. de A. 0126/96'),
                                    ])
                                    ->columnSpanFull(),

                                Toggle::make('tiene_multiples_sedes')
                                    ->label('¿Esta ciudad tiene 2 o más sedes?')
                                    ->live()
                                    ->default(false)
                                    ->afterStateUpdated(function (Set $set, ?bool $state): void {
                                        if (! $state) {
                                            $set('sedes', []);
                                        }
                                    })
                                    ->columnSpanFull(),

                                Repeater::make('sedes')
                                    ->label('Sedes y dependencias')
                                    ->visible(fn (Get $get): bool => (bool) $get('tiene_multiples_sedes'))
                                    ->dehydrated(fn (Get $get): bool => (bool) $get('tiene_multiples_sedes'))
                                    ->columnSpanFull()
                                    ->extraAttributes([
                                        'class' => 'pe-ciudad-sedes-repeater',
                                    ])
                                    ->collapsible()
                                    ->collapsed()
                                    ->itemLabel(fn (array $state): ?string => filled($state['nombre_sede'] ?? null)
                                        ? (string) $state['nombre_sede']
                                        : 'Nueva sede')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('nombre_sede')
                                                    ->label('Nombre de la sede')
                                                    ->required()
                                                    ->maxLength(100),

                                                TextInput::make('telefono')
                                                    ->label('Teléfono')
                                                    ->tel()
                                                    ->maxLength(50),
                                            ]),

                                        Grid::make(2)
                                            ->schema([
                                                TextInput::make('direccion')
                                                    ->label('Dirección')
                                                    ->maxLength(255),

                                                TextInput::make('matricula')
                                                    ->label('Matrícula')
                                                    ->maxLength(100),
                                            ]),

                                        TextInput::make('email')
                                            ->label('Correo electrónico')
                                            ->email()
                                            ->maxLength(255)
                                            ->columnSpanFull(),

                                        Repeater::make('dependencias')
                                            ->label('Dependencias')
                                            ->helperText('Si tiene más de 2 sedes, agregue aquí las dependencias y contactos adicionales de esta sede.')
                                            ->extraAttributes([
                                                'class' => 'pe-ciudad-dependencias-repeater',
                                            ])
                                            ->collapsible()
                                            ->collapsed()
                                            ->itemLabel(fn (array $state): ?string => filled($state['nombre_dependencia'] ?? null)
                                                ? (string) $state['nombre_dependencia']
                                                : 'Nueva dependencia')
                                            ->schema([
                                                Grid::make(2)
                                                    ->schema([
                                                        TextInput::make('nombre_dependencia')
                                                            ->label('Dependencia')
                                                            ->placeholder('Ej: Ventas, Arriendos')
                                                            ->maxLength(100),

                                                        TextInput::make('contacto_nombre')
                                                            ->label('Nombre del contacto')
                                                            ->maxLength(100),

                                                        TextInput::make('telefono_contacto')
                                                            ->label('Teléfono de contacto')
                                                            ->tel()
                                                            ->maxLength(50),

                                                        TextInput::make('email_contacto')
                                                            ->label('Correo electrónico')
                                                            ->email()
                                                            ->maxLength(255),
                                                    ]),
                                            ]),
                                    ]),

                                Toggle::make('has_office')
                                    ->label('¿Tiene Oficina Física?')
                                    ->live()
                                    ->default(false)
                                    ->columnSpanFull(),

                                Toggle::make('visible_buscador')
                                    ->label('Visible en el buscador principal')
                                    ->helperText('Controla si la ciudad aparece en el select público del sitio web.')
                                    ->default(true)
                                    ->columnSpanFull(),
                            ]),

                        Tab::make('Canales de WhatsApp')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->schema([
                                Placeholder::make('whatsapp_help')
                                    ->label('')
                                    ->content('Números mostrados en el widget de WhatsApp del sitio. Use solo dígitos con indicativo 57 (ej: 573001234567).')
                                    ->columnSpanFull(),

                                Grid::make(2)
                                    ->schema([
                                        TextInput::make('whatsapp_arriendo')
                                            ->label('WhatsApp Arriendos')
                                            ->tel()
                                            ->maxLength(20)
                                            ->placeholder('573001234567')
                                            ->helperText('Número al elegir Arriendos en el widget.'),

                                        TextInput::make('whatsapp_venta')
                                            ->label('WhatsApp Ventas')
                                            ->tel()
                                            ->maxLength(20)
                                            ->placeholder('573001234567')
                                            ->helperText('Número al elegir Ventas en el widget.'),
                                    ]),
                            ]),

                        Tab::make('Dependencias y Contactos')
                            ->icon('heroicon-o-user-group')
                            ->visible(fn (Get $get): bool => (bool) $get('has_office') && ! (bool) $get('tiene_multiples_sedes'))
                            ->schema([
                                Placeholder::make('contacts_save_hint')
                                    ->label('')
                                    ->content('Guarde la ciudad y vuelva a editarla para administrar las dependencias y contactos de esta oficina.')
                                    ->visible(fn ($livewire): bool => ! $livewire instanceof EditRecord)
                                    ->columnSpanFull(),

                                Livewire::make(
                                    ContactsRelationManager::class,
                                    fn (EditRecord $livewire): array => [
                                        'ownerRecord' => $livewire->getRecord(),
                                        'pageClass' => $livewire::class,
                                    ],
                                )
                                    ->visible(fn ($livewire): bool => $livewire instanceof EditRecord)
                                    ->columnSpanFull(),
                            ]),
                    ]),
            ]);
    }
}
