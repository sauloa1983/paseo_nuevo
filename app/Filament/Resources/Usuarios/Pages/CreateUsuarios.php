<?php

namespace App\Filament\Resources\Usuarios\Pages;

use App\Filament\Resources\Usuarios\UsuariosResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateUsuarios extends CreateRecord
{
    protected static string $resource = UsuariosResource::class;

    public function getTitle(): string
    {
        return 'Nuevo Usuario';
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Usuario creado')
            ->body('El usuario se creó correctamente.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getCreateFormAction()
                ->label('Crear Usuario')
                ->icon('heroicon-m-check-circle')
                ->extraAttributes(['class' => 'btn-verde-custom']),

            $this->getCancelFormAction()
                ->label('Cancelar')
                ->extraAttributes(['class' => 'btn-blanco-custom']),
        ];
    }
}
