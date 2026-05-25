<?php

namespace App\Filament\Resources\Usuarios\Tables;

use App\Models\Usuario;
use Dom\Text;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class UsuariosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->query(
                Usuario::query()->where('cargo', '!=', 1)  // Asegúrate de cargar la relación 'cargos'
            )
            ->columns([
                // Define tus columnas aquí, por ejemplo:
                TextColumn::make('cedula')->label('Cédula')->sortable(),
                TextColumn::make('nombres')->label('Nombres')->sortable()->searchable(),
                TextColumn::make('apellidos')->label('Apellidos')->searchable(),
                TextColumn::make('telefonos')->label('Teléfonos'),
                TextColumn::make('email')->label('Email'),
                TextColumn::make('cargos.nombre')->label('Cargo'),
                TextColumn::make('vigente')
                    ->label('Estado')
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'Activo' : 'Inactivo'),
            ])
            ->filters([
                SelectFilter::make('cargo')
                    ->label('Cargo')
                    ->options(
                        Usuario::query()
                            ->where('cargo', '!=', 1)
                            ->with('cargos')  // Asegúrate de cargar la relación 'cargos'
                            ->get()
                            ->pluck('cargos.nombre', 'cargo')
                    ),
                SelectFilter::make('vigente')
                    ->label('Estado')
                    ->options([
                        1 => 'Activo',
                        0 => 'Inactivo',
                    ]),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->size('xl')
                    ->color('gray')
                    ->tooltip('Editar usuario')
                    ->extraAttributes([
                        'class' => 'group hover:animate-bounce',
                    ]),
                DeleteAction::make()
                    ->color('danger')
                    ->icon('heroicon-m-trash')
                    ->modalIconColor('danger')
                    ->modalHeading('¿Eliminar usuario?')
                    ->modalDescription('¿Está seguro de que desea eliminar este usuario? Esta acción no se puede deshacer.')
                    ->iconButton()
                    ->size('xl')
                    ->modalSubmitAction(fn ($action) => $action->color('danger'))
                    ->modalCancelAction(fn ($action) => $action->color('gray'))
                    ->color('gray')
                    ->tooltip('Eliminar usuario')
                    ->extraAttributes([
                        'class' => 'group hover:animate-bounce',
                    ])
                    ->successNotification(
                        Notification::make()
                            ->danger()
                            ->title('Usuario eliminado')
                            ->body('El usuario se eliminó correctamente.'),
                    ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
