<?php

namespace App\Filament\Resources\Categorias\Pages;

use App\Filament\Resources\Categorias\CategoriaResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageCategorias extends ManageRecords
{
    protected static string $resource = CategoriaResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Tipo de Inmueble')
                ->icon('heroicon-o-plus')
                ->createAnother(false)
                ->modalWidth('md')
                ->modalSubmitActionLabel('Crear Tipo de Inmueble')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Tipo de Inmueble creado')
                        ->body('El tipo de inmueble se creó correctamente.'),
                ),
        ];
    }
}
