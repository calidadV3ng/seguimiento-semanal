<?php
// ============================================================
// ver.php - Ver los datos guardados
// ============================================================

header('Content-Type: text/html; charset=utf-8');

$archivo = __DIR__ . '/datos/control_data.json';

echo "<!DOCTYPE html>
<html>
<head>
    <title>📊 Datos Guardados</title>
    <style>
        body { background: #0d1b2a; color: #d6e6f5; font-family: monospace; padding: 2rem; }
        h1 { color: #8db4d4; }
        .info { background: #1a2d3e; padding: 1rem; border-radius: 8px; margin: 1rem 0; border: 1px solid #2a4055; }
        pre { background: #0f1f2e; padding: 1rem; border-radius: 8px; overflow-x: auto; border: 1px solid #1e3347; }
        .volver { display: inline-block; background: #2a5f7a; color: white; padding: 0.5rem 1rem; border-radius: 8px; text-decoration: none; margin-top: 1rem; }
        .volver:hover { background: #3a7a9a; }
    </style>
</head>
<body>";

echo "<h1>📊 Datos Guardados</h1>";

if (!file_exists($archivo)) {
    echo "<div class='info'>❌ No hay datos guardados todavía</div>";
    echo "<a href='index.html' class='volver'>← Volver</a>";
    echo "</body></html>";
    exit();
}

$contenido = file_get_contents($archivo);
$data = json_decode($contenido, true);

if (!$data) {
    echo "<div class='info'>❌ Error al leer los datos</div>";
    echo "<a href='index.html' class='volver'>← Volver</a>";
    echo "</body></html>";
    exit();
}

$personas = isset($data['people']) ? count($data['people']) : 0;
$semanaActual = isset($data['semanaActual']) ? $data['semanaActual'] + 1 : 'No disponible';
$tamano = round(filesize($archivo) / 1024, 2);

echo "<div class='info'>";
echo "👥 Personas: $personas | ";
echo "📅 Semana: $semanaActual | ";
echo "📦 Tamaño: $tamano KB | ";
echo "📅 Modificado: " . date('Y-m-d H:i:s', filemtime($archivo));
echo "</div>";

echo "<h2>📋 Contenido:</h2>";
echo "<pre>" . htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";

echo "<a href='index.html' class='volver'>← Volver al inicio</a>";
echo "</body></html>";
?>