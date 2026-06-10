@echo off
title TI Control - Sistema de Gesti\u00f3n TI
cd /d "%~dp0"

set PHP_DIR=%~dp0php
set APP_DIR=%~dp0app
set DATA_DIR=%~dp0data
set PORT=8080
set HOST=127.0.0.1

if not exist "%PHP_DIR%\php.exe" (
    echo [ERROR] No se encontr\u00f3 PHP en %PHP_DIR%
    echo Ejecute Instalar.bat primero para configurar el entorno.
    pause
    exit /b 1
)

if not exist "%DATA_DIR%" mkdir "%DATA_DIR%"
if not exist "%APP_DIR%\database\database.sqlite" (
    if exist "%DATA_DIR%\database.sqlite" (
        mklink "%APP_DIR%\database\database.sqlite" "%DATA_DIR%\database.sqlite" 2>nul
        if errorlevel 1 copy "%DATA_DIR%\database.sqlite" "%APP_DIR%\database\database.sqlite" >nul
    )
)
if not exist "%APP_DIR%\public\storage" (
    "%PHP_DIR%\php.exe" "%APP_DIR%\artisan" storage:link --quiet 2>nul
)

for %%d in (respaldos respaldos_correos respaldos_bd renovaciones backups logs framework\sessions framework\views framework\cache) do (
    if not exist "%APP_DIR%\storage\%%d" mkdir "%APP_DIR%\storage\%%d"
)

set APP_ENV=production
set APP_DEBUG=false
set DB_CONNECTION=sqlite
set DB_DATABASE=%APP_DIR%\database\database.sqlite

echo ============================================
echo       TI Control - Sistema de Gesti\u00f3n TI
echo ============================================
echo.
echo Iniciando servidor en http://%HOST%:%PORT%
echo.
echo [IMPORTANTE] No cierre esta ventana.
echo Presione cualquier tecla para DETENER.
echo.

start "" /b "%PHP_DIR%\php.exe" "%APP_DIR%\artisan" serve --host=%HOST% --port=%PORT%
timeout /t 3 /nobreak >nul
start http://%HOST%:%PORT%
pause >nul
taskkill /f /im php.exe >nul 2>&1
echo Servidor detenido.
timeout /t 2 /nobreak >nul
