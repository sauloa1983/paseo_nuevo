<?php

namespace App\Filament\Resources\Usuarios\Schemas;

use App\Models\Cargo;
use App\Models\User;
use App\Models\Usuario;
use Dom\Text;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Tabs;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Grid;
use Filament\Forms\Components\View;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\View as ComponentsView;

class UsuariosForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
        ->components([
            Tabs::make('Formulario inmueble')
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Información general')
                        ->schema([
                            Grid::make(3)
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
                                        ])  ,

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
                                        ->maxLength(255),

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
        ]);
    }
}
