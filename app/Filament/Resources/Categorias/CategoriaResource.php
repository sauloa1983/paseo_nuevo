<?php

namespace App\Filament\Resources\Categorias;

use App\Filament\Resources\Categorias\Pages\ManageCategorias;
use App\Filament\Support\InmuebleLinkedDeleteGuard;
use App\Models\Categoria;
use App\Models\TipoInmueble;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class CategoriaResource extends Resource
{
    protected static ?string $model = TipoInmueble::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $recordTitleAttribute = 'exit';

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                TextInput::make('tipo')
                    ->label('Tipo de Inmuebles')
                    ->required()
                    ->maxLength(50)
                    ->unique(TipoInmueble::class, 'tipo', ignoreRecord: true)
                    ->placeholder('Ej: Apartamento, Casa, Local, etc.')
                    //->helperText('Ingrese un tipo de inmueble único.')
                    ->validationMessages([
                        'required' => 'El campo tipo de inmueble es obligatorio.',
                        'maxLength' => 'El tipo de inmueble no puede exceder los 50 caracteres.',
                        'unique' => 'Este tipo de inmueble ya existe. Por favor ingrese uno diferente.',
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('tipo')
            ->columns([
                TextColumn::make('tipo')
                    ->label('Tipo de Inmueble')
                    ->sortable()
                    ->searchable(),
            ])
            ->defaultSort('tipo', 'asc')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->size('xl')
                    ->color('gray')
                    ->tooltip('Editar tipo de inmueble')
                    ->modalWidth('md')
                    ->extraAttributes([
                        'class' => 'group hover:animate-bounce',
                    ])
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Tipo de inmueble actualizado')
                            ->body('El tipo de inmueble se actualizó correctamente.'),
                    ),
                InmuebleLinkedDeleteGuard::wrapDeleteAction(
                    DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-m-trash')
                        ->modalIconColor('danger')
                        ->modalHeading('¿Eliminar tipo de inmueble?')
                        ->modalDescription('¿Está seguro de que desea eliminar este tipo de inmueble? Esta acción no se puede deshacer.')
                        ->iconButton()
                        ->size('xl')
                        ->modalSubmitAction(fn ($action) => $action->color('danger'))
                        ->modalCancelAction(fn ($action) => $action->color('gray'))
                        ->color('gray')
                        ->tooltip('Eliminar tipo de inmueble')
                        ->extraAttributes([
                            'class' => 'group hover:animate-bounce',
                        ])
                        ->successNotification(
                            Notification::make()
                                ->danger()
                                ->title('Tipo de inmueble eliminado')
                                ->body('El tipo de inmueble se eliminó correctamente.'),
                        ),
                    'No se puede eliminar el tipo de inmueble',
                ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    InmuebleLinkedDeleteGuard::wrapDeleteBulkAction(
                        DeleteBulkAction::make(),
                        'No se pueden eliminar algunos tipos de inmueble',
                        'tipo',
                    ),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ManageCategorias::route('/'),
        ];
    }
}
