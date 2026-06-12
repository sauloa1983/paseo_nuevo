<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

function laravelRootFromPublicHtml(): string
{
    if (is_file(__DIR__ . '/artisan') && is_dir(__DIR__ . '/vendor')) {
        return __DIR__;
    }

    $parent = dirname(__DIR__);

    foreach (glob($parent . '/*/artisan') ?: [] as $artisanPath) {
        $root = dirname($artisanPath);

        if (is_dir($root . '/vendor') && is_dir($root . '/bootstrap')) {
            return $root;
        }
    }

    foreach (['proyecto_paseo', 'paseo_espana'] as $folder) {
        $fallback = $parent . DIRECTORY_SEPARATOR . $folder;

        if (is_dir($fallback . '/vendor')) {
            return $fallback;
        }
    }

    http_response_code(500);
    exit('No se encontró Laravel. Copia index.php junto a public_html o apunta el subdominio a public_html.');
}

define('LARAVEL_ROOT', laravelRootFromPublicHtml());

if (file_exists($maintenance = LARAVEL_ROOT . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

require LARAVEL_ROOT . '/vendor/autoload.php';

/** @var Application $app */
$app = require_once LARAVEL_ROOT . '/bootstrap/app.php';

$app->handleRequest(Request::capture());
