<?php

namespace App\Filament\Resources\Testimonials\Pages;

use App\Filament\Resources\Testimonials\TestimonialResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;
use Filament\Notifications\Notification;

class ManageTestimonials extends ManageRecords
{
    protected static string $resource = TestimonialResource::class;

    protected function getHeaderActions(): array
    {
        return [
            //CreateAction::make(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Testimonio editado')
            ->body('El testimonio se edito correctamente.');
    }
}
