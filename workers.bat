@echo off
echo Iniciando 3 Workers de Fila...
echo.

start "Worker 1" cmd /k "php artisan queue:work database --queue=default --timeout=0 --tries=1"
timeout /t 1 > nul

start "Worker 2" cmd /k "php artisan queue:work database --queue=default --timeout=0 --tries=1"
timeout /t 1 > nul

start "Worker 3" cmd /k "php artisan queue:work database --queue=default --timeout=0 --tries=1"

echo.
echo 3 Workers iniciados!
echo Feche as janelas para parar os workers.
pause
