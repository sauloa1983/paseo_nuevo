<?php

namespace App\Filament\Resources\Ciudads\Pages;

use App\Filament\Resources\Ciudads\CiudadResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCiudads extends ListRecords
{
    protected static string $resource = CiudadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nueva Ciudad')
                ->icon('heroicon-o-plus'),
        ];
    }
}
