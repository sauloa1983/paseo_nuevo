<?php
/**
 * Migrador de Fotos Inteligente por Bloques Estrictos de 10,000
 * Desarrollado para Paseo España.
 */

$carpeta_origen  = 'fotos/';
$carpeta_destino = 'fotos_migracion/';

$carpeta_origen  = rtrim($carpeta_origen, '/') . '/';
$carpeta_destino = rtrim($carpeta_destino, '/') . '/';

// --- FUNCIÓN PARA DETECTAR EL ESTADO REAL DE LA CARPETA ---
function obtenerDetallesCarpeta($ruta) {
    if (!is_dir($ruta)) {
        return ['existe' => false, 'cantidad' => 0, 'peso_mb' => 0];
    }
    $archivos = glob($ruta . '*');
    $cantidad = 0;
    $peso_total = 0;
    if (!empty($archivos)) {
        foreach ($archivos as $archivo) {
            if (is_file($archivo)) {
                $cantidad++;
                $peso_total += filesize($archivo);
            }
        }
    }
    return ['existe' => true, 'cantidad' => $cantidad, 'peso_mb' => round($peso_total / (1024 * 1024), 2)];
}

// --- 1. PROCESAMIENTO BACKEND (Peticiones AJAX) ---
if (isset($_GET['accion'])) {
    header('Content-Type: application/json');

    // --- ACCIÓN A: DIAGNÓSTICO ---
    if ($_GET['accion'] === 'diagnostico') {
        echo json_encode(obtenerDetallesCarpeta($carpeta_destino));
        exit;
    }

    // --- ACCIÓN B: PROCESAR POR SUB-LOTES ---
    if ($_GET['accion'] === 'procesar') {
        $patron = $carpeta_origen . '*.{jpg,jpeg,png,gif,webp,JPG,JPEG,PNG,GIF,WEBP}';
        $imagenes = glob($patron, GLOB_BRACE);

        if ($imagenes === false) {
            echo json_encode(['error' => 'No se pudo leer la carpeta de origen.']);
            exit;
        }

        $total_imagenes = count($imagenes);
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $limite_bloque = isset($_GET['inicio_bloque']) ? (int)$_GET['inicio_bloque'] + 10000 : 10000;

        $lote = 50; // Tanda de procesamiento por segundo

        if (!is_dir($carpeta_destino)) {
            mkdir($carpeta_destino, 0755, true);
        }

        // Evaluar si ya procesamos las 10,000 de este bloque actual
        if ($offset >= $limite_bloque) {
            echo json_encode([
                'total' => $total_imagenes,
                'procesados' => $offset,
                'completado' => $offset >= $total_imagenes,
                'bloque_lleno' => true
            ]);
            exit;
        }

        $imagenes_a_procesar = array_slice($imagenes, $offset, $lote);
        $copiados_exitosamente = 0;

        foreach ($imagenes_a_procesar as $ruta_origen) {
            $nombre_archivo = basename($ruta_origen);
            if (@copy($ruta_origen, $carpeta_destino . $nombre_archivo)) {
                $copiados_exitosamente++;
            } else {
                // Freno de emergencia por espacio físico real en disco
                echo json_encode([
                    'total' => $total_imagenes,
                    'procesados' => $offset + $copiados_exitosamente,
                    'completado' => false,
                    'error_espacio' => true
                ]);
                exit;
            }
        }

        $nuevo_offset = $offset + count($imagenes_a_procesar);

        echo json_encode([
            'total' => $total_imagenes,
            'procesados' => $nuevo_offset,
            'completado' => $nuevo_offset >= $total_imagenes,
            'bloque_lleno' => ($nuevo_offset >= $limite_bloque),
            'error_espacio' => false
        ]);
        exit;
    }

    // --- ACCIÓN C: COMPRIMIR ---
    if ($_GET['accion'] === 'comprimir') {
        if (!class_exists('ZipArchive')) {
            echo json_encode(['error' => 'La extensión ZipArchive no está habilitada.']);
            exit;
        }

        $archivo_zip = 'lote_imagenes_' . date('Ymd_His') . '.zip';
        $zip = new ZipArchive();

        if ($zip->open($archivo_zip, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            echo json_encode(['error' => 'No se pudo crear el archivo ZIP.']);
            exit;
        }

        $archivos_destino = glob($carpeta_destino . '*');
        foreach ($archivos_destino as $archivo) {
            if (is_file($archivo)) {
                $zip->addFile($archivo, basename($archivo));
            }
        }
        $zip->close();

        echo json_encode(['exito' => true, 'archivo' => $archivo_zip]);
        exit;
    }

    // --- ACCIÓN E: PURGAR CARPETA DESTINO ---
    if ($_GET['accion'] === 'purgar') {
        $archivos_destino = glob($carpeta_destino . '*');
        $eliminados = 0;
        foreach ($archivos_destino as $archivo) {
            if (is_file($archivo)) {
                @unlink($archivo);
                $eliminados++;
            }
        }
        echo json_encode(['exito' => true, 'eliminados' => $eliminados]);
        exit;
    }
}

// --- ACCIÓN D: DESCARGAR ZIP ---
if (isset($_GET['descargar_zip'])) {
    $archivo_solicitado = basename($_GET['descargar_zip']);
    if (file_exists($archivo_solicitado) && pathinfo($archivo_solicitado, PATHINFO_EXTENSION) === 'zip') {
        if (ob_get_level()) ob_end_clean();
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $archivo_solicitado . '"');
        header('Content-Length: ' . filesize($archivo_solicitado));
        readfile($archivo_solicitado);
        unlink($archivo_solicitado);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Migrador por Bloques - Paseo España</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body { background-color: #f4f6f9; font-family: system-ui, sans-serif; }
        .card-migrador { border: none; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.08); background: #ffffff; }
        .progress { height: 12px; border-radius: 6px; background-color: #e9ecef; overflow: hidden; }
        .progress-bar { transition: width 0.3s ease; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center my-5">
        <div class="col-md-7">
            <div class="card card-migrador p-5 text-center">

                <div id="estado-icono" class="mb-4">
                    <i class="bi bi-layers-half text-danger display-3"></i>
                </div>

                <h3 class="fw-bold mb-2">Control Estricto de 10,000 Registros</h3>
                <p id="estado-texto" class="text-secondary mb-4">El sistema dividirá la carga para evitar caídas en la compresión ZIP.</p>

                <div id="bloque-progreso" class="d-none mb-4">
                    <div class="progress mb-2">
                        <div id="barra" class="progress-bar progress-bar-striped progress-bar-animated bg-danger" role="progressbar" style="width: 0%"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center px-1">
                        <span id="contador-numerico" class="fw-semibold text-dark">0 / 0 imágenes</span>
                        <span id="porcentaje" class="fw-bold text-danger">0%</span>
                    </div>
                </div>

                <div class="mt-2 d-flex justify-content-center gap-2">
                    <button id="btn-iniciar" onclick="iniciarMigracion()" class="btn btn-danger px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-play-fill"></i> Iniciar / Continuar Bloque
                    </button>

                    <button id="btn-descargar" onclick="crearZip()" class="btn btn-success d-none px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-file-earmark-zip-fill"></i> Descargar Lote de 10k (.ZIP)
                    </button>

                    <button id="btn-purgar" onclick="purgarServidor()" class="btn btn-dark d-none px-4 py-2 fw-bold shadow-sm">
                        <i class="bi bi-trash3-fill text-warning"></i> Vaciar Servidor y Siguiente Bloque
                    </button>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
let offset = 0;
let inicioBloque = 0; // Monitorea dónde arrancó este bloque específico
let totalImagenesGlobal = 0;

document.addEventListener("DOMContentLoaded", function() {
    fetch('?accion=diagnostico')
        .then(response => response.json())
        .then(data => {
            if (data.cantidad > 0) {
                document.getElementById('estado-icono').innerHTML = '<i class="bi bi-folder-symlink text-warning display-3"></i>';
                document.getElementById('estado-texto').innerHTML = `El servidor tiene un lote pendiente en la carpeta destino.<br><span class="badge bg-dark mt-2 p-2">${data.cantidad} fotos (${data.peso_mb} MB)</span>`;
                document.getElementById('btn-descargar').classList.remove('d-none');
                document.getElementById('btn-purgar').classList.remove('d-none');
            }
        });
});

function iniciarMigracion() {
    document.getElementById('btn-iniciar').classList.add('d-none');
    document.getElementById('btn-descargar').classList.add('d-none');
    document.getElementById('btn-purgar').classList.add('d-none');
    document.getElementById('bloque-progreso').classList.remove('d-none');
    document.getElementById('estado-icono').innerHTML = '<div class="spinner-border text-danger" style="width:3rem; height:3rem;" role="status"></div>';
    document.getElementById('estado-texto').innerText = 'Copiando lote controlado en segundo plano...';

    ejecutarLote();
}

function ejecutarLote() {
    fetch(`?accion=procesar&offset=${offset}&inicio_bloque=${inicioBloque}`)
        .then(response => response.json())
        .then(data => {
            if (data.error) { alert(data.error); return; }

            totalImagenesGlobal = data.total;
            offset = data.procesados;

            let porcentaje = Math.round((offset / totalImagenesGlobal) * 100);
            document.getElementById('barra').style.width = porcentaje + '%';
            document.getElementById('porcentaje').innerText = porcentaje + '%';
            document.getElementById('contador-numerico').innerText = `${offset.toLocaleString()} / ${totalImagenesGlobal.toLocaleString()} imágenes`;

            // DETENCIÓN LÓGICA 1: Llegamos al límite del bloque de 10k
            if (data.bloque_lleno === true) {
                document.getElementById('estado-icono').innerHTML = '<i class="bi bi-collection-zip-fill text-success display-3"></i>';
                document.getElementById('estado-texto').innerHTML = `<strong class="text-success">🎯 BLOQUE DE 10,000 ALCANZADO</strong><br>Se completó con éxito la tanda actual (Posición ${offset.toLocaleString()}).<br>Descarga el archivo ZIP ahora para continuar de forma segura.`;
                document.getElementById('btn-descargar').classList.remove('d-none');
                return;
            }

            // DETENCIÓN LÓGICA 2: El hosting físico se llenó antes de llegar a las 10k
            if (data.error_espacio === true) {
                document.getElementById('estado-icono').innerHTML = '<i class="bi bi-disc-fill text-danger display-3"></i>';
                document.getElementById('estado-texto').innerHTML = `<strong class="text-danger">⚠ ALMACENAMIENTO LLENO</strong><br>El disco se llenó prematuramente en la imagen <strong>${offset.toLocaleString()}</strong>.<br>Descarga lo acumulado para poder liberar espacio.`;
                document.getElementById('btn-descargar').classList.remove('d-none');
                return;
            }

            if (!data.completado) {
                ejecutarLote();
            } else {
                document.getElementById('estado-icono').innerHTML = '<i class="bi bi-check-circle-fill text-success display-3"></i>';
                document.getElementById('estado-texto').innerHTML = '<strong>🚀 ¡Felicidades! Se migraron las ' + totalImagenesGlobal.toLocaleString() + ' imágenes con éxito.</strong>';
                document.getElementById('btn-descargar').classList.remove('d-none');
            }
        });
}

function crearZip() {
    let btn = document.getElementById('btn-descargar');
    btn.disabled = true;
    btn.innerText = 'Empaquetando lote seguro de 10k...';

    fetch('?accion=comprimir')
        .then(response => response.json())
        .then(data => {
            window.location.href = `?descargar_zip=${data.archivo}`;
            setTimeout(() => {
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-file-earmark-zip-fill"></i> Descargar de nuevo (.ZIP)';
                document.getElementById('btn-purgar').classList.remove('d-none');
                document.getElementById('estado-texto').innerHTML = '<strong>¡Lote ZIP descargado!</strong><br>Presiona el botón negro para limpiar la carpeta destino y abrir espacio para las próximas 10,000 imágenes.';
            }, 2000);
        });
}

function purgarServidor() {
    if (!confirm('¿Ya guardaste el paquete ZIP en tu computadora? Al continuar se vaciará el directorio temporal del servidor.')) return;

    document.getElementById('btn-purgar').disabled = true;

    fetch('?accion=purgar')
        .then(response => response.json())
        .then(data => {
            alert(`Espacio liberado. Se limpiaron ${data.eliminados} archivos.`);
            document.getElementById('btn-purgar').classList.add('d-none');
            document.getElementById('btn-purgar').disabled = false;

            // Actualizamos el inicio del bloque para las siguientes 10,000 imágenes
            inicioBloque = offset;

            // Reanudar el ciclo automáticamente sin perder el conteo global
            iniciarMigracion();
        });
}
</script>

</body>
</html>
