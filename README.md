# MainServer v2 🚀

Un servidor web minimalista construido con PHP que incluye sistema de autenticación, panel de control administrativo y consola del sistema.

## ✨ Características

### 🔐 Sistema de Autenticación
- Login y registro de usuarios
- Roles de usuario (normal, admin, owner)
- Gestión de sesiones segura
- Interfaz con iconos SVG elegantes para mostrar/ocultar contraseñas

### 🎨 Interfaz de Usuario
- Diseño minimalista y responsive
- Navbar dinámica que se adapta según el rol del usuario
- Páginas: Inicio, Servicios, Tools, Docs
- Estilos CSS globales consistentes

### ⚙️ Panel de Control (Solo Admin/Owner)
- **Consola del Sistema**: Ejecuta comandos del sistema de forma segura
- **Acciones del Sistema**: 
  - Reiniciar/Apagar servidor PHP
  - Reiniciar/Apagar Raspberry Pi
- Medidas de seguridad: confirmación + countdown de 3 segundos
- Lista negra de comandos peligrosos

### 🛡️ Seguridad
- Validación de roles en todas las APIs
- Comandos del sistema con whitelist/blacklist
- Timeouts para prevenir bloqueos
- Cierre automático de sesiones antes de acciones críticas
- Logs de todas las acciones administrativas

## 🏗️ Estructura del Proyecto

```
MainServer_v2/
├── start-php-server.bat          # Script para iniciar el servidor
├── public/                       # Directorio público del servidor
│   ├── index.html               # Página principal
│   ├── router.php               # Enrutador del servidor
│   ├── api/                     # APIs backend
│   │   ├── db.php              # Configuración de base de datos
│   │   ├── login.php           # API de login
│   │   ├── logout.php          # API de logout
│   │   ├── register.php        # API de registro
│   │   ├── user_role.php       # API de roles de usuario
│   │   ├── execute_command.php # API de consola del sistema
│   │   ├── system_actions.php  # API de acciones del sistema
│   │   └── users.sqlite        # Base de datos SQLite
│   ├── pages/                   # Páginas HTML
│   │   ├── servicios.html
│   │   ├── tools.html
│   │   ├── docs.html
│   │   └── control-panel.html  # Panel de control administrativo
│   ├── src/                     # Componentes frontend
│   │   ├── navbar.html         # Componente navbar
│   │   └── navbar.js           # Lógica de navegación y auth
│   └── styles/
│       └── global.css          # Estilos globales
```

## 🚀 Instalación y Uso

### Prerrequisitos
- PHP 7.4 o superior
- Extensión SQLite para PHP
- Git (para clonar el repositorio)

### Pasos de instalación

1. **Clonar el repositorio**
   ```bash
   git clone [URL_DEL_REPOSITORIO]
   cd MainServer_v2
   ```

2. **Iniciar el servidor**
   
   En Windows:
   ```cmd
   start-php-server.bat
   ```
   
   En Linux/Raspberry Pi:
   ```bash
   cd public
   php -S localhost:8000 router.php
   ```

3. **Acceder a la aplicación**
   - Abrir navegador en `http://localhost:8000`
   - Crear una cuenta o usar credenciales existentes

### Crear usuario administrador

Para crear el primer usuario owner, puedes usar el script:
```bash
# Acceder a la consola del sistema en el panel de control
# O ejecutar directamente:
cd public/api
php create_admin_owner.php
```

## 🖥️ Funcionalidades del Panel de Control

### Consola del Sistema
- Ejecuta comandos del sistema operativo
- Interfaz tipo terminal con syntax highlighting
- Timeout de 30 segundos por comando
- Historial de comandos y resultados

### Acciones del Sistema
- **Reiniciar Servidor**: Reinicia solo el proceso PHP
- **Apagar Servidor**: Detiene el servidor web
- **Reiniciar Raspberry**: Reinicia todo el sistema (sudo reboot)
- **Apagar Raspberry**: Apaga el sistema (sudo shutdown)

⚠️ **Nota**: Las acciones de Raspberry Pi requieren permisos sudo y solo funcionan en sistemas Linux.

## 🔒 Configuración de Seguridad

### Comandos Prohibidos
La consola del sistema incluye una lista negra de comandos peligrosos:
- `rm -rf /`
- `dd if=`
- `mkfs`
- `passwd`
- Y otros comandos potencialmente destructivos

### Roles de Usuario
- **normal**: Acceso básico a páginas públicas
- **admin**: Acceso al panel de control
- **owner**: Acceso completo + capacidad de crear otros admins

## 📝 Desarrollo

Este proyecto está diseñado para ser:
- **Minimalista**: Código limpio y fácil de entender
- **Seguro**: Validaciones en frontend y backend
- **Extensible**: Fácil añadir nuevas funcionalidades
- **Portable**: Funciona en Windows, Linux y Raspberry Pi

## 🤝 Contribuir

1. Fork el proyecto
2. Crea una rama para tu feature (`git checkout -b feature/AmazingFeature`)
3. Commit tus cambios (`git commit -m 'Add some AmazingFeature'`)
4. Push a la rama (`git push origin feature/AmazingFeature`)
5. Abre un Pull Request

## 📄 Licencia

Este proyecto está bajo la Licencia MIT - ver el archivo [LICENSE](LICENSE) para detalles.

## 📞 Contacto

- **Proyecto**: MainServer v2
- **Versión**: 2.0.0
- **Fecha**: Septiembre 2025

---

⭐ ¡Si te gusta este proyecto, dale una estrella en GitHub!