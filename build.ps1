$ErrorActionPreference = "Stop"
$ProjectRoot = Split-Path -Parent $MyInvocation.MyCommand.Path
$DistDir = Join-Path $ProjectRoot "dist"
$AppDir = Join-Path $DistDir "app"
$PhpDir = Join-Path $DistDir "php"

function Write-Step { param([string]$Message) Write-Host ">> $Message" -ForegroundColor Cyan }

# === 1. Clean dist ===
Write-Step "Limpiando dist..."
if (Test-Path $DistDir) {
    Remove-Item "$DistDir\*" -Recurse -Force -ErrorAction SilentlyContinue
}
@('app', 'php', 'data') | ForEach-Object {
    New-Item -ItemType Directory -Path (Join-Path $DistDir $_) -Force | Out-Null
}

# === 2. Copy Laravel app (exclude dev files) ===
Write-Step "Copiando aplicación..."
$exclude = @('.git', '.github', 'node_modules', 'dist', 'installer', 'storage\framework\*', 'storage\logs\*', 'storage\app\backups\*', 'build.ps1', '.env', '.env.example.bak')
Get-ChildItem -Path $ProjectRoot -Exclude $exclude | Where-Object { $_.Name -notin @('.git', 'node_modules', 'dist', 'installer') } | ForEach-Object {
    if ($_.PSIsContainer) {
        Copy-Item -Path $_.FullName -Destination (Join-Path $AppDir $_.Name) -Recurse -Container -ErrorAction SilentlyContinue
    } else {
        Copy-Item -Path $_.FullName -Destination (Join-Path $AppDir $_.Name) -ErrorAction SilentlyContinue
    }
}

# === 3. Copy storage structure ===
Write-Step "Preparando storage..."
@('app\respaldos', 'app\respaldos_correos', 'app\respaldos_bd', 'app\renovaciones', 'app\backups', 'logs', 'framework\sessions', 'framework\views', 'framework\cache') | ForEach-Object {
    $p = Join-Path $AppDir "storage\$_"
    if (-not (Test-Path $p)) { New-Item -ItemType Directory -Path $p -Force | Out-Null }
}

# === 4. Copy database from data if exists ===
$dbFrom = Join-Path $DistDir "data\database.sqlite"
$dbTo = Join-Path $AppDir "database\database.sqlite"
if (Test-Path $dbFrom) {
    Copy-Item $dbFrom $dbTo -Force
}

# === 5. Download PHP portable ===
if (-not (Test-Path "$PhpDir\php.exe")) {
    Write-Step "Descargando PHP portable..."
    $phpUrl = "https://windows.php.net/downloads/releases/php-8.2.31-nts-Win32-vs16-x64.zip"
    $phpZip = Join-Path $env:TEMP "php-portable.zip"
    
    $wc = New-Object System.Net.WebClient
    $wc.DownloadFile($phpUrl, $phpZip)
    Add-Type -AssemblyName System.IO.Compression.FileSystem
    [System.IO.Compression.ZipFile]::ExtractToDirectory($phpZip, $PhpDir)
    Remove-Item $phpZip -Force
    
    # php.ini
    $iniDev = Join-Path $PhpDir "php.ini-development"
    $iniPath = Join-Path $PhpDir "php.ini"
    if (Test-Path $iniDev) { Copy-Item $iniDev $iniPath }
    if (Test-Path $iniPath) {
        $ini = Get-Content $iniPath
        $ini = $ini -replace ';extension_dir = "ext"', 'extension_dir = "ext"'
        @('extension=curl', 'extension=fileinfo', 'extension=gd', 'extension=mbstring', 'extension=openssl', 'extension=pdo_sqlite', 'extension=zip') | ForEach-Object {
            $ini = $ini -replace ";$_", $_
        }
        $ini | Set-Content $iniPath
    }
    Write-Step "PHP portable listo"
} else {
    Write-Step "PHP ya existe"
}

# === 6. Copy scripts ===
Write-Step "Copiando scripts..."
@('Iniciar.bat', 'Instalar.bat', 'Instalar.ps1', 'Desinstalar.ps1') | ForEach-Object {
    Copy-Item (Join-Path $ProjectRoot "installer\$_") (Join-Path $DistDir $_) -Force
}

# === 7. Create .env for production ===
Write-Step "Configurando .env..."
$envContent = @"
APP_NAME=TI_Control
APP_ENV=production
APP_DEBUG=false
APP_URL=http://127.0.0.1:8080

DB_CONNECTION=sqlite
DB_DATABASE="$(Join-Path $AppDir "database\database.sqlite")"

SESSION_DRIVER=file
LOG_CHANNEL=stack
LOG_LEVEL=error
CACHE_STORE=file
"@
$envContent | Set-Content (Join-Path $AppDir ".env") -Encoding UTF8

# === 8. Optimize autoload ===
Write-Step "Optimizando autoload..."
Set-Location $AppDir
if (Get-Command composer -ErrorAction SilentlyContinue) {
    composer dump-autoload --no-dev --quiet 2>&1 | Out-Null
}
Set-Location $ProjectRoot

Write-Step "Build completado!"
Write-Host "`nEl paquete portable está en: $DistDir" -ForegroundColor Green
Write-Host "Para distribuir, comprima la carpeta dist en un ZIP." -ForegroundColor Green
