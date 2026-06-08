<?php
/**
 * Auditoría de fotos - Paseo España
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
set_time_limit(300);

function formatearPeso($bytes)
{
    $bytes = (float) $bytes;
    if ($bytes >= 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    }
    if ($bytes >= 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    }
    if ($bytes >= 1024) {
        return number_format($bytes / 1024, 2) . ' KB';
    }
    return $bytes . ' bytes';
}

function h($text)
{
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

function normalizarNombreFoto($ruta)
{
    return strtolower(basename(str_replace('\\', '/', (string) $ruta)));
}

function cargarNombresFotosBD($db, $tablaFotos)
{
    $nombres = array();
    $res = $db->query('SELECT foto FROM ' . $tablaFotos . ' WHERE foto IS NOT NULL AND TRIM(foto) <> \'\'');
    if (! $res) {
        throw new Exception('Consulta fotos: ' . $db->error);
    }
    while ($row = $res->fetch_assoc()) {
        $nombres[normalizarNombreFoto($row['foto'])] = true;
    }
    $res->free();
    return $nombres;
}

function rutaRelativaEnCarpeta($rutaCarpeta, $rutaAbsoluta)
{
    $base = realpath($rutaCarpeta);
    $archivo = realpath($rutaAbsoluta);

    if (! $base || ! $archivo || strpos($archivo, $base) !== 0) {
        return null;
    }

    $relativa = substr($archivo, strlen($base));
    return ltrim(str_replace('\\', '/', $relativa), '/');
}

function resolverRutaHuerfana($rutaCarpeta, $rutaRelativa, $fotosEnBDLookup)
{
    $rutaRelativa = ltrim(str_replace('\\', '/', (string) $rutaRelativa), '/');

    if ($rutaRelativa === '' || strpos($rutaRelativa, '..') !== false) {
        return null;
    }

    if (isset($fotosEnBDLookup[normalizarNombreFoto($rutaRelativa)])) {
        return null;
    }

    $rutaCompleta = $rutaCarpeta . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $rutaRelativa);
    $realArchivo = realpath($rutaCompleta);
    $realCarpeta = realpath($rutaCarpeta);

    if (! $realArchivo || ! $realCarpeta || strpos($realArchivo, $realCarpeta) !== 0 || ! is_file($realArchivo)) {
        return null;
    }

    return $realArchivo;
}

function eliminarHuerfano($rutaCarpeta, $rutaRelativa, $fotosEnBDLookup)
{
    $rutaAbsoluta = resolverRutaHuerfana($rutaCarpeta, $rutaRelativa, $fotosEnBDLookup);

    if ($rutaAbsoluta === null) {
        return false;
    }

    if (! is_writable($rutaAbsoluta)) {
        return false;
    }

    return @unlink($rutaAbsoluta);
}

$hostname       = 'localhost';
$bd_usuario     = 'paseoesp_sitio';
$bd_clave       = 'i$;ODHoOAg4U';
$bd_nombre      = 'paseoesp_sitio';
$tablaFotos     = 'fotos_inmuebles';
$tablaInmuebles = 'inmuebles';

$rutasCandidatas = array(
    __DIR__ . '/fotos'                  => '/fotos',
    __DIR__ . '/storage/fotos'          => '/storage/fotos',
    __DIR__ . '/public/storage/fotos'   => '/storage/fotos',
    __DIR__ . '/public/fotos'           => '/fotos',
);

$rutaCarpeta = null;
$urlBaseFotos = '/fotos';
foreach ($rutasCandidatas as $ruta => $urlPublica) {
    if (is_dir($ruta)) {
        $rutaCarpeta = $ruta;
        $urlBaseFotos = $urlPublica;
        break;
    }
}

$extensiones = array('jpg', 'jpeg', 'png', 'webp', 'gif');
$error = null;
$flash = isset($_GET['msg']) ? (string) $_GET['msg'] : null;

$totalInmuebles = 0;
$inmueblesConFotos = 0;
$inmueblesSinFotos = 0;
$totalRegistrosFoto = 0;
$archivosLigados = 0;
$archivosHuerfanos = 0;
$registrosSinArchivo = 0;
$pesoLigadasBytes = 0;
$pesoHuerfanasBytes = 0;
$totalFotosCarpeta = 0;
$pesoTotalCarpetaBytes = 0;

$muestraLimite = 100;

$fotosEnBD = array();
$fotosEnBDLookup = array();
$inmueblesSinFotoLista = array();
$archivosHuerfanosLista = array();
$registrosSinArchivoLista = array();
$registrosSinArchivoMuestra = array();

if (! class_exists('mysqli')) {
    die('ERROR: mysqli no habilitado.');
}

try {
    $db = new mysqli($hostname, $bd_usuario, $bd_clave, $bd_nombre);
    if ($db->connect_errno) {
        throw new Exception('Conexion MySQL: ' . $db->connect_error);
    }
    $db->set_charset('utf8');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $rutaCarpeta !== null) {
        $fotosEnBDLookup = cargarNombresFotosBD($db, $tablaFotos);
        $accion = isset($_POST['accion']) ? $_POST['accion'] : '';
        $eliminados = 0;

        if ($accion === 'eliminar_uno' && ! empty($_POST['ruta_relativa'])) {
            if (eliminarHuerfano($rutaCarpeta, $_POST['ruta_relativa'], $fotosEnBDLookup)) {
                $eliminados = 1;
            }
        }

        if ($accion === 'eliminar_todos') {
            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($rutaCarpeta, FilesystemIterator::SKIP_DOTS)
            );
            foreach ($iterator as $file) {
                if (! $file->isFile()) {
                    continue;
                }
                $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
                if (! in_array($ext, $extensiones, true)) {
                    continue;
                }
                $relativa = rutaRelativaEnCarpeta($rutaCarpeta, $file->getPathname());
                if ($relativa && eliminarHuerfano($rutaCarpeta, $relativa, $fotosEnBDLookup)) {
                    $eliminados++;
                }
            }
        }

        $db->close();
        $msg = $eliminados > 0
            ? 'Se eliminaron ' . $eliminados . ' archivo(s) huérfano(s).'
            : 'No se pudo eliminar. Revise permisos de escritura en la carpeta fotos o que el archivo siga existiendo.';
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?') . '?msg=' . urlencode($msg));
        exit;
    }

    $res = $db->query('SELECT COUNT(*) AS t FROM ' . $tablaInmuebles);
    if ($res) {
        $row = $res->fetch_assoc();
        $totalInmuebles = (int) $row['t'];
        $res->free();
    }

    $sql = 'SELECT COUNT(*) AS t FROM ' . $tablaInmuebles . ' i WHERE EXISTS (
        SELECT 1 FROM ' . $tablaFotos . ' f
        WHERE f.inmueble_fk = i.id AND f.foto IS NOT NULL AND TRIM(f.foto) <> \'\'
    )';
    $res = $db->query($sql);
    if ($res) {
        $row = $res->fetch_assoc();
        $inmueblesConFotos = (int) $row['t'];
        $res->free();
    }

    $inmueblesSinFotos = max(0, $totalInmuebles - $inmueblesConFotos);

    $sqlSinFotos = 'SELECT i.id, i.codigo, i.estado
        FROM ' . $tablaInmuebles . ' i
        WHERE NOT EXISTS (
            SELECT 1 FROM ' . $tablaFotos . ' f
            WHERE f.inmueble_fk = i.id AND f.foto IS NOT NULL AND TRIM(f.foto) <> \'\'
        )
        ORDER BY i.codigo ASC';
    $res = $db->query($sqlSinFotos);
    if (! $res) {
        throw new Exception('Consulta inmuebles sin foto: ' . $db->error);
    }
    while ($row = $res->fetch_assoc()) {
        $inmueblesSinFotoLista[] = $row;
    }
    $res->free();

    $res = $db->query('SELECT inmueble_fk, foto FROM ' . $tablaFotos . ' WHERE foto IS NOT NULL AND TRIM(foto) <> \'\'');
    if (! $res) {
        throw new Exception('Consulta fotos: ' . $db->error);
    }

    while ($row = $res->fetch_assoc()) {
        $totalRegistrosFoto++;
        $nombre = basename(str_replace('\\', '/', $row['foto']));
        $clave = normalizarNombreFoto($nombre);
        $fotosEnBD[$clave] = array(
            'inmueble_fk' => $row['inmueble_fk'],
            'ruta_bd'    => $row['foto'],
            'archivo'    => $nombre,
        );
        $fotosEnBDLookup[$clave] = true;
    }
    $res->free();
    $db->close();
} catch (Exception $ex) {
    $error = $ex->getMessage();
}

if (! $error && $rutaCarpeta === null) {
    $error = 'No se encontro carpeta fotos. Rutas probadas: ' . implode(', ', array_keys($rutasCandidatas));
}

if (! $error) {
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rutaCarpeta, FilesystemIterator::SKIP_DOTS)
    );

    foreach ($iterator as $file) {
        if (! $file->isFile()) {
            continue;
        }

        $ext = strtolower(pathinfo($file->getFilename(), PATHINFO_EXTENSION));
        if (! in_array($ext, $extensiones, true)) {
            continue;
        }

        $nombre = $file->getFilename();
        $peso = $file->getSize();
        $rutaRelativa = rutaRelativaEnCarpeta($rutaCarpeta, $file->getPathname());
        $clave = normalizarNombreFoto($nombre);

        if ($rutaRelativa === null) {
            continue;
        }

        if (isset($fotosEnBD[$clave])) {
            $archivosLigados++;
            $pesoLigadasBytes += $peso;
            unset($fotosEnBD[$clave]);
        } else {
            $archivosHuerfanos++;
            $pesoHuerfanasBytes += $peso;
            $partesUrl = array_map('rawurlencode', explode('/', $rutaRelativa));
            $archivosHuerfanosLista[] = array(
                'nombre'        => $nombre,
                'ruta_relativa' => $rutaRelativa,
                'peso'          => formatearPeso($peso),
                'url'           => rtrim($urlBaseFotos, '/') . '/' . implode('/', $partesUrl),
            );
        }
    }

    usort($archivosHuerfanosLista, function ($a, $b) {
        return strcmp($a['nombre'], $b['nombre']);
    });

    $registrosSinArchivoLista = array_values($fotosEnBD);
    usort($registrosSinArchivoLista, function ($a, $b) {
        return strcmp($a['archivo'], $b['archivo']);
    });

    $registrosSinArchivo = count($registrosSinArchivoLista);
    $registrosSinArchivoMuestra = array_slice($registrosSinArchivoLista, 0, $muestraLimite);

    $totalFotosCarpeta = $archivosLigados + $archivosHuerfanos;
    $pesoTotalCarpetaBytes = $pesoLigadasBytes + $pesoHuerfanasBytes;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Fotos</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1100px; margin: 40px auto; padding: 0 20px; }
        h1, h2 { color: #c81517; }
        h2 { font-size: 1.1rem; margin-top: 28px; display: flex; align-items: center; justify-content: space-between; gap: 12px; flex-wrap: wrap; }
        .box { border: 1px solid #ddd; border-radius: 8px; padding: 16px; margin: 12px 0; }
        .warn { color: #b45309; font-size: 18px; font-weight: bold; }
        .bad { color: #b91c1c; font-size: 18px; font-weight: bold; }
        .err { background: #fee2e2; color: #991b1b; padding: 12px; border-radius: 6px; }
        .ok { background: #dcfce7; color: #166534; padding: 12px; border-radius: 6px; margin-bottom: 12px; }
        .lista { max-height: 420px; overflow-y: auto; border: 1px solid #e5e7eb; border-radius: 6px; }
        table { width: 100%; border-collapse: collapse; font-size: 14px; }
        th, td { padding: 8px 10px; border-bottom: 1px solid #eee; text-align: left; vertical-align: middle; }
        th { background: #f9fafb; position: sticky; top: 0; }
        tr:nth-child(even) { background: #fafafa; }
        small { color: #666; }
        .btn { display: inline-block; padding: 6px 12px; border-radius: 6px; border: none; cursor: pointer; font-size: 13px; text-decoration: none; }
        .btn-danger { background: #b91c1c; color: #fff; }
        .btn-danger:hover { background: #991b1b; }
        .btn-link { background: #eff6ff; color: #1d4ed8; }
        .btn-link:hover { background: #dbeafe; }
        .acciones { display: flex; gap: 6px; flex-wrap: wrap; }
    </style>
</head>
<body>
    <h1>Reporte de fotos</h1>

    <?php if ($flash): ?>
        <div class="ok"><?php echo h($flash); ?></div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="err"><?php echo h($error); ?></div>
    <?php else: ?>
        <p><small>Carpeta escaneada: <?php echo h($rutaCarpeta); ?> | URL pública: <?php echo h($urlBaseFotos); ?>/</small></p>

        <div class="box">
            <strong>Carpeta fotos</strong><br>
            Total de fotos en disco: <strong><?php echo number_format($totalFotosCarpeta); ?></strong>
            (ligadas: <?php echo number_format($archivosLigados); ?> + huérfanas: <?php echo number_format($archivosHuerfanos); ?>)<br>
            Peso total de la carpeta: <strong><?php echo formatearPeso($pesoTotalCarpetaBytes); ?></strong>
            (ligadas: <?php echo formatearPeso($pesoLigadasBytes); ?> + huérfanas: <?php echo formatearPeso($pesoHuerfanasBytes); ?>)
        </div>

        <div class="box">
            <strong>Resumen BD</strong><br>
            Inmuebles total: <?php echo number_format($totalInmuebles); ?> |
            Con fotos: <?php echo number_format($inmueblesConFotos); ?> |
            Sin fotos: <span class="warn"><?php echo number_format($inmueblesSinFotos); ?></span><br>
            Registros en fotos_inmuebles: <?php echo number_format($totalRegistrosFoto); ?><br>
            Registros BD sin archivo: <span class="warn"><?php echo number_format($registrosSinArchivo); ?></span>
        </div>

        <h2>Inmuebles sin foto (<?php echo count($inmueblesSinFotoLista); ?>)</h2>
        <div class="lista">
            <?php if (empty($inmueblesSinFotoLista)): ?>
                <p style="padding:12px;">Ninguno.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Código</th><th>Estado</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($inmueblesSinFotoLista as $inmueble): ?>
                            <tr>
                                <td><?php echo h($inmueble['id']); ?></td>
                                <td><?php echo h($inmueble['codigo']); ?></td>
                                <td><?php echo ((int) $inmueble['estado'] === 0) ? 'Activo' : 'Inactivo'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <h2>
            Registros BD sin archivo — muestra de <?php echo min($muestraLimite, $registrosSinArchivo); ?>
            de <?php echo number_format($registrosSinArchivo); ?>
        </h2>
        <div class="lista">
            <?php if (empty($registrosSinArchivoMuestra)): ?>
                <p style="padding:12px;">Ninguno.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr><th>Inmueble FK</th><th>Ruta en BD</th><th>Archivo esperado</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrosSinArchivoMuestra as $registro): ?>
                            <tr>
                                <td><?php echo h($registro['inmueble_fk']); ?></td>
                                <td><?php echo h($registro['ruta_bd']); ?></td>
                                <td><?php echo h($registro['archivo']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>

        <h2>
            <span>Archivos huérfanos (<?php echo count($archivosHuerfanosLista); ?>)</span>
            <?php if (! empty($archivosHuerfanosLista)): ?>
                <form method="post" onsubmit="return confirm('¿Eliminar TODOS los archivos huérfanos? Esta acción no se puede deshacer.');">
                    <input type="hidden" name="accion" value="eliminar_todos">
                    <button type="submit" class="btn btn-danger">Eliminar todos los huérfanos</button>
                </form>
            <?php endif; ?>
        </h2>
        <div class="lista">
            <?php if (empty($archivosHuerfanosLista)): ?>
                <p style="padding:12px;">Ninguno.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Archivo</th>
                            <th>Peso</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($archivosHuerfanosLista as $archivo): ?>
                            <tr>
                                <td><?php echo h($archivo['nombre']); ?></td>
                                <td><?php echo h($archivo['peso']); ?></td>
                                <td>
                                    <div class="acciones">
                                        <a class="btn btn-link" href="<?php echo h($archivo['url']); ?>" target="_blank" rel="noopener">Ver foto</a>
                                        <form method="post" style="display:inline;" onsubmit="return confirm('¿Eliminar <?php echo h($archivo['nombre']); ?>?');">
                                            <input type="hidden" name="accion" value="eliminar_uno">
                                            <input type="hidden" name="ruta_relativa" value="<?php echo h($archivo['ruta_relativa']); ?>">
                                            <button type="submit" class="btn btn-danger">Eliminar</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</body>
</html>
