<?php

namespace r0073rr0r\ThemeSwitcher\Tests;

use Illuminate\Support\Facades\Blade;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use r0073rr0r\ThemeSwitcher\ThemeSwitcherServiceProvider;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            LivewireServiceProvider::class,
            ThemeSwitcherServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('app.key', 'base64:'.base64_encode(random_bytes(32)));
    }

    protected function setUp(): void
    {
        parent::setUp();

        Blade::anonymousComponentPath(__DIR__.'/resources/views/components');
    }
}
