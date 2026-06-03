<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Helpers/Functions.php');

        $this->app->bind('path.public', function() {
            return base_path('../public_html');
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Blade::directive('precio', function ($expression) {
            return "<?php echo number_format(str_replace('.', '', $expression), 0, ',', '.'); ?>";
        });

        FilamentView::registerRenderHook(
            PanelsRenderHook::HEAD_END,
            fn (): string => view('filament.custom-styles')->render()
        );

        /*FilamentAsset::register([
            Js::make('filament-watermark', resource_path('js/filament-watermark.js')),
        ]);*/

        \App\Models\FotoInmueble::observe(\App\Observers\FotoInmuebleObserver::class);

    }
}
