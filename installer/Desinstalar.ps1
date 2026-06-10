$ErrorActionPreference = "Continue"
$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path

Write-Host "========================================" -ForegroundColor Red
Write-Host "   DESINSTALAR TI CONTROL" -ForegroundColor Red
Write-Host "========================================" -ForegroundColor Red

$opcion = Read-Host "`nEliminar tambien base de datos y archivos? (S/N)"
$rmData = $opcion -eq "S" -or $opcion -eq "s"

# Accesos directos
$desktop = Join-Path ([Environment]::GetFolderPath("Desktop")) "TI Control.lnk"
if (Test-Path $desktop) { Remove-Item $desktop -Force; Write-Host "  [OK] Acceso directo del escritorio eliminado" -ForegroundColor Green }

$startMenu = Join-Path ([Environment]::GetFolderPath("StartMenu")) "Programs\TI Control"
if (Test-Path $startMenu) { Remove-Item $startMenu -Recurse -Force; Write-Host "  [OK] Menu inicio eliminado" -ForegroundColor Green }

if ($rmData) {
    $dbLink = Join-Path $ScriptDir "app\database\database.sqlite"
    if (Test-Path $dbLink) { Remove-Item $dbLink -Force }
    $dataDir = Join-Path $ScriptDir "data"
    if (Test-Path $dataDir) { Remove-Item $dataDir -Recurse -Force }
    $removeAll = Read-Host "`nEliminar toda la carpeta de instalacion? (S/N)"
    if ($removeAll -eq "S" -or $removeAll -eq "s") {
        Push-Location $env:TEMP
        Remove-Item $ScriptDir -Recurse -Force
        Write-Host "  [OK] Instalacion eliminada" -ForegroundColor Green
    }
} else {
    Write-Host "  [i] Datos conservados en la carpeta de instalacion" -ForegroundColor Yellow
}
Write-Host "`nDesinstalacion completada." -ForegroundColor Green
Read-Host "Presione Enter para salir"
