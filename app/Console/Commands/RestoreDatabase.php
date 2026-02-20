<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use PDO;

class RestoreDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:restore {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Restaura una base de datos verificando si existe. Si no se especifica archivo, restaura el más reciente.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $file = $this->argument('file');
        $path = storage_path("app/backups");

        if (!$file) {
            if (!File::exists($path)) {
                $this->error("El directorio de backups no existe.");
                return;
            }
            $files = collect(File::files($path))->filter(function ($file) {
                return $file->getExtension() === 'sql';
            })->sortByDesc(function ($file) {
                return $file->getMTime();
            });

            if ($files->isEmpty()) {
                $this->error("No hay archivos .sql de backup disponibles.");
                return;
            }
            $filepath = $files->first()->getPathname();
        }
        else {
            $filepath = $path . "/" . $file;
            if (!File::exists($filepath)) {
                $this->error("El archivo no existe: " . $filepath);
                return;
            }
        }

        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');

        $this->info("Preparando para restaurar desde: " . basename($filepath));

        // Create Database if it does not exist
        try {
            $pdo = new PDO("mysql:host={$host};port={$port}", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$database}`");
            $this->info("Base de datos '{$database}' verificada / creada.");
        }
        catch (\PDOException $e) {
            $this->error("Error conectando a MySQL: " . $e->getMessage());
            return;
        }

        $passwordArg = $password ? "--password={$password}" : "";
        $command = "mysql --user={$username} {$passwordArg} --host={$host} --port={$port} {$database} < {$filepath}";

        $this->info("Restaurando tablas y datos...");
        $output = null;
        $returnVar = null;
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Base de datos restaurada exitosamente.");
        }
        else {
            $this->error("Error al restaurar. Asegúrate de tener 'mysql' en tus variables de entorno.");
        }
    }
}
