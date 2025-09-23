<?php

namespace MuhammadSami\CrudGenerator\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateCrud extends Command
{
    protected $signature = 'make:crud {name}';
    protected $description = 'Generate full CRUD using selected pattern (MVC, Service, Repository)';

    public function handle(): void
    {
        $name = Str::studly($this->argument('name'));
        $lowerName = Str::camel($name);
        $table = Str::snake(Str::pluralStudly($name));
        $timestamp = now()->format('Y_m_d_His');

        // Ask user for pattern
        $pattern = $this->choice(
            'Which pattern do you want to use?',
            ['mvc', 'service', 'repository'],
            0
        );
        $this->info("You selected the '{$pattern}' pattern.");

        // Prepare files to generate based on pattern
        $paths = [
            'model' => app_path("Models/{$name}.php"),
            'request' => app_path("Http/Requests/{$name}Request.php"),
            'migration' => database_path("migrations/{$timestamp}_create_{$table}_table.php"),
        ];

        if ($pattern === 'repository') {
            $paths['interface'] = app_path("Interfaces/{$name}Interface.php");
            $paths['repository'] = app_path("Repositories/{$name}Repository.php");
            $paths['controller'] = app_path("Http/Controllers/Api/{$name}Controller.php");
        } elseif ($pattern === 'service') {
            $paths['service'] = app_path("Services/{$name}Service.php");
            $paths['controller'] = app_path("Http/Controllers/Api/{$name}Controller.php");
        } elseif ($pattern === 'mvc') {
            $paths['controller'] = app_path("Http/Controllers/Api/{$name}Controller.php");
        }

        // Generate files from stubs
        foreach ($paths as $stub => $path) {
            File::ensureDirectoryExists(dirname($path));
            $this->generateFromStub($stub, $path, [
                '{{ model }}' => $name,
                '{{ variable }}' => $lowerName,
                '{{ table }}' => $table,
                '{{ pattern }}' => $pattern,
            ], $pattern);
        }

        $this->info("✅ CRUD for {$name} generated successfully using {$pattern} pattern.");
        if ($pattern === 'repository') {
            $this->warn("ℹ️ Please bind {$name}Interface to {$name}Repository in AppServiceProvider.");
        }
    }

    protected function generateFromStub(string $stubName, string $targetPath, array $replacements, string $pattern = 'repository'): void
    {
        // Check pattern-specific stub first, then fallback to common stubs
        $stubPath = resource_path("stubs/vendor/crud-generator/{$pattern}/{$stubName}.stub");
        
        if (!File::exists($stubPath)) {
            $stubPath = resource_path("stubs/vendor/crud-generator/{$stubName}.stub");
        }

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
