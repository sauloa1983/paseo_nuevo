<?php

namespace App\Filament\Resources\Barrios\Pages;

use App\Filament\Resources\Barrios\BarrioResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageBarrios extends ManageRecords
{

    protected static string $resource = BarrioResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Barrio')
                ->icon('heroicon-o-plus')
                ->createAnother(false)
                ->modalWidth('md')
                ->modalSubmitActionLabel('Crear Barrio')
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Barrio creado')
                        ->body('El barrio se creó correctamente.'),
                ),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Barrio guardado')
            ->body('El barrio se guardó correctamente.');
    }

}
