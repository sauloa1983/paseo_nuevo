<?php

namespace App\Filament\Pages\Auth;

use App\Filament\Resources\Usuarios\Schemas\UsuariosForm;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Filament\Auth\Pages\Login as BaseLogin;

class Login extends BaseLogin
{
    //protected string $view = 'filament-panels::pages.auth.login';

    public function getHeading(): string
    {
        return 'Acceso Administrativo';
    }

    public function getSubheading(): ?string
    {
        return 'Ingresa al panel de administración';
    }

    /**
     * Tras un login exitoso, marca la sesión si el usuario ingresó
     * con la contraseña por defecto para forzar el cambio obligatorio.
     */
    public function authenticate(): ?LoginResponse
    {
        $plainPassword = $this->form->getState()['password'] ?? '';

        $response = parent::authenticate();

        if ($response !== null && $plainPassword === UsuariosForm::DEFAULT_PASSWORD) {
            session(['clave_por_defecto' => true]);
        }

        return $response;
    }
}
