<?php

namespace App\Filament\Resources\Inmuebles\Pages;

use App\Filament\Resources\Inmuebles\InmuebleResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListInmuebles extends ListRecords
{
    protected static string $resource = InmuebleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Nuevo Inmueble')
                ->icon('heroicon-o-plus'),
        ];
    }
}
