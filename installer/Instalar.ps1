<#
.SYNOPSIS
    Instalador de TI Control - Sistema de Gesti\u00f3n de Activos TI
#>
$ErrorActionPreference = "Stop"
$ScriptDir = Split-Path -Parent $MyInvocation.MyCommand.Path
$AppDir = Join-Path $ScriptDir "app"
$PhpDir = Join-Path $ScriptDir "php"
$DataDir = Join-Path $ScriptDir "data"

function Write-Step { param([string]$M) Write-Host ">> $M" -ForegroundColor Cyan }

# === 1. Descargar PHP portable ===
if (-not (Test-Path "$PhpDir\php.exe")) {
    Write-Step "Descargando PHP 8.2 portable..."
    $url = "https://windows.php.net/downloads/releases/php-8.2.31-nts-Win32-vs16-x64.zip"
    $zip = Join-Path $env:TEMP "php.zip"
    $wc = New-Object System.Net.WebClient
    $wc.DownloadFile($url, $zip)
    Add-Type -AssemblyName System.IO.Compression.FileSystem
    [System.IO.Compression.ZipFile]::ExtractToDirectory($zip, $PhpDir)
    Remove-Item $zip -Force

    $iniPath = Join-Path $PhpDir "php.ini"
    if (Test-Path (Join-Path $PhpDir "php.ini-development")) {
        Copy-Item (Join-Path $PhpDir "php.ini-development") $iniPath
    }
    if (Test-Path $iniPath) {
        $ini = Get-Content $iniPath
        $ini = $ini -replace ';extension_dir = "ext"', 'extension_dir = "ext"'
        foreach ($ext in @('curl','fileinfo','gd','mbstring','openssl','pdo_sqlite','zip')) {
            $ini = $ini -replace ";extension=$ext", "extension=$ext"
        }
        $ini | Set-Content $iniPath
    }
    Write-Step "PHP portable listo"
}

# === 2. Directorio de datos ===
if (-not (Test-Path $DataDir)) { New-Item -ItemType Directory -Path $DataDir -Force | Out-Null }

# === 3. Inicializar BD ===
$dbPath = Join-Path $AppDir "database\database.sqlite"
if (-not (Test-Path $dbPath)) {
    Write-Step "Inicializando base de datos..."
    Push-Location $AppDir
    & "$PhpDir\php.exe" artisan migrate --force 2>&1 | Out-Null
    & "$PhpDir\php.exe" artisan db:seed --force 2>&1 | Out-Null
    Pop-Location
    Write-Step "Base de datos inicializada"
}

# === 4. Optimizar ===
Write-Step "Optimizando..."
Push-Location $AppDir
& "$PhpDir\php.exe" artisan config:cache --force 2>&1 | Out-Null
& "$PhpDir\php.exe" artisan route:cache --force 2>&1 | Out-Null
& "$PhpDir\php.exe" artisan view:cache --force 2>&1 | Out-Null
Pop-Location

# === 5. Accesos directos ===
Write-Step "Creando accesos directos..."
$desktop = [Environment]::GetFolderPath("Desktop")
$startMenu = Join-Path ([Environment]::GetFolderPath("StartMenu")) "Programs\TI Control"
if (-not (Test-Path $startMenu)) { New-Item -ItemType Directory -Path $startMenu -Force | Out-Null }
$ws = New-Object -ComObject WScript.Shell

$lnk = $ws.CreateShortcut((Join-Path $desktop "TI Control.lnk"))
$lnk.TargetPath = "cmd.exe"
$lnk.Arguments = "/c `"$ScriptDir\Iniciar.bat`""
$lnk.WorkingDirectory = $ScriptDir
$lnk.Description = "TI Control - Gesti\u00f3n de Activos TI"
$lnk.Save()

$lnk2 = $ws.CreateShortcut((Join-Path $startMenu "TI Control.lnk"))
$lnk2.TargetPath = "cmd.exe"
$lnk2.Arguments = "/c `"$ScriptDir\Iniciar.bat`""
$lnk2.WorkingDirectory = $ScriptDir
$lnk2.Description = "TI Control - Gesti\u00f3n de Activos TI"
$lnk2.Save()

$ulnk = $ws.CreateShortcut((Join-Path $startMenu "Desinstalar TI Control.lnk"))
$ulnk.TargetPath = "powershell.exe"
$ulnk.Arguments = "-ExecutionPolicy Bypass -File `"$ScriptDir\Desinstalar.ps1`""
$ulnk.Save()

Write-Host "`n================== INSTALACION COMPLETADA ==================" -ForegroundColor Green
Write-Host "`n  Para iniciar: haga doble clic en 'TI Control' del escritorio" -ForegroundColor White
Write-Host "  Usuario: 73823769 / Contrasena: 73823769" -ForegroundColor White
Write-Host "`n  No cierre la ventana de consola mientras usa el programa.`n" -ForegroundColor Yellow
Read-Host "Presione Enter para salir"
