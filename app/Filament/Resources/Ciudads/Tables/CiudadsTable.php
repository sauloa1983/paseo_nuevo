<?php

namespace App\Filament\Resources\Ciudads\Tables;

use App\Filament\Support\InmuebleLinkedDeleteGuard;
use App\Filament\Support\StandardTable;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Notifications\Notification;

class CiudadsTable
{
    public static function configure(Table $table): Table
    {
        return StandardTable::configure($table)
            ->columns([
                TextColumn::make('nombre')
                    ->label('Ciudad')
                    ->searchable()
                    ->sortable(),

                ToggleColumn::make('has_office')
                    ->label('¿Tiene Oficina?'),

                IconColumn::make('visible_buscador')
                    ->label('Visible en buscador')
                    ->boolean(),

                TextColumn::make('whatsapp_arriendo')
                    ->label('WA Arriendos')
                    ->placeholder('—')
                    ->toggleable(),

                TextColumn::make('whatsapp_venta')
                    ->label('WA Ventas')
                    ->placeholder('—')
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make()
                    ->icon('heroicon-m-pencil-square')
                    ->iconButton()
                    ->size('xl')
                    ->color('gray')
                    ->tooltip('Editar ciudad')
                    ->extraAttributes([
                        'class' => 'group hover:animate-bounce',
                    ]),
                InmuebleLinkedDeleteGuard::wrapDeleteAction(
                    DeleteAction::make()
                        ->color('danger')
                        ->icon('heroicon-m-trash')
                        ->modalIconColor('danger')
                        ->modalHeading('¿Eliminar ciudad?')
                        ->modalDescription('¿Está seguro de que desea eliminar esta ciudad? Esta acción no se puede deshacer.')
                        ->iconButton()
                        ->size('xl')
                        ->modalSubmitAction(fn ($action) => $action->color('danger'))
                        ->modalCancelAction(fn ($action) => $action->color('gray'))
                        ->color('gray')
                        ->tooltip('Eliminar ciudad')
                        ->extraAttributes([
                            'class' => 'group hover:animate-bounce',
                        ])
                        ->successNotification(
                            Notification::make()
                                ->danger()
                                ->title('Ciudad eliminada')
                                ->body('La ciudad se eliminó correctamente.'),
                        ),
                    'No se puede eliminar la ciudad',
                ),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    InmuebleLinkedDeleteGuard::wrapDeleteBulkAction(
                        DeleteBulkAction::make(),
                        'No se pueden eliminar algunas ciudades',
                    ),
                ]),
            ]);
    }
}
