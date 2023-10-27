<?php

namespace NiclasTimm\EloquentSchemaViewer;

use Illuminate\Support\ServiceProvider;
use NiclasTimm\EloquentSchemaViewer\Console\ViewSchema;

class EloquentSchemaViewerServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                ViewSchema::class
            ]);
        }
    }
}