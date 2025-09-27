
# Crear estructura de carpetas
mkdir src\pages
mkdir src\components
mkdir src\styles

# Crear archivos vacíos
New-Item src\pages\Home.jsx -ItemType File
New-Item src\pages\Login.jsx -ItemType File
New-Item src\pages\Dashboard.jsx -ItemType File
New-Item src\pages\Console.jsx -ItemType File
New-Item src\pages\Editor.jsx -ItemType File

New-Item src\components\Navbar.jsx -ItemType File
New-Item src\components\Card.jsx -ItemType File

New-Item src\styles\global.css -ItemType File
New-Item src\styles\dashboard.css -ItemType File

# Mensaje final
Write-Host "✅ Proyecto rasp-ui creado con estructura completa."
Write-Host "Entra con: cd rasp-ui"
Write-Host "Ejecuta en desarrollo con: npm run dev"
