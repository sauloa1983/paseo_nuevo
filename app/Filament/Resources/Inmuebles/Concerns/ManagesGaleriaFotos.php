<?php

namespace App\Filament\Resources\Inmuebles\Concerns;

use App\Filament\Resources\Inmuebles\Schemas\InmuebleForm;
use App\Models\Inmueble;

trait ManagesGaleriaFotos
{
    /** @var array<int|string, mixed> */
    protected array $pendingGaleriaFotos = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if ($this->record instanceof Inmueble) {
            $data['galeria_fotos'] = InmuebleForm::galeriaPathsFromRecord($this->record);
        }

        return $data;
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->extractGaleriaFotosFromFormData($data);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->extractGaleriaFotosFromFormData($data);
    }

    protected function afterCreate(): void
    {
        $this->syncPendingGaleriaFotos();
    }

    protected function afterSave(): void
    {
        $this->syncPendingGaleriaFotos();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function extractGaleriaFotosFromFormData(array $data): array
    {
        $this->pendingGaleriaFotos = is_array($data['galeria_fotos'] ?? null)
            ? array_values($data['galeria_fotos'])
            : [];

        unset($data['galeria_fotos']);

        return $data;
    }

    protected function syncPendingGaleriaFotos(): void
    {
        if (! $this->record instanceof Inmueble) {
            return;
        }

        InmuebleForm::syncGaleriaFotos($this->record, $this->pendingGaleriaFotos);
    }
}
