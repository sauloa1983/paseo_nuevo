<?php

/**
 * Diagnóstico rápido de despliegue. Sube a public_html/, abre en el navegador
 * y BORRA este archivo inmediatamente después.
 */
header('Content-Type: text/plain; charset=utf-8');

echo "=== Diagnóstico Paseo España ===\n\n";
echo 'PHP: ' . PHP_VERSION . "\n";
echo 'Document root: ' . __DIR__ . "\n\n";

$adminDir = __DIR__ . '/admin';
echo 'Carpeta admin/ en public_html: ' . (is_dir($adminDir) ? 'SI — ELIMINAR (causa 403)' : 'no') . "\n";

$storagePath = __DIR__ . '/storage';
$storageIsLink = is_link($storagePath);
echo 'public_html/storage: ';
if (! file_exists($storagePath)) {
    echo "no existe — ejecuta: php artisan storage:link\n";
} elseif ($storageIsLink) {
    echo 'enlace simbólico ok → ' . readlink($storagePath) . "\n";
} else {
    echo "CARPETA FÍSICA — eliminar y ejecutar php artisan storage:link\n";
}

echo 'index.php: ' . (is_file(__DIR__ . '/index.php') ? 'ok' : 'FALTA') . "\n";
echo '.htaccess: ' . (is_file(__DIR__ . '/.htaccess') ? 'ok' : 'FALTA') . "\n";

$modules = function_exists('apache_get_modules') ? apache_get_modules() : [];
echo 'mod_rewrite: ' . (in_array('mod_rewrite', $modules ?: [], true) ? 'ok' : 'no detectado') . "\n\n";

echo "Carpetas Laravel hermanas:\n";
$found = false;
foreach (glob(dirname(__DIR__) . '/*/artisan') ?: [] as $artisan) {
    $found = true;
    echo '  - ' . dirname($artisan) . "\n";
}
if (! $found && is_file(__DIR__ . '/artisan')) {
    echo '  - Laravel en public_html: ' . __DIR__ . "\n";
}

echo "\nURLs a probar:\n";
echo "  - https://TU-DOMINIO/\n";
echo "  - https://TU-DOMINIO/gestion/login  (panel Filament)\n";
echo "\nNota: /admin suele dar 403 por ModSecurity en LiteSpeed. Usa /gestion/login.\n";
echo "\n=== Fin ===\n";
