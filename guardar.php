<?php
// ============================================================
// guardar.php - Guarda los datos en el servidor
// ============================================================

// Configurar cabeceras
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Activar reporte de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Si es OPTIONS, responder OK
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Solo POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false, 
        'message' => 'Método no permitido. Usa POST.'
    ]);
    exit();
}

// Obtener datos
$input = file_get_contents('php://input');
$data = json_decode($input, true);

if (!$data) {
    echo json_encode([
        'success' => false, 
        'message' => 'Datos inválidos'
    ]);
    exit();
}

// Verificar estructura
if (!isset($data['people']) || !is_array($data['people'])) {
    echo json_encode([
        'success' => false, 
        'message' => 'Falta el campo "people"'
    ]);
    exit();
}

// ============================================================
// CREAR CARPETAS
// ============================================================

$carpeta = __DIR__ . '/datos';

// Verificar si existe la carpeta
if (!file_exists($carpeta)) {
    $creado = mkdir($carpeta, 0777, true);
    if (!$creado) {
        echo json_encode([
            'success' => false,
            'message' => 'No se pudo crear la carpeta "datos"'
        ]);
        exit();
    }
}

// Verificar permisos
if (!is_writable($carpeta)) {
    echo json_encode([
        'success' => false,
        'message' => 'La carpeta "datos" no tiene permisos de escritura'
    ]);
    exit();
}

// ============================================================
// GUARDAR ARCHIVO
// ============================================================

$archivo = $carpeta . '/control_data.json';

// Agregar timestamp si no existe
if (!isset($data['timestamp'])) {
    $data['timestamp'] = date('Y-m-d H:i:s');
}

// Guardar
$jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
$resultado = file_put_contents($archivo, $jsonData);

if ($resultado !== false) {
    echo json_encode([
        'success' => true,
        'message' => 'Datos guardados correctamente',
        'timestamp' => date('Y-m-d H:i:s'),
        'personas' => count($data['people'])
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Error al guardar el archivo'
    ]);
}
?>