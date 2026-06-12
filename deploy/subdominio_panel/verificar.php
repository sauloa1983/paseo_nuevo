<?php

header('Content-Type: text/plain; charset=utf-8');

echo "=== Verificación subdominio panel ===\n\n";
echo 'PHP: ' . PHP_VERSION . "\n";
echo 'Carpeta actual: ' . __DIR__ . "\n";
echo 'Host: ' . ($_SERVER['HTTP_HOST'] ?? '?') . "\n\n";
echo 'index.php: ' . (is_file(__DIR__ . '/index.php') ? 'OK' : 'FALTA — sube deploy/subdominio_panel/index.php') . "\n";
echo '.htaccess: ' . (is_file(__DIR__ . '/.htaccess') ? 'OK' : 'FALTA — sube deploy/subdominio_panel/.htaccess') . "\n\n";

$parent = dirname(__DIR__);
echo "Laravel en carpetas hermanas:\n";
$found = false;
foreach (glob($parent . '/*/artisan') ?: [] as $artisan) {
    $found = true;
    echo '  - ' . dirname($artisan) . "\n";
}
if (! $found) {
    echo "  (no encontrado)\n";
}

echo "\nSi ves este texto, PHP funciona aquí.\n";
echo "Prueba luego: /index.php y /acceso\n";
echo "BORRA este archivo después de verificar.\n";
