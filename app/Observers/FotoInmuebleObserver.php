<?php

namespace App\Observers;

use App\Models\FotoInmueble;
use Illuminate\Support\Facades\Storage;

class FotoInmuebleObserver
{
    /**
     * Handle the FotoInmueble "created" event.
     */
    public function created(FotoInmueble $fotoInmueble): void
    {
        //
    }

    /**
     * Handle the FotoInmueble "updated" event.
     */
    public function updated(FotoInmueble $fotoInmueble): void
    {
        //
    }

    /**
     * Handle the FotoInmueble "deleted" event.
     */
    public function deleted(FotoInmueble $fotoInmueble): void
    {
        if ($fotoInmueble->foto) {
            // Recuerda que el accessor ya añade "fotos/",
            // asegúrate de que la ruta sea correcta para el disco
            Storage::disk('public')->delete($fotoInmueble->foto);
        }
    }
    /**
     * Handle the FotoInmueble "restored" event.
     */
    public function restored(FotoInmueble $fotoInmueble): void
    {
        //
    }

    /**
     * Handle the FotoInmueble "force deleted" event.
     */
    public function forceDeleted(FotoInmueble $fotoInmueble): void
    {
        //
    }
}
