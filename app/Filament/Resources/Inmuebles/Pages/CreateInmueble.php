<?php

namespace App\Filament\Resources\Inmuebles\Pages;

use App\Filament\Resources\Inmuebles\Concerns\ManagesGaleriaFotos;
use App\Filament\Resources\Inmuebles\InmuebleResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateInmueble extends CreateRecord
{
    use ManagesGaleriaFotos;
    protected static string $resource = InmuebleResource::class;

    public function getTitle(): string
    {
        return 'Nuevo inmueble';
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Inmueble creado')
            ->body('El inmueble se creó correctamente.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Crear Inmueble')
                ->icon('heroicon-m-check-circle')
                ->extraAttributes(['class' => 'btn-verde-custom']),

            $this->getCancelFormAction()
                ->label('Cancelar')
                ->extraAttributes(['class' => 'btn-blanco-custom']),
        ];
    }
}
