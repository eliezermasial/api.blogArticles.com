<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeService extends Command
{
    protected $signature = 'make:service {name}';
    protected $description = 'Créer un service basé sur un stub';

    public function handle()
    {
        $name = ucfirst($this->argument('name'));

        $stubPath = base_path('stubs/service.stub');

        if (!File::exists($stubPath)) {
            $this->error("Le fichier stub n'existe pas : {$stubPath}");
            return;
        }

        $stub = File::get($stubPath);
        $content = str_replace('{{name}}', $name, $stub);

        $directory = app_path('Services');
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $filePath = "{$directory}/{$name}.php";
        if (File::exists($filePath)) {
            $this->error("Le fichier existe déjà : {$filePath}");
            return;
        }

        File::put($filePath, $content);
        $this->info("Service {$name} créé avec succès !");
    }
}
