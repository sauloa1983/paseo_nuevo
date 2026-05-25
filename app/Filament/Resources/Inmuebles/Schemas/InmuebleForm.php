<?php

namespace App\Filament\Resources\Inmuebles\Schemas;

use App\Models\Usuario;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Forms\Components\Hidden;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\File;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Filament\Schemas\Schema;
use Filament\Support\RawJs;
use Filament\Forms\Components\FileUpload;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Alignment;

class InmuebleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            Tabs::make('Formulario inmueble')
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
                                                Toggle::make('arriendo')->label('Arriendo'),
                                                Toggle::make('venta')->label('Venta'),
                                                Toggle::make('destacado')->label('Destacado'),
                                                Toggle::make('iva')->label('IVA'),
                                            ])
                                            ->columnSpanFull(),
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
                                        TextInput::make('valor_arriendo')
                                            ->label('Valor arriendo')
                                            ->prefix('$')
                                            ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                                            ->stripCharacters('.')
                                            ->numeric()
                                            ->dehydrateStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : null),

                                        TextInput::make('valor_venta')
                                            ->label('Valor venta')
                                            ->prefix('$')
                                            ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                                            ->stripCharacters('.')
                                            ->numeric()
                                            ->dehydrateStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : null),

                                        TextInput::make('administracion')
                                            ->label('Valor administración')
                                            ->prefix('$')
                                            ->mask(RawJs::make('$money($input, \',\', \'.\')'))
                                            ->stripCharacters('.')
                                            ->numeric()
                                            ->dehydrateStateUsing(fn ($state) => $state ? (int) str_replace('.', '', $state) : null),
                                        Select::make('ciudad')
                                            ->label('Ciudad')
                                            ->relationship('ciudadRelacion', 'nombre')
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

                                        TextInput::make('area_construida')
                                            ->label('Área construida (m²)')
                                            ->numeric(),
                                        TextInput::make('no_alcobas')
                                            ->label('Número de alcobas')
                                            ->numeric(),
                                        TextInput::make('no_banos')
                                            ->label('Número de baños')
                                            ->numeric(),
                                        TextInput::make('no_closets')
                                            ->label('Número de closets'),
                                        TextInput::make('garajes')
                                            ->label('Número de parqueaderos')
                                            ->numeric(),
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
                                        Select::make('ubicacion')
                                            ->label('Ubicación')
                                            ->options([
                                                '0' => 'Interior',
                                                '1' => 'Exterior',
                                            ])
                                            ->placeholder('Ubicación'),
                                        Select::make('acceso')
                                            ->label('Acceso')
                                            ->options([
                                                '0' => 'Vehicular',
                                                '1' => 'Peatonal',
                                            ])
                                            ->placeholder('Seleccione el tipo de acceso')
                                            ->native(false),
                                        TextInput::make('unidad')
                                            ->label('No. Piso'),
                                        TextInput::make('no_bodega')
                                            ->label('Número de bodegas')
                                            ->numeric(),
                                        TextInput::make('no_oficina')
                                            ->label('Número de oficinas')
                                            ->numeric(),
                                        TextInput::make('no_salon')
                                            ->label('Número de salones')
                                            ->numeric(),
                                        Select::make('asesor')
                                            ->label('Asesor')
                                            ->options(
                                                Usuario::where('cargo', '!=', 1)
                                                    ->orderBy('nombres')      // ← Ordena por nombres
                                                    ->orderBy('apellidos')    // ← Luego apellidos
                                                    ->get()
                                                    ->mapWithKeys(fn ($user) => [
                                                        $user->cedula => $user->nombres . ' ' . $user->apellidos
                                                    ])
                                            )
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
                                        Toggle::make('estado')
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

                    Tab::make('Características')
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
                                            Toggle::make('parq_moto')->label('Parqueadero para motos'),
                                            Toggle::make('parq_comunal')->label('Parqueadero comunal'),
                                            Toggle::make('sala_comedor')->label('Sala Comedor'),

                                            Toggle::make('balcon')->label('Balcón'),

                                            Toggle::make('lobby')->label('Lobby'),

                                            Toggle::make('piscina')->label('Piscina'),

                                            Toggle::make('alcoba_servicio')->label('Alcoba de Servicio'),

                                            Toggle::make('zona_ropas')->label('Zona de Ropas'),

                                            Toggle::make('ascensor')->label('Ascensor'),

                                            Toggle::make('sauna')->label('Sauna'),

                                            Toggle::make('hall')->label('Hall TV'),

                                            Toggle::make('terraza')->label('Terraza'),

                                            Toggle::make('vigilancia')->label('Celaduría'),

                                            Toggle::make('turco')->label('Turco'),

                                            Toggle::make('estudio')->label('Estudio'),

                                            Toggle::make('salon_n')->label('Salón'),

                                            Toggle::make('juegos')->label('Juegos Infantiles'),

                                            Toggle::make('cancha')->label('Cancha'),

                                            Toggle::make('patio')->label('Patio'),

                                            Toggle::make('bodega')->label('Bodega'),

                                            Toggle::make('salon_social')->label('Salón Social'),

                                            Toggle::make('bbq')->label('BBQ'),

                                            Toggle::make('mirador')->label('Mirador'),

                                            Toggle::make('oficina')->label('Oficina'),

                                            Toggle::make('gimnasio')->label('Gimnasio'),

                                            Toggle::make('conjunto_cerrado')->label('Conjunto Cerrado'),

                                            Toggle::make('edificio')->label('Edificio'),

                                            Toggle::make('calentador')->label('Calentador'),

                                            Toggle::make('aire_acondicionado')->label('Aire Acondicionado'),
                                        ])
                                ])
                        ])
                        ->columnSpanFull(),



                    Tab::make('Fotografías y videos')
                        ->schema([
                                    Repeater::make('fotoInmueble') // Nombre del campo de relación en el modelo Inmueble
                                        ->relationship('fotoInmueble') // Nombre de la relación en el modelo Inmueble
                                        ->schema([
                                            FileUpload::make('foto') // Nombre del campo en el modelo FotoInmueble
                                                //->extraAttributes(['data-watermark-field' => 'foto'])
                                                ->image()
                                                //->hiddenLabel()
                                                ->disk('public')
                                                ->directory('fotos')
                                                ->visibility('public')
                                                ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                                    return Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();
                                                })
                                                ->imageEditor()
                                                ->required()
                                                ->dehydrated(fn ($state) => filled($state)) // No vacía el campo si ya tiene valor
                                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg']) // Restringe los tipos de archivo a imágenes
                                                ->validationMessages([
                                                    'required' => 'La foto es obligatoria.',
                                                    'mimetypes' => 'El archivo debe ser un formato de imagen válido (JPEG, PNG, JPG).',
                                                    'mimes' => 'Solo se permiten archivos con extensión .jpg, .jpeg o .png.',
                                                ]),

                                            // Campo oculto para la posición
                                            Hidden::make('posicion')
                                                ->dehydrated(true),
                                        ])
                                        ->grid(4) // Vista tipo galería
                                        ->orderColumn('posicion') // Mantiene el orden al editar
                                        ->addActionLabel('Agregar nueva foto')
                                        // Para que el encabezado de cada foto se vea limpio
                                        //->itemLabel(fn (array $state): ?string => "Posición: " . ($state['posicion'] ?? 'N/A')),

                        ])
                        ->columnSpanFull(),
                ])
                ->columnSpanFull()
        ]);

    }

}

