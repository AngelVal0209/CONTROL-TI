@echo off
title Instalador TI Control
cd /d "%~dp0"
echo ============================================
echo    Instalador de TI Control
echo ============================================
echo.

if exist "%~dp0php\php.exe" (
    echo [OK] PHP encontrado
) else (
    echo [INFO] Descargando PHP portable...
    powershell -ExecutionPolicy Bypass -Command "& { \
        try { \
            \$url = 'https://windows.php.net/downloads/releases/php-8.2.31-nts-Win32-vs16-x64.zip'; \
            \$zip = \"$env:TEMP\php.zip\"; \
            Write-Host 'Descargando PHP 8.2...'; \
            (New-Object Net.WebClient).DownloadFile(\$url, \$zip); \
            Add-Type -AssemblyName System.IO.Compression.FileSystem; \
            [System.IO.Compression.ZipFile]::ExtractToDirectory(\$zip, '%~dp0php'); \
            Remove-Item \$zip -Force; \
            Write-Host 'PHP descargado y extraido'; \
        } catch { Write-Host 'Error: ' + \$_.Exception.Message; exit 1 } \
    }"
    if not exist "%~dp0php\php.exe" (
        echo [ERROR] No se pudo descargar PHP.
        pause
        exit /b 1
    )
    echo [OK] PHP instalado
)

echo.
echo Configurando sistema...
powershell -ExecutionPolicy Bypass -File "%~dp0Instalar.ps1"
if %errorlevel% neq 0 (
    echo [ERROR] Error durante la instalacion.
    pause
    exit /b 1
)
pause
