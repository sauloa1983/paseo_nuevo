<?php

namespace App\Filament\Pages\Auth;

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
}
