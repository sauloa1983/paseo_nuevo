<?php
/**
 * Auditor de Consistencia post-migración
 * Compara los archivos físicos reales contra los registros esperados.
 */

$carpeta_destino = 'fotos_migracion/';
$carpeta_destino = rtrim($carpeta_destino, '/') . '/';

// 1. Leer los archivos reales que existen físicamente en la nueva carpeta
$archivos_reales = glob($carpeta_destino . '*');
$lista_fisica_real = [];

if (!empty($archivos_reales)) {
    foreach ($archivos_reales as $ruta) {
        if (is_file($ruta)) {
            $lista_fisica_real[basename($ruta)] = true;
        }
    }
}

// 2. Leer los archivos que el sistema intentó migrar desde la carpeta original
// (Usamos la misma lógica de lectura que usó tu script de copiado)
$carpeta_origen = 'fotos/';
$carpeta_origen = rtrim($carpeta_origen, '/') . '/';
$patron = $carpeta_origen . '*.{jpg,jpeg,png,gif,webp,JPG,JPEG,PNG,GIF,WEBP}';
$archivos_origen = glob($patron, GLOB_BRACE);

$archivos_faltantes = [];

if (!empty($archivos_origen)) {
    foreach ($archivos_origen as $ruta_origen) {
        $nombre_archivo = basename($ruta_origen);

        // Si el archivo de origen NO existe en la lista de la carpeta destino
        if (!isset($lista_fisica_real[$nombre_archivo])) {
            $archivos_faltantes[] = $nombre_archivo;
        }
    }
}

// 3. Mostrar el reporte en pantalla
echo "<h2>📊 Reporte de Auditoría de Archivos</h2>";
echo "Archivos encontrados en destino físico: <strong>" . count($lista_fisica_real) . "</strong><br>";
echo "Archivos detectados en origen: <strong>" . count($archivos_origen) . "</strong><br>";
echo "Diferencia real faltante: <strong style='color:red;'>" . count($archivos_faltantes) . " archivos</strong><br><br>";

if (!empty($archivos_faltantes)) {
    echo "<h3>📋 Lista de archivos que hacen falta en la nueva carpeta:</h3>";
    echo "<div style='background:#f8f9fa; padding:15px; border:1px solid #ddd; max-height:400px; overflow-y:scroll; font-family:monospace;'>";
    foreach ($archivos_faltantes as $faltante) {
        echo "❌ M.IA. Faltante: " . htmlspecialchars($faltante) . "<br>";
    }
    echo "</div>";
} else {
    echo "<h3 style='color:green;'>✔ ¡Excelente! Todos los archivos físicos del origen están presentes en el destino.</h3>";
}
?>
