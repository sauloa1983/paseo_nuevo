<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

/**
 * Resuelve la raíz de Laravel según la estructura del hosting.
 */
function laravelRootFromPublicHtml(): string
{
    // Caso A: todo el proyecto Laravel está dentro de public_html
    if (is_file(__DIR__ . '/artisan') && is_dir(__DIR__ . '/vendor')) {
        return __DIR__;
    }

    // Caso B: Laravel en carpeta hermana de public_html (cPanel típico)
    $parent = dirname(__DIR__);

    foreach (glob($parent . '/*/artisan') ?: [] as $artisanPath) {
        $root = dirname($artisanPath);

        if (is_dir($root . '/vendor') && is_dir($root . '/bootstrap')) {
            return $root;
        }
    }

    $fallback = $parent . '/proyecto_paseo';

    if (is_dir($fallback . '/vendor')) {
        return $fallback;
    }

    $fallback = $parent . '/paseo_espana';

    if (is_dir($fallback . '/vendor')) {
        return $fallback;
    }

    http_response_code(500);
    exit('No se encontró la aplicación Laravel. Verifica la estructura de carpetas en el servidor.');
}

define('LARAVEL_ROOT', laravelRootFromPublicHtml());

if (file_exists($maintenance = LARAVEL_ROOT . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

require LARAVEL_ROOT . '/vendor/autoload.php';

/** @var Application $app */
$app = require_once LARAVEL_ROOT . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
