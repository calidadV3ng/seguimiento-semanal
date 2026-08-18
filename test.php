<?php
// ============================================================
// test.php - Diagnóstico del servidor
// ============================================================

header('Content-Type: text/html; charset=utf-8');

echo "<!DOCTYPE html>
<html>
<head>
    <title>🔧 Diagnóstico</title>
    <style>
        body { background: #0d1b2a; color: #d6e6f5; font-family: monospace; padding: 2rem; }
        h1 { color: #8db4d4; }
        .ok { color: #6bc49a; }
        .error { color: #e08080; }
        .info { background: #1a2d3e; padding: 1rem; border-radius: 8px; margin: 0.5rem 0; border: 1px solid #2a4055; }
        pre { background: #0f1f2e; padding: 1rem; border-radius: 8px; overflow-x: auto; }
    </style>
</head>
<body>
<h1>🔧 Diagnóstico del Servidor</h1>";

echo "<div class='info'><strong>PHP Versión:</strong> " . phpversion() . "</div>";
echo "<div class='info'><strong>Directorio actual:</strong> " . __DIR__ . "</div>";

$carpeta = __DIR__ . '/datos';
if (file_exists($carpeta)) {
    echo "<div class='info ok'>✅ Carpeta 'datos' existe</div>";
    echo "<div class='info'>" . (is_writable($carpeta) ? "✅ Tiene permisos de escritura" : "❌ NO tiene permisos de escritura") . "</div>";
} else {
    echo "<div class='info error'>❌ Carpeta 'datos' NO existe</div>";
    echo "<div class='info'>💡 Crea la carpeta 'datos' manualmente con FTP</div>";
}

echo "<br><a href='index.html' style='color:#6fa8d4;'>← Volver al inicio</a>";
echo "</body></html>";
?>