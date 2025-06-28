<?php

namespace MuhammadSami\CrudGenerator;

use Illuminate\Support\ServiceProvider;
use MuhammadSami\CrudGenerator\Commands\GenerateCrud;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/stubs' => resource_path('stubs/vendor/crud-generator'),
        ], 'crud-stubs');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCrud::class,
            ]);
        }
    }

    public function register(): void {}
}
