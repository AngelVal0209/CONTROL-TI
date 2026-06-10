<?php

namespace App\Http\Controllers\Backup;
use App\Http\Controllers\Controller;

use App\Models\Area;
use App\Models\Auditoria;
use App\Models\Configuracion;
use App\Models\Equipo;
use App\Models\Incidente;
use App\Models\Mantenimiento;
use App\Models\Marca;
use App\Models\Modelo;
use App\Models\Puesto;
use App\Models\Renovacion;
use App\Models\Respaldo;
use App\Models\RespaldoBd;
use App\Models\RespaldoCorreo;
use App\Models\Tipo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use ZipArchive;

class BackupController extends Controller
{
    public function index()
    {
        $backups = collect(Storage::disk('local')->files('backups'))
            ->filter(fn ($f) => str_ends_with($f, '.zip'))
            ->map(fn ($f) => [
                'nombre' => basename($f),
                'tamano' => $this->formatBytes(Storage::disk('local')->size($f)),
                'fecha' => date('Y-m-d H:i:s', Storage::disk('local')->lastModified($f)),
                'ruta' => $f,
            ])
            ->sortByDesc('fecha')
            ->values();

        return Inertia::render('Backup/Index', [
            'backups' => $backups,
        ]);
    }

    public function export()
    {
        $tables = [
            'equipos' => Equipo::all()->toArray(),
            'incidentes' => Incidente::all()->toArray(),
            'configuraciones' => DB::table('configuraciones')->get()->toArray(),
            'mantenimientos' => Mantenimiento::all()->toArray(),
            'respaldos' => Respaldo::all()->toArray(),
            'respaldos_correos' => RespaldoCorreo::all()->toArray(),
            'respaldos_bd' => RespaldoBd::all()->toArray(),
            'renovaciones' => Renovacion::all()->toArray(),
            'usuarios' => User::all()->toArray(),
            'areas' => Area::all()->toArray(),
            'puestos' => Puesto::all()->toArray(),
            'tipos' => Tipo::all()->toArray(),
            'marcas' => Marca::all()->toArray(),
            'modelos' => Modelo::all()->toArray(),
            'auditoria' => Auditoria::all()->toArray(),
        ];

        $json = json_encode($tables, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $filename = 'backup_' . date('Ymd_His') . '.zip';
        $zipPath = storage_path("app/backups/{$filename}");

        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        $zip = new ZipArchive();
        if ($zip->open($zipPath, ZipArchive::CREATE) === true) {
            $zip->addFromString('datos.json', $json);

            $directories = ['respaldos_correos', 'respaldos_bd', 'respaldos', 'renovaciones'];
            foreach ($directories as $dir) {
                $files = Storage::disk('local')->files($dir);
                foreach ($files as $file) {
                    $localPath = storage_path("app/{$file}");
                    if (file_exists($localPath)) {
                        $zip->addFile($localPath, $file);
                    }
                }
            }

            $zip->close();
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'archivo' => 'required|file|mimes:zip,rar,7z,tar,gz,bak|max:1024000',
        ]);

        $zipFile = $request->file('archivo');
        $extractPath = storage_path('app/backups/extract_' . uniqid());

        $zip = new ZipArchive();
        if ($zip->open($zipFile->getPathname()) !== true) {
            return back()->with('error', 'No se pudo abrir el archivo ZIP.');
        }
        $zip->extractTo($extractPath);
        $zip->close();

        $jsonPath = $extractPath . '/datos.json';
        if (!file_exists($jsonPath)) {
            $this->cleanup($extractPath);
            return back()->with('error', 'Archivo de datos no encontrado en el ZIP.');
        }

        $data = json_decode(file_get_contents($jsonPath), true);
        if (!$data) {
            $this->cleanup($extractPath);
            return back()->with('error', 'Formato de datos invÃƒÆ’Ã‚Â¡lido.');
        }

        $modelMap = [
            'equipos' => Equipo::class,
            'incidentes' => Incidente::class,
            'configuraciones' => Configuracion::class,
            'mantenimientos' => Mantenimiento::class,
            'respaldos' => Respaldo::class,
            'respaldos_correos' => RespaldoCorreo::class,
            'respaldos_bd' => RespaldoBd::class,
            'renovaciones' => Renovacion::class,
            'usuarios' => User::class,
            'areas' => Area::class,
            'puestos' => Puesto::class,
            'tipos' => Tipo::class,
            'marcas' => Marca::class,
            'modelos' => Modelo::class,
            'auditoria' => Auditoria::class,
        ];

        $errors = [];
        foreach ($modelMap as $key => $model) {
            if (!isset($data[$key])) continue;
            foreach ($data[$key] as $row) {
                try {
                    $model::updateOrCreate(['id' => $row['id'] ?? null], $row);
                } catch (\Exception $e) {
                    $errors[] = "{$key} #{$row['id']}: {$e->getMessage()}";
                }
            }
        }

        // Restore files
        foreach (['respaldos_correos', 'respaldos_bd', 'respaldos', 'renovaciones'] as $dir) {
            $srcDir = $extractPath . '/' . $dir;
            if (is_dir($srcDir)) {
                $files = scandir($srcDir);
                foreach ($files as $file) {
                    if ($file === '.' || $file === '..') continue;
                    $destPath = storage_path("app/{$dir}/{$file}");
                    if (!is_dir(dirname($destPath))) {
                        mkdir(dirname($destPath), 0755, true);
                    }
                    copy("{$srcDir}/{$file}", $destPath);
                }
            }
        }

        $this->cleanup($extractPath);

        if (!empty($errors)) {
            return back()->with('warning', 'RestauraciÃƒÆ’Ã‚Â³n completada con errores: ' . implode('; ', array_slice($errors, 0, 5)));
        }

        return back()->with('success', 'Datos restaurados correctamente desde la copia de seguridad.');
    }

    private function cleanup($path)
    {
        if (is_dir($path)) {
            array_map('unlink', glob("{$path}/*.*"));
            $subDirs = glob("{$path}/*", GLOB_ONLYDIR);
            foreach ($subDirs as $subDir) {
                array_map('unlink', glob("{$subDir}/*.*"));
                rmdir($subDir);
            }
            rmdir($path);
        }
    }

    private function formatBytes($bytes, $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / pow(1024, $pow), $precision) . ' ' . $units[$pow];
    }
}


