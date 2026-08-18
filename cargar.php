<?php
// ============================================================
// cargar.php - Carga los datos desde el servidor
// ============================================================

// Configurar cabeceras
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
header('Content-Type: application/json');

// Si es una petición OPTIONS (preflight), responder OK
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Solo aceptar GET
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    echo json_encode([
        'success' => false, 
        'message' => 'Método no permitido. Usa GET.'
    ]);
    exit();
}

// ============================================================
// RUTA DEL ARCHIVO
// ============================================================

$archivo = __DIR__ . '/datos/control_data.json';

// ============================================================
// CARGAR DATOS
// ============================================================

if (!file_exists($archivo)) {
    echo json_encode([
        'success' => false,
        'message' => 'No hay datos guardados todavía',
        'data' => null,
        'existe' => false
    ]);
    exit();
}

// Leer archivo
$contenido = file_get_contents($archivo);

if ($contenido === false) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al leer el archivo',
        'data' => null
    ]);
    exit();
}

// Decodificar JSON
$data = json_decode($contenido, true);

if (!$data) {
    echo json_encode([
        'success' => false,
        'message' => 'Error al decodificar el JSON',
        'data' => null
    ]);
    exit();
}

// Verificar estructura
if (!isset($data['people']) || !is_array($data['people'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Estructura de datos inválida',
        'data' => null
    ]);
    exit();
}

// ============================================================
// RESPONDER CON LOS DATOS
// ============================================================

$stats = [
    'personas' => count($data['people']),
    'tamano' => round(filesize($archivo) / 1024, 2) . ' KB',
    'modificado' => date('Y-m-d H:i:s', filemtime($archivo))
];

echo json_encode([
    'success' => true,
    'message' => 'Datos cargados correctamente',
    'data' => $data,
    'stats' => $stats,
    'timestamp' => date('Y-m-d H:i:s')
]);
?>