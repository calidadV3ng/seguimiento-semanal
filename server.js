const express = require('express');
const fs = require('fs');
const path = require('path');
const app = express();

app.use(express.json());
app.use(express.static('.'));

// Intentar usar el disco persistente si existe, sino usar local
const DATA_DIR = process.env.RENDER_DISK_PATH || '.';
const DATA_FILE = path.join(DATA_DIR, 'datos.json');

// Inicializar archivo si no existe
if (!fs.existsSync(DATA_FILE)) {
  fs.writeFileSync(DATA_FILE, JSON.stringify({ 
    personas: [], 
    semanaActual: 0,
    archivos: []
  }, null, 2));
}

// Ruta para guardar datos
app.post('/api/guardar', (req, res) => {
  try {
    fs.writeFileSync(DATA_FILE, JSON.stringify(req.body, null, 2));
    res.json({ ok: true, mensaje: '✅ Datos guardados correctamente' });
  } catch (e) {
    res.status(500).json({ ok: false, error: e.message });
  }
});

// Ruta para cargar datos
app.get('/api/cargar', (req, res) => {
  try {
    if (fs.existsSync(DATA_FILE)) {
      const data = fs.readFileSync(DATA_FILE, 'utf8');
      const jsonData = JSON.parse(data);
      res.json(jsonData);
    } else {
      res.json({ personas: [], semanaActual: 0, archivos: [] });
    }
  } catch (e) {
    console.error('Error al cargar:', e);
    res.json({ personas: [], semanaActual: 0, archivos: [] });
  }
});

const PORT = process.env.PORT || 3000;
app.listen(PORT, () => {
  console.log(`🚀 Servidor corriendo en http://localhost:${PORT}`);
  console.log(`📁 Datos guardados en: ${DATA_FILE}`);
});