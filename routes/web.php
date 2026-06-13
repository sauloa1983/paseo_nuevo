<?php

use App\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\PropertyContactController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\TestimonialController;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Mail;

if (! function_exists('mainSiteHosts')) {
    /**
     * Dominios del sitio público (no incluye el subdominio del panel).
     *
     * @return list<string>
     */
    function mainSiteHosts(): array
    {
        $hosts = array_filter([
            parse_url((string) config('app.url'), PHP_URL_HOST) ?: null,
            env('APP_DOMAIN') ?: null,
        ]);

        return array_values(array_unique($hosts));
    }
}

if (! function_exists('syncPublicStorageTree')) {
    function syncPublicStorageTree(string $source, string $destination): int
    {
        if (! is_dir($source)) {
            return 0;
        }

        if (! is_dir($destination)) {
            mkdir($destination, 0755, true);
        }

        $copied = 0;

        foreach (scandir($source) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            $from = $source . DIRECTORY_SEPARATOR . $entry;
            $to = $destination . DIRECTORY_SEPARATOR . $entry;

            if (is_dir($from)) {
                $copied += syncPublicStorageTree($from, $to);

                continue;
            }

            if (! is_file($to)) {
                copy($from, $to);
                $copied++;
            }
        }

        return $copied;
    }
}

if (! function_exists('ensurePublicStorageLink')) {
    function ensurePublicStorageLink(): string
    {
        $publicStorage = public_storage_root();
        $legacyStorage = storage_path('app/public');

        if (! is_dir($publicStorage)) {
            mkdir($publicStorage, 0755, true);
        }

        foreach (['fotos', 'banners', 'ciudades', 'usuarios'] as $subdir) {
            $path = $publicStorage . DIRECTORY_SEPARATOR . $subdir;

            if (! is_dir($path)) {
                mkdir($path, 0755, true);
            }
        }

        $copied = syncPublicStorageTree($legacyStorage, $publicStorage);

        $legacyPublicFotos = public_path('fotos');

        if (is_dir($legacyPublicFotos)) {
            $copied += syncPublicStorageTree(
                $legacyPublicFotos,
                $publicStorage . DIRECTORY_SEPARATOR . 'fotos',
            );
        }

        $fotosDir = $publicStorage . DIRECTORY_SEPARATOR . 'fotos';
        $fotosCount = 0;

        if (is_dir($fotosDir)) {
            $fotosCount = count(array_filter(
                scandir($fotosDir) ?: [],
                static fn (string $entry): bool => $entry !== '.' && $entry !== '..' && is_file($fotosDir . DIRECTORY_SEPARATOR . $entry),
            ));
        }

        $lines = [
            'public_html: ' . resolve_public_html_path(),
            'storage: ' . $publicStorage,
            'fotos en disco: ' . $fotosCount,
            'APP_URL: ' . config('app.url'),
            'FOTOS_BASE_URL: ' . (config('app.fotos_base_url') ?: '(no definido)'),
        ];

        if ($copied > 0) {
            $lines[] = "copiados: {$copied} archivo(s) desde storage/app/public";
        }

        return implode("\n", $lines);
    }
}

