<?php

namespace App\Http\Middleware;

use App\Filament\Pages\Auth\CambiarClave;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Equivalente a verificar_seguridad.php: bloquea la navegación del panel
 * mientras el usuario no haya cambiado la contraseña por defecto.
 */
class EnsureDefaultPasswordChanged
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $next($request);
        }

        if (session('clave_por_defecto') !== true) {
            return $next($request);
        }

        if ($request->routeIs([
            CambiarClave::getRouteName(),
            'filament.admin.auth.logout',
        ])) {
            return $next($request);
        }

        return redirect()->to(CambiarClave::getUrl());
    }
}
