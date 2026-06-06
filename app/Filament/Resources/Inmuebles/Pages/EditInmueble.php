<?php

namespace App\Filament\Resources\Inmuebles\Pages;

use App\Filament\Resources\Inmuebles\Concerns\ManagesGaleriaFotos;
use App\Filament\Resources\Inmuebles\InmuebleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditInmueble extends EditRecord
{
    use ManagesGaleriaFotos;
    protected static string $resource = InmuebleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make()
                ->label('Eliminar Inmueble')
                ->icon('heroicon-m-trash'),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Inmueble actualizado')
            ->body('El inmueble se actualizó correctamente.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Actualizar Inmueble')
                ->icon('heroicon-m-check-circle'),

            $this->getCancelFormAction()
                ->label('Cancelar'),
        ];
    }


}
