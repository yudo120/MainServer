#!/bin/bash
# Configuración para Apache en Raspberry Pi
# Ejecutar con: chmod +x setup-apache.sh && sudo ./setup-apache.sh

echo "🚀 Configurando MainServer v2 para Apache..."

# Verificar que Apache esté instalado
if ! command -v apache2 &> /dev/null; then
    echo "❌ Apache no está instalado. Instalando..."
    apt update
    apt install apache2 php libapache2-mod-php php-sqlite3 -y
fi

# Habilitar módulos necesarios
echo "📦 Habilitando módulos de Apache..."
a2enmod rewrite
a2enmod headers
a2enmod php8.1  # Ajustar según tu versión de PHP

# Configurar permisos del directorio
echo "🔐 Configurando permisos..."
WEBDIR="/var/www/rasp-server.io"

# Asegurar que el directorio exista
mkdir -p $WEBDIR/public/api
mkdir -p $WEBDIR/public/api/logs

# Configurar propietario y permisos
chown -R www-data:www-data $WEBDIR
chmod -R 755 $WEBDIR
chmod -R 777 $WEBDIR/public/api/logs  # Para logs
chmod 666 $WEBDIR/public/api/users.sqlite 2>/dev/null || echo "Base de datos se creará automáticamente"

# Crear configuración de sitio si no existe
SITE_CONFIG="/etc/apache2/sites-available/rasp-server.conf"
if [ ! -f "$SITE_CONFIG" ]; then
    echo "📝 Creando configuración de sitio..."
    cat > $SITE_CONFIG << EOF
<VirtualHost *:80>
    ServerName rasp-server.io
    ServerAlias www.rasp-server.io
    DocumentRoot $WEBDIR/public
    
    <Directory $WEBDIR/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
    
    # Logs
    ErrorLog \${APACHE_LOG_DIR}/rasp-server_error.log
    CustomLog \${APACHE_LOG_DIR}/rasp-server_access.log combined
</VirtualHost>
EOF
    
    # Habilitar el sitio
    a2ensite rasp-server.conf
fi

# Reiniciar Apache
echo "🔄 Reiniciando Apache..."
systemctl restart apache2
systemctl enable apache2

# Verificar estado
echo "✅ Verificando estado de Apache..."
systemctl status apache2 --no-pager -l

# Mostrar información útil
echo ""
echo "🎉 Configuración completada!"
echo "📍 Sitio web: http://$(hostname -I | awk '{print $1}')"
echo "📂 Directorio: $WEBDIR"
echo "📄 Logs: /var/log/apache2/rasp-server_*"
echo ""
echo "🔧 Para crear usuario admin, ejecuta:"
echo "cd $WEBDIR/public/api && php create_admin_owner.php"