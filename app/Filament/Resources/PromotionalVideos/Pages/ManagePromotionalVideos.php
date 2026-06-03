<?php

namespace App\Filament\Resources\PromotionalVideos\Pages;

use App\Filament\Resources\PromotionalVideos\PromotionalVideoResource;
use App\Filament\Resources\PromotionalVideos\Tables\PromotionalVideosTable;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManagePromotionalVideos extends ManageRecords
{
    protected static string $resource = PromotionalVideoResource::class;

    public function getTitle(): string
    {
        return 'Videos promocionales';
    }

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Video')
                ->icon('heroicon-o-plus')
                ->modalHeading('Nuevo video promocional')
                ->modalWidth('md')
                ->modalSubmitActionLabel('Guardar')
                ->modalCancelActionLabel('Cancelar')
                ->createAnother(false)
                ->mutateFormDataUsing(fn (array $data): array => PromotionalVideosTable::mergeDefaults($data)),
        ];
    }
}
