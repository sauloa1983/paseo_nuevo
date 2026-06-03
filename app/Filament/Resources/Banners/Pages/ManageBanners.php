<?php

namespace App\Filament\Resources\Banners\Pages;

use App\Filament\Resources\Banners\BannerResource;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ManageRecords;

class ManageBanners extends ManageRecords
{
    protected static string $resource = BannerResource::class;

    public function getTitle(): string
    {
        return 'Banners publicitarios';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Agregar banner')
                ->icon('heroicon-o-photo')
                ->color('primary')
                ->modalHeading('Nuevo banner publicitario')
                ->modalDescription('Sube la imagen y, si lo deseas, define el enlace de redirección. Solo puede haber un banner activo a la vez.')
                ->modalWidth('lg')
                ->modalSubmitActionLabel('Guardar banner')
                ->modalCancelActionLabel('Cancelar')
                ->createAnother(false)
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Banner creado')
                        ->body('El banner se registró correctamente.'),
                ),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Banner actualizado')
            ->body('Los cambios se guardaron correctamente.');
    }
}
