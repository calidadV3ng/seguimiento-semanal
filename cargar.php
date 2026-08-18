<?php
// ============================================================
// cargar.php - Carga los datos desde el servidor
// ============================================================

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'success' => false, 
        'message' => 'Método no permitido. Usa GET.'
    ]);
    exit();
}

$archivo = __DIR__ . '/datos/control_data.json';

if (!file_exists($archivo)) {
    echo json_encode([
        'success' => false,
        'message' => 'No hay datos guardados todavía',
        'data' => null
    ]);
    exit();
}

$contenido = file_get_contents($archivo);

if ($contenido === false) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al leer el archivo',
        'data' => null
    ]);
    exit();
}

$data = json_decode($contenido, true);

if (!$data) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al decodificar JSON',
        'data' => null
    ]);
    exit();
}

if (!isset($data['people']) || !is_array($data['people'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Estructura de datos inválida',
        'data' => null
    ]);
    exit();
}

echo json_encode([
    'success' => true,
    'message' => 'Datos cargados correctamente',
    'data' => $data,
    'timestamp' => date('Y-m-d H:i:s', filemtime($archivo))
]);
?>