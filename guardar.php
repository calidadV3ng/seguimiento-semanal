<?php
// ============================================================
// guardar.php - Guarda los datos en el servidor
// ============================================================

// Configurar cabeceras para permitir peticiones desde cualquier origen
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Si es una petición OPTIONS (preflight), responder OK
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Solo aceptar POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false, 
        'message' => 'Método no permitido. Usa POST.'
    ]);
    exit();
}

// Obtener los datos enviados
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Verificar que los datos sean válidos
if (!$data) {
    echo json_encode([
        'success' => false, 
        'message' => 'Datos inválidos o formato incorrecto'
    ]);
    exit();
}

// Verificar que tenga la estructura esperada
if (!isset($data['people']) || !is_array($data['people'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Falta el campo "people" o no es un array'
    ]);
    exit();
}

// ============================================================
// CREAR CARPETAS SI NO EXISTEN
// ============================================================

$carpeta = __DIR__ . '/datos';
$backupDir = $carpeta . '/backups/';

// Crear carpeta principal
if (!file_exists($carpeta)) {
    if (!mkdir($carpeta, 0777, true)) {
        echo json_encode([
            'success' => false, 
            'message' => 'No se pudo crear la carpeta "datos"'
        ]);
        exit();
    }
}

// Crear carpeta de backups
if (!file_exists($backupDir)) {
    mkdir($backupDir, 0777, true);
}

// ============================================================
// GUARDAR ARCHIVO
// ============================================================

$archivo = $carpeta . '/control_data.json';

// Si existe archivo anterior, hacer backup
if (file_exists($archivo)) {
    $timestamp = date('Y-m-d_H-i-s');
    $backupFile = $backupDir . 'backup_' . $timestamp . '.json';
    copy($archivo, $backupFile);
    
    // Limitar backups (guardar solo los últimos 20)
    $backups = glob($backupDir . 'backup_*.json');
    if (count($backups) > 20) {
        usort($backups, function($a, $b) {
            return filemtime($a) - filemtime($b);
        });
        $eliminar = array_slice($backups, 0, count($backups) - 20);
        foreach ($eliminar as $file) {
            unlink($file);
        }
    }
}

// Guardar datos con formato legible
$jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$resultado = file_put_contents($archivo, $jsonData);

// Verificar si se guardó correctamente
if ($resultado !== false) {
    // Registrar en log
    $log = date('Y-m-d H:i:s') . " - Datos guardados - " . count($data['people']) . " personas\n";
    file_put_contents($carpeta . '/log.txt', $log, FILE_APPEND);
    
    echo json_encode([
        'success' => true,
        'message' => 'Datos guardados correctamente',
        'timestamp' => date('Y-m-d H:i:s'),
        'personas' => count($data['people']),
        'archivo' => 'datos/control_data.json'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar el archivo. Verifica permisos de escritura.'
    ]);
}
?>