if (! function_exists('publicStorageDiagnostics')) {
    function publicStorageDiagnostics(bool $fullAudit = false): string
    {
        $publicHtml = resolve_public_html_path();
        $storageRoot = public_storage_root();
        $fotosDir = $storageRoot . DIRECTORY_SEPARATOR . 'fotos';
        $sampleFiles = [];

        if (is_dir($fotosDir)) {
            foreach (scandir($fotosDir) ?: [] as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }

                if (is_file($fotosDir . DIRECTORY_SEPARATOR . $entry)) {
                    $sampleFiles[] = $entry;
                }

                if (count($sampleFiles) >= 3) {
                    break;
                }
            }
        }

        $dbSample = \App\Models\FotoInmueble::query()
            ->orderByDesc('id')
            ->value('foto');

        $totalRegistros = \App\Models\FotoInmueble::query()->count();

        $watermarkLine = 'watermark.png: NO — sube public/images/watermark.png a public_html/images/';

        foreach ([
            public_path('images/watermark.png'),
            resolve_public_html_path() . '/images/watermark.png',
            base_path('public/images/watermark.png'),
        ] as $watermarkPath) {
            if (is_file($watermarkPath)) {
                $watermarkLine = 'watermark.png: si (' . $watermarkPath . ')';
                break;
            }
        }

        $lines = [
            '=== Diagnóstico storage ===',
            'Host: ' . (request()->getHost() ?: '?'),
            'public_html: ' . $publicHtml,
            'storage root: ' . $storageRoot,
            'storage existe: ' . (is_dir($storageRoot) ? 'si' : 'no'),
            'fotos/ existe: ' . (is_dir($fotosDir) ? 'si' : 'no'),
            'fotos/ escribible: ' . (is_dir($fotosDir) && is_writable($fotosDir) ? 'si' : 'NO — chmod 755 o 775'),
            $watermarkLine,
            'disco public (config): ' . config('filesystems.disks.public.root'),
            'APP_URL: ' . config('app.url'),
            'FOTOS_BASE_URL: ' . (config('app.fotos_base_url') ?: '(no definido)'),
            'FILAMENT_DOMAIN: ' . (config('app.panel_domain') ?: '(no definido)'),
            'PUBLIC_HTML_PATH env: ' . (env('PUBLIC_HTML_PATH') ?: '(no definido)'),
            'Registros en fotos_inmuebles: ' . $totalRegistros,
        ];

        if ($sampleFiles !== []) {
            $lines[] = '';
            $lines[] = 'Archivos de ejemplo en fotos/:';

            foreach ($sampleFiles as $filename) {
                $diskPath = 'fotos/' . $filename;
                $lines[] = '  - ' . $filename;
                $lines[] = '    existe: ' . (public_storage_file_exists($diskPath) ? 'si' : 'no');
                $lines[] = '    url: ' . (public_storage_url($diskPath) ?? '?');
            }
        }

        if (filled($dbSample)) {
            $normalized = 'fotos/' . ltrim(str_replace('fotos/', '', (string) $dbSample), '/');
            $lines[] = '';
            $lines[] = 'Última foto en BD: ' . $dbSample;
            $lines[] = 'Ruta normalizada: ' . $normalized;
            $lines[] = 'Existe en disco: ' . (public_storage_file_exists($normalized) ? 'si' : 'no');
            $lines[] = 'URL: ' . (public_storage_url($normalized) ?? '?');
        }

        if (! $fullAudit) {
            $lines[] = '';
            $lines[] = 'Auditoría completa BD vs disco: usa /diag-storage?audit=1';
            $lines[] = '(puede tardar 1–2 min si hay muchas fotos)';
            $lines[] = '';
            $lines[] = 'BORRA /diag-storage después de revisar.';

            return implode("\n", $lines);
        }

        set_time_limit(300);

        $diskLookup = [];

        if (is_dir($fotosDir)) {
            foreach (scandir($fotosDir) ?: [] as $entry) {
                if ($entry === '.' || $entry === '..') {
                    continue;
                }

                if (is_file($fotosDir . DIRECTORY_SEPARATOR . $entry)) {
                    $diskLookup[strtolower($entry)] = true;
                }
            }
        }

        $dbFilenames = [];
        $registrosConArchivo = 0;
        $registrosSinArchivo = 0;
        $sinArchivoMuestra = [];

        foreach (\App\Models\FotoInmueble::query()->select(['id', 'foto', 'inmueble_fk'])->cursor() as $foto) {
            $filename = strtolower(basename(str_replace('\\', '/', (string) ($foto->getAttributes()['foto'] ?? ''))));

            if ($filename === '') {
                continue;
            }

            $dbFilenames[$filename] = true;

            if (isset($diskLookup[$filename])) {
                $registrosConArchivo++;

                continue;
            }

            $registrosSinArchivo++;

            if (count($sinArchivoMuestra) < 5) {
                $sinArchivoMuestra[] = 'id=' . $foto->id . ' inmueble=' . $foto->inmueble_fk . ' foto=' . ($foto->getAttributes()['foto'] ?? '?');
            }
        }

        $archivosHuerfanos = 0;

        foreach (array_keys($diskLookup) as $filename) {
            if (! isset($dbFilenames[$filename])) {
                $archivosHuerfanos++;
            }
        }

        $lines[] = '';
        $lines[] = '=== Cruce BD vs disco (auditoría completa) ===';
        $lines[] = 'Archivos en fotos/: ' . count($diskLookup);
        $lines[] = 'Registros en fotos_inmuebles: ' . $totalRegistros;
        $lines[] = 'Registros CON archivo en disco: ' . $registrosConArchivo;
        $lines[] = 'Registros SIN archivo en disco: ' . $registrosSinArchivo;
        $lines[] = 'Archivos en disco sin registro BD: ' . $archivosHuerfanos;

        if ($sinArchivoMuestra !== []) {
            $lines[] = '';
            $lines[] = 'Ejemplos de registros sin archivo:';

            foreach ($sinArchivoMuestra as $row) {
                $lines[] = '  - ' . $row;
            }
        }

        if ($registrosSinArchivo > 0 && $archivosHuerfanos > 0) {
            $lines[] = '';
            $lines[] = 'Conclusión: la BD apunta a nombres distintos a los archivos en disco.';
            $lines[] = 'Hay que alinear nombres (migración) o volver a subir las fotos en el panel.';
        }

        $lines[] = '';
        $lines[] = 'BORRA /diag-storage después de revisar.';

        return implode("\n", $lines);
    }
}

