<?php

namespace App\Filament\Resources\Barrios;

use App\Filament\Resources\Barrios\Pages\ManageBarrios;
use App\Filament\Support\InmuebleLinkedDeleteGuard;
use App\Models\Barrio;
use App\Models\Ciudad;
use BackedEnum;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Validation\Rules\Unique;
use Filament\Notifications\Notification;

class BarrioResource extends Resource
{
    protected static ?string $model = Barrio::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;

    protected static ?string $recordTitleAttribute = 'Barrios';

    protected static bool $canCreateAnother = false;

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([

                Select::make('ciudad_fk')
                        ->label('Ciudad')
                        ->options(Ciudad::pluck('nombre', 'id'))  // Todas las ciudades
                        ->required()
                        //->searchable()
                        ->preload()
                        ->placeholder('Selecciona ciudad...')
                        ->validationMessages([
                            'required' => 'El campo ciudad es obligatorio.',
                            'exists' => 'La ciudad seleccionada no es válida.',
                        ]),
                    TextInput::make('nombre')
                        ->label('Barrio')
                        ->required()
                        ->maxLength(100)
                        ->unique(
                            table: Barrio::class,
                            column: 'nombre',
                            ignoreRecord: true,
                            modifyRuleUsing: function (Unique $rule, Get $get) {
                                return $rule->where('ciudad_fk', $get('ciudad_fk'));
                            }
                        )
                        ->validationMessages([
                            'required' => 'El campo barrio es obligatorio.',
                            'max' => 'El nombre del barrio no puede exceder los 100 caracteres.',
                            'unique' => 'Este barrio ya existe en la ciudad seleccionada. Por favor ingrese uno diferente.',
                        ])
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('Barrios')
            ->columns([
                TextColumn::make('nombre')
                    ->label('Barrio')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('municipio.nombre')
                    ->label('Municipio')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('nombre', 'asc')
            ->filters([
                SelectFilter::make('ciudad_fk')
                    ->label('Ciudad')
                    ->options(Ciudad::pluck('nombre', 'id'))
                    ->searchable()
                    ->placeholder('Selecciona ciudad...'),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->size('xl')
                    ->color('gray')
                    ->tooltip('Editar barrio')
                    ->modalWidth('md')
                    ->extraAttributes([
                        'class' => 'group hover:animate-bounce',
                    ])
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Barrio actualizado correctamente')
                            ->body('Los cambios se guardaron con éxito.'),
                    ),
                InmuebleLinkedDeleteGuard::wrapDeleteAction(
                    DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-m-trash')
                        ->modalIconColor('danger')
                        ->modalHeading('¿Eliminar barrio?')
                        ->modalDescription('¿Está seguro de que desea eliminar este barrio? Esta acción no se puede deshacer.')
                        ->iconButton()
                        ->size('xl')
                        ->modalSubmitAction(fn ($action) => $action->color('danger'))
                        ->modalCancelAction(fn ($action) => $action->color('gray'))
                        ->color('gray')
                        ->tooltip('Eliminar barrio')
                        ->extraAttributes([
                            'class' => 'group hover:animate-bounce',
                        ])
                        ->successNotification(
                            Notification::make()
                                ->danger()
                                ->title('Barrio eliminado')
                                ->body('El barrio se eliminó correctamente.'),
                        ),
                    'No se puede eliminar el barrio',
                ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    InmuebleLinkedDeleteGuard::wrapDeleteBulkAction(
                        DeleteBulkAction::make(),
                        'No se pueden eliminar algunos barrios',
                    ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageBarrios::route('/'),
        ];
    }
}
