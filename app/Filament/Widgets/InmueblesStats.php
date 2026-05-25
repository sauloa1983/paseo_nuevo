<?php

namespace App\Filament\Widgets;

use App\Models\Inmueble;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InmueblesStats extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Inmuebles totales', Inmueble::count()),
            Stat::make('En arriendo', Inmueble::where('arriendo', '1')->count()),
            Stat::make('En venta', Inmueble::where('venta', '1')->count()),

            /*Stat::make('Inmuebles totales', Inmueble::count())
                ->description('Registrados en el sistema')
                ->descriptionIcon('heroicon-m-home')
                ->color('success')
                ->chart([7, 3, 5, 8, 10, 12, 15]),*/
        ];
    }
}
