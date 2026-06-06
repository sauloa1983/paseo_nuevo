<?php

namespace App\Models\Concerns;

trait PreventsDeletionWhenLinkedToInmuebles
{
    public function cantidadInmueblesAsignados(): int
    {
        return $this->inmueble()->count();
    }

    public function motivoNoEliminable(): ?string
    {
        $cantidad = $this->cantidadInmueblesAsignados();

        if ($cantidad === 0) {
            return null;
        }

        return "Tiene {$cantidad} inmueble(s) asociados. Reasigne esos inmuebles antes de eliminar este registro.";
    }
}
