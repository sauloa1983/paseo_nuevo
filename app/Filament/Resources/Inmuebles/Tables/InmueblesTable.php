<?php

namespace App\Filament\Resources\Inmuebles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Enums\RecordActionsPosition;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InmueblesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->extraAttributes(['class' => 'pe-inmuebles-table'])
            ->defaultPaginationPageOption(10)
            ->paginationPageOptions([10, 25, 50, 100])
            ->scrollToTopOnPageChange()
            ->recordActionsPosition(RecordActionsPosition::AfterColumns)
            ->columns([
                TextColumn::make('codigo')
                    ->label('Código')
                    ->numeric()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('estado')
                    ->color(fn ($state) => $state == 1 ? 'danger' : 'success')
                    ->formatStateUsing(fn ($state) => $state == 1 ? 'No Disponible' : 'Disponible')
                    ->sortable(),
                TextColumn::make('tipo_inmueble.tipo')
                    ->label('Tipo inmueble')
                    ->sortable(),
                IconColumn::make('arriendo')
                    ->boolean(),
                IconColumn::make('venta')
                    ->boolean(),
                IconColumn::make('destacado')
                    ->boolean(),
                TextColumn::make('fecha_captacion')
                    ->label('Fecha de captación')
                    ->date()
                    ->sortable(),
                TextColumn::make('valor_arriendo')
                    ->label('Valor de arriendo')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('valor_venta')
                    ->label('Valor de venta')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('ciudadRelacion.nombre')
                    ->label('Ciudad')
                    ->sortable(),
                TextColumn::make('barrio.nombre')
                    ->label('Barrio')
                    ->searchable(),

            ])
            ->defaultSort('codigo', 'desc')
            ->filters([
                SelectFilter::make('estado')
                    ->label('Estado')
                    ->options([
                        0 => 'Disponible',
                        1 => 'No Disponible',
                ]),
                SelectFilter::make('ciudadRelacion.id')
                    ->label('Ciudad')
                    ->relationship('ciudadRelacion', 'nombre')
                    ->searchable()
                    ->preload(),
                SelectFilter::make('tipo_fk')
                    ->label('Tipo inmueble')
                    ->relationship('tipo_inmueble', 'tipo')
                    ->searchable()
                    ->preload(),
                TernaryFilter::make('destacado')
                    ->label('Destacado'),
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->size('xl')
                    ->color('gray')
                    ->tooltip('Editar inmueble')
                    ->extraAttributes([
                        'class' => 'group hover:animate-bounce',
                    ]),
                DeleteAction::make()
                    ->color('danger')
                    ->icon('heroicon-m-trash')
                    ->modalIconColor('danger')
                    ->modalHeading('¿Eliminar inmueble?')
                    ->modalDescription('¿Está seguro de que desea eliminar este inmueble? Esta acción no se puede deshacer.')
                    ->iconButton()
                    ->size('xl')
                    ->modalSubmitAction(fn ($action) => $action->color('danger'))
                    ->modalCancelAction(fn ($action) => $action->color('gray'))
                    ->color('gray')
                    ->tooltip('Eliminar inmueble')
                    ->extraAttributes([
                        'class' => 'group hover:animate-bounce',
                    ])
                    ->successNotification(
                        Notification::make()
                            ->danger()
                            ->title('Inmueble eliminado')
                            ->body('El inmueble se eliminó correctamente.'),
                    )
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
