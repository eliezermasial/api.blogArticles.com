<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRepository extends Command
{
    protected $signature = 'make:repository {name}';
    protected $description = 'Créer un repository basé sur un stub';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));

        $stubPath = base_path('stubs/repository.stub');

        if (!File::exists($stubPath)) {
            $this->error("Le fichier stub n'existe pas : {$stubPath}");
            return;
        }

        $stub = File::get($stubPath);
        $content = str_replace('{{name}}', $name, $stub);

        $directory = app_path('Repositories');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/{$name}Repository.php";
        if (File::exists($filePath)) {
            $this->error("Le fichier existe déjà : {$filePath}");
            return;
        }

        File::put($filePath, $content);
        $this->info("Repository {$name}Repository créé avec succès !");
    }
}
