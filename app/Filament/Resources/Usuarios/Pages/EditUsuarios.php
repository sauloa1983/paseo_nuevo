<?php

namespace App\Filament\Resources\Usuarios\Pages;

use App\Filament\Resources\Usuarios\Tables\UsuariosTable;
use App\Filament\Resources\Usuarios\UsuariosResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditUsuarios extends EditRecord
{
    protected static string $resource = UsuariosResource::class;

    protected function getHeaderActions(): array
    {
        return [
            UsuariosTable::makeDeleteAction()
                ->label('Eliminar Usuario')
                ->icon('heroicon-m-trash'),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Usuario actualizado')
            ->body('El usuario se actualizó correctamente.');
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()
                ->label('Actualizar Usuario')
                ->icon('heroicon-m-check-circle'),

            $this->getCancelFormAction()
                ->label('Cancelar'),
        ];
    }
}
