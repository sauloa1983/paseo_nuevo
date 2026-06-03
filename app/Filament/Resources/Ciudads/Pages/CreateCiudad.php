<?php

namespace App\Filament\Resources\Ciudads\Pages;

use App\Filament\Resources\Ciudads\CiudadResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCiudad extends CreateRecord
{
    protected static string $resource = CiudadResource::class;

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Ciudad creada')
            ->body('La ciudad se creó correctamente.');

    }

    protected function getRedirectUrl(): string
    {
        if ($this->getRecord()?->has_office) {
            return $this->getResource()::getUrl('edit', ['record' => $this->getRecord()]);
        }

        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Crear Ciudad')
                ->icon('heroicon-m-check-circle')
                ->extraAttributes(['class' => 'btn-verde-custom']),

            $this->getCancelFormAction()
                ->label('Cancelar')
                ->extraAttributes(['class' => 'btn-blanco-custom']),
        ];
    }
}

