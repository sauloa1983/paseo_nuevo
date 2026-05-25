<?php

namespace App\Filament\Widgets;

use App\Models\Inmueble;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class InmueblesMunicipiosStats extends StatsOverviewWidget
{
    protected ?string $heading = 'Inmuebles por Municipio';

    //protected string $view = 'filament.widgets.InmueblesMunicipiosStats';

    protected function getStats(): array
    {
        $stats = [];

        $municipios = Inmueble::query()
            ->where('estado', 0)
            ->join('ciudades', 'inmuebles.ciudad', '=', 'ciudades.id')
            ->select('ciudades.nombre as ciudad')
            ->selectRaw('COUNT(*) as total')
            ->selectRaw("SUM(CASE WHEN arriendo = '1' THEN 1 ELSE 0 END) as arriendo")
            ->selectRaw("SUM(CASE WHEN venta = '1' THEN 1 ELSE 0 END) as venta")
            ->groupBy('ciudades.nombre')
            ->orderBy('ciudades.nombre')
            ->get();

        return $municipios->map(function ($municipio) {
            return Stat::make($municipio->ciudad, $municipio->total)
                ->description("Arriendo: {$municipio->arriendo} | Venta: {$municipio->venta}")
                ->descriptionIcon('heroicon-m-home')
                ->color('primary');
        })->toArray();

        return $stats;
    }
}
