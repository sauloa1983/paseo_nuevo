<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Carbon;

trait HasEtiquetasComerciales
{
    public const BADGE_STATUS_OPCIONES = [
        'OPORTUNIDAD' => 'Oportunidad',
        'NEGOCIABLE' => 'Negociable',
        'BAJO_DE_PRECIO' => 'Bajo de Precio',
    ];

    protected function disponibilidadTexto(): Attribute
    {
        return Attribute::make(
            get: function (): ?string {
                if (blank($this->contract_end_date)) {
                    return null;
                }

                $fechaFin = Carbon::parse($this->contract_end_date)->startOfDay();
                $hoy = Carbon::today();

                if ($fechaFin->lte($hoy)) {
                    return null;
                }

                $diasRestantes = $hoy->diffInDays($fechaFin);

                if ($diasRestantes > 30) {
                    return 'Disponible próximamente';
                }

                if ($diasRestantes <= 7) {
                    return $diasRestantes <= 3
                        ? 'Disponible pronto'
                        : 'Disponible en 1 semana';
                }

                $semanas = (int) round($diasRestantes / 7);
                $semanas = max(2, min(4, $semanas));

                return "Disponible en {$semanas} semanas";
            },
        );
    }

    protected function badgeStatusEtiqueta(): Attribute
    {
        return Attribute::make(
            get: fn (): ?string => filled($this->badge_status)
                ? (self::BADGE_STATUS_OPCIONES[$this->badge_status] ?? null)
                : null,
        );
    }
}
