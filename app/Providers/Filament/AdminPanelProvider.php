<?php

namespace App\Providers\Filament;

use App\Http\Middleware\EnsureDefaultPasswordChanged;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Illuminate\Support\Facades\Auth;
use Filament\Actions\Action;
use Filament\Navigation\MenuItem;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;
use Filament\Enums\ThemeMode;
use Filament\View\PanelsRenderHook;


class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        $panelDomain = panel_domain();
        $useSubdomain = filled($panelDomain);

        $panel = $panel
            ->defaultThemeMode(ThemeMode::Light)
            ->darkMode(false) // sin toggle oscuro
            ->colors([
                'primary' => Color::Red,
            ])
            ->default()
            ->id('admin')
            ->path($useSubdomain ? '' : 'pe-panel')
            ->loginRouteSlug('acceso')
            ->login(\App\Filament\Pages\Auth\Login::class);

        if ($useSubdomain) {
            $panel = $panel->domain($panelDomain);
        }

        return $panel
            ->authGuard('web')       // ✅ Usa guard web (tabla usuarios)
            ->authPasswordBroker('users')  // ✅ Usa provider users
            ->brandName('Paseo España Inmobiliaria')
            ->brandLogo(asset('images/logo.png'))
            ->brandLogoHeight('4rem')
            ->favicon(asset('favicon.ico'))
            ->globalSearch(false)
            ->sidebarCollapsibleOnDesktop()
            ->collapsedSidebarWidth('5.75rem')
            ->maxContentWidth(Width::Full) // Muestra el contenido al 100% del ancho
            ->renderHook(
                PanelsRenderHook::TOPBAR_LOGO_BEFORE,
                fn (): string => view('filament.partials.admin-topbar-brand')->render()
            )
            ->renderHook(
                PanelsRenderHook::SIDEBAR_LOGO_BEFORE,
                fn (): string => view('filament.partials.admin-topbar-brand')->render()
            )

            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                EnsureDefaultPasswordChanged::class,
            ])
            ->plugins([
                FilamentEditProfilePlugin::make()
                    ->slug('edit-profile')
                    ->setTitle('Mi Perfil')
                    ->shouldRegisterNavigation(false),
            ])
            ->userMenuItems([
                // Reemplaza el link de perfil existente
                'profile' => fn (Action $action) => $action
                    ->label(fn () => Auth::user()?->name ?? 'Perfil')
                    ->url(fn (): string => EditProfilePage::getUrl())
                    ->icon('heroicon-o-user-circle')
                    ->color('gray'),
            ])
            //Elimina los marcos del panel principal
            ->renderHook(
                'panels::body.start',
                fn (): string => \Illuminate\Support\Facades\Blade::render('
                    <style>
                        .fi-section {
                            border-radius: 14px !important;
                            border: 0px solid #e5e7eb !important;
                            box-shadow: none !important;
                        }
                    </style>
                ')
            );
    }
}
