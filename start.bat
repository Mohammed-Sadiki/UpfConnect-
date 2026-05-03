@echo off
echo.
echo  ╔══════════════════════════════════════╗
echo  ║    UniConnect — Serveur local        ║
echo  ║    http://127.0.0.1:8000             ║
echo  ╚══════════════════════════════════════╝
echo.
cd /d d:\laravel\uniconnect
php -c php-custom.ini artisan serve --host=127.0.0.1 --port=8000
pause
