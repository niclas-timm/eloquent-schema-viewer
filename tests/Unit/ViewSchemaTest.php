<?php

namespace NiclasTimm\EloquentSchemaViewer\Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use NiclasTimm\EloquentSchemaViewer\Tests\TestCase;

class ViewSchemaTest extends TestCase
{
    /** @test */
    public function it_requires_model_or_table_name()
    {
        Artisan::call('eloquent-schema-viewer:view')->assertFailed();
    }
}