Route::get('/limpiar-vistas', function () {
    $token = (string) env('DEPLOY_CLEAR_TOKEN', '');

    if ($token === '' || ! hash_equals($token, (string) request()->query('token', ''))) {
        abort(404);
    }

    $messages = [];

    foreach (['view:clear', 'config:clear', 'route:clear', 'cache:clear'] as $command) {
        Artisan::call($command);
        $messages[] = $command . ': ok';
    }

    $panelDomain = panel_domain();
    $loginUrl = filament()->getPanel('admin')->getLoginUrl();

    $messages[] = '';
    $messages[] = 'FILAMENT_DOMAIN: ' . ($panelDomain ?: '(no definido — panel en /pe-panel/acceso del sitio principal)');
    $messages[] = 'URL login panel: ' . ($loginUrl ?: '(no disponible)');

    return response(
        implode("\n", $messages) . "\n\nQuita DEPLOY_CLEAR_TOKEN del .env cuando termines.",
        200,
        ['Content-Type' => 'text/plain; charset=utf-8'],
    );
});

Route::get('/diag-panel', function () {
    $token = (string) env('DEPLOY_CLEAR_TOKEN', '');

    if ($token === '' || ! hash_equals($token, (string) request()->query('token', ''))) {
        abort(404);
    }

    $lines = [
        '=== Diagnóstico panel Filament ===',
        'Host: ' . request()->getHost(),
        'APP_URL: ' . config('app.url'),
        'FILAMENT_DOMAIN (config): ' . (config('app.panel_domain') ?: '(vacío)'),
        'panel_domain(): ' . (panel_domain() ?: '(vacío)'),
    ];

    try {
        $panel = filament()->getPanel('admin');
        $lines[] = 'Login URL: ' . ($panel->getLoginUrl() ?: '(vacío)');
        $lines[] = 'Panel path: ' . ($panel->getPath() ?: '(raíz del subdominio)');
    } catch (\Throwable $e) {
        $lines[] = 'Filament error: ' . $e->getMessage();
    }

    $accesoRoutes = collect(app('router')->getRoutes())
        ->filter(fn ($route): bool => str_contains($route->uri(), 'acceso'))
        ->map(fn ($route): string => implode('|', $route->methods()) . ' '
            . ($route->getDomain() ?: '*') . ' /' . $route->uri())
        ->values();

    $lines[] = '';
    $lines[] = 'Rutas con "acceso":';

    if ($accesoRoutes->isEmpty()) {
        $lines[] = '  (ninguna — borra bootstrap/cache/*.php en el servidor)';
    } else {
        foreach ($accesoRoutes as $routeLine) {
            $lines[] = '  ' . $routeLine;
        }
    }

    $cacheFiles = glob(base_path('bootstrap/cache/*.php')) ?: [];
    $lines[] = '';
    $lines[] = 'Archivos en bootstrap/cache: ' . count($cacheFiles);

    foreach ($cacheFiles as $file) {
        $lines[] = '  - ' . basename($file);
    }

    if (request()->boolean('clear')) {
        $lines[] = '';
        $lines[] = '=== Limpiando caché ===';

        foreach (['view:clear', 'config:clear', 'route:clear', 'cache:clear'] as $command) {
            Artisan::call($command);
            $lines[] = $command . ': ok';
        }

        try {
            $lines[] = 'Login URL (después de limpiar): ' . filament()->getPanel('admin')->getLoginUrl();
        } catch (\Throwable $e) {
            $lines[] = 'Login URL: error — ' . $e->getMessage();
        }
    } else {
        $lines[] = '';
        $lines[] = 'Agrega &clear=1 a esta URL para limpiar caché automáticamente.';
    }

    $lines[] = '';
    $lines[] = 'BORRA DEPLOY_CLEAR_TOKEN del .env cuando termines.';

    return response(implode("\n", $lines), 200, ['Content-Type' => 'text/plain; charset=utf-8']);
});

