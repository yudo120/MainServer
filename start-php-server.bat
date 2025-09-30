@echo off
REM Inicia un servidor PHP embebido en Windows en el puerto 8000 con router
cd public
php -S localhost:8000 router.php
