<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Crea un respaldo de la base de datos (SQL dump)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $database = env('DB_DATABASE');
        $username = env('DB_USERNAME');
        $password = env('DB_PASSWORD');
        $host = env('DB_HOST', '127.0.0.1');
        $port = env('DB_PORT', '3306');

        $filename = "backup-" . date('Y-m-d-H-i-s') . ".sql";
        $path = storage_path("app/backups");

        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $filepath = $path . "/" . $filename;
        $passwordArg = $password ? "--password={$password}" : "";

        $command = "mysqldump --user={$username} {$passwordArg} --host={$host} --port={$port} {$database} > {$filepath}";

        $this->info("Iniciando respaldo de la base de datos: {$database}...");

        $output = null;
        $returnVar = null;
        exec($command, $output, $returnVar);

        if ($returnVar === 0) {
            $this->info("Backup guardado exitosamente: " . $filepath);
        }
        else {
            $this->error("Error creando el backup. Asegúrate de tener 'mysqldump' en tus variables de entorno.");
        }
    }
}