/*Route::get('/limpiar-sistema', function () {
    $messages = [];

    foreach (['config:clear', 'view:clear', 'cache:clear', 'route:clear'] as $command) {
        Artisan::call($command);
        $messages[] = $command . ': ok';
    }

    $messages[] = ensurePublicStorageLink();

    return response(implode("\n", $messages), 200, ['Content-Type' => 'text/plain; charset=utf-8']);
});*/

/*Route::get('/crear-storage-link', function () {
    return response(ensurePublicStorageLink(), 200, ['Content-Type' => 'text/plain; charset=utf-8']);
});*/

/*Route::get('/diag-storage', function () {
    $fullAudit = request()->boolean('audit');

    return response(
        publicStorageDiagnostics($fullAudit),
        200,
        ['Content-Type' => 'text/plain; charset=utf-8'],
    );
});*/

if ($panelDomain = panel_domain()) {
    Route::domain($panelDomain)->group(function (): void {
        Route::redirect('/entrar', '/acceso', 301);
    });

    foreach (mainSiteHosts() as $siteHost) {
        Route::domain($siteHost)->group(function () use ($panelDomain): void {
            Route::redirect('/pe-panel', 'https://' . $panelDomain . '/acceso', 302);
            Route::redirect('/pe-panel/acceso', 'https://' . $panelDomain . '/acceso', 302);
        });
    }
}

Route::get('/media/{path}', function (string $path) {
    $path = str_replace('\\', '/', $path);
    $path = ltrim(str_replace(['..', './'], '', $path), '/');

    if (! preg_match('#^(fotos|banners|ciudades|usuarios)/#', $path)) {
        abort(404);
    }

    $absolutePath = public_storage_file_path($path);

    if (! is_file($absolutePath)) {
        abort(404);
    }

    $mime = match (strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION))) {
        'png' => 'image/png',
        'gif' => 'image/gif',
        'webp' => 'image/webp',
        'avif' => 'image/avif',
        'jpg', 'jpeg' => 'image/jpeg',
        default => mime_content_type($absolutePath) ?: 'application/octet-stream',
    };

    return response()->file($absolutePath, [
        'Content-Type' => $mime,
        'Cache-Control' => 'public, max-age=31536000',
    ]);
})->where('path', '.*');

foreach (mainSiteHosts() as $siteHost) {
    Route::domain($siteHost)->group(function (): void {
        Route::get('/', [FrontController::class, 'index'])->name('home');
        Route::get('/inmuebles', [PropertyController::class, 'search'])->name('inmuebles.search');
        Route::get('/inmuebles/{inmueble}', [PropertyController::class, 'show'])->name('inmuebles.show');

        Route::get('/nosotros', [FrontController::class, 'about'])->name('about');
        Route::get('/servicios', [FrontController::class, 'services'])->name('services');
        Route::get('/contacto', [FrontController::class, 'contact'])->name('contact');
        Route::post('/contact', [ContactController::class, 'send'])->name('contact.send');

        Route::get('/clientes', [FrontController::class, 'clients'])->name('clients');
        Route::post('/testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
        Route::get('/requisitos', [FrontController::class, 'requirements'])->name('requirements');
        Route::get('/requisitos/arrendatarios', [FrontController::class, 'tenant'])->name('tenant');
        Route::get('/requisitos/propietarios', [FrontController::class, 'property'])->name('property');
        Route::get('/faq', [FrontController::class, 'faq'])->name('faq');

        Route::get('/inmuebles/ciudad/{ciudad}', [FrontController::class, 'porCiudad'])->name('inmuebles.ciudad');

        Route::get('/barrios/{ciudad}', function ($ciudadId) {
            if (! \App\Models\Ciudad::esVisibleEnBuscador($ciudadId)) {
                return response()->json([]);
            }

            $barrios = DB::table('barrios')
                ->where('ciudad_fk', $ciudadId)
                ->orderBy('nombre')
                ->get(['codigo_barrio', 'nombre']);

            return response()->json($barrios);
        });

        Route::post('/property/contact', [PropertyContactController::class, 'send'])
            ->name('property.contact');

        Route::get('/entrar-al-panel', function () {
            $loginUrl = filament()->getPanel('admin')->getLoginUrl();

            return $loginUrl
                ? redirect()->to($loginUrl)
                : response('Panel no configurado.', 500);
        });
    });
}
