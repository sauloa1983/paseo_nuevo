<?php

namespace App\Filament\Resources\Ciudads\Pages;

use App\Filament\Resources\Ciudads\CiudadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditCiudad extends EditRecord
{
    protected static string $resource = CiudadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Eliminar Ciudad')
                ->icon('heroicon-m-trash'),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ciudad actualizada')
            ->body('La ciudad se actualizó correctamente.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Actualizar Ciudad')
                ->icon('heroicon-m-check-circle'),

            $this->getCancelFormAction()
                ->label('Cancelar'),
        ];
    }
}
