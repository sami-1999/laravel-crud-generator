<?php

namespace MuhammadSami\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateCrud extends Command
{
    protected $signature = 'make:crud {name}';
    protected $description = 'Generate full CRUD using repository pattern';

    public function handle(): void
    {
        $name = Str::studly($this->argument('name'));
        $lowerName = Str::camel($name);
        $table = Str::snake(Str::pluralStudly($name));
        $timestamp = now()->format('Y_m_d_His');

        $paths = [
            'model' => app_path("Models/{$name}.php"),
            'request' => app_path("Http/Requests/{$name}Request.php"),
            'interface' => app_path("Interfaces/{$name}Interface.php"),
            'repository' => app_path("Repositories/{$name}Repository.php"),
            'controller' => app_path("Http/Controllers/Api/{$name}Controller.php"),
            'migration' => database_path("migrations/{$timestamp}_create_{$table}_table.php"),
        ];

        foreach ($paths as $stub => $path) {
            File::ensureDirectoryExists(dirname($path));
            $this->generateFromStub($stub, $path, [
                '{{ model }}' => $name,
                '{{ variable }}' => $lowerName,
                '{{ table }}' => $table,
            ]);
        }

        $this->info("✅ CRUD for {$name} generated successfully.");
        $this->warn("ℹ️ Please bind {$name}Interface to {$name}Repository in AppServiceProvider.");
    }

    protected function generateFromStub(string $stubName, string $targetPath, array $replacements): void
    {
        $stubPath = resource_path("stubs/vendor/crud-generator/{$stubName}.stub");

        if (!File::exists($stubPath)) {
            $this->error("❌ Stub file not found: {$stubPath}");
            return;
        }

        $content = File::get($stubPath);

        foreach ($replacements as $key => $value) {
            $content = str_replace($key, $value, $content);
        }

        File::put($targetPath, $content);
    }
}
