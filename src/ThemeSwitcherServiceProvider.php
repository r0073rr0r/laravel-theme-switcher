<?php

namespace r0073rr0r\ThemeSwitcher;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;
use r0073rr0r\ThemeSwitcher\Livewire\Profile\UpdateAppearanceForm;
use r0073rr0r\ThemeSwitcher\Livewire\ThemeSwitcher as ThemeSwitcherComponent;

class ThemeSwitcherServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'theme-switcher');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'theme-switcher');
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        Blade::component('theme-switcher::components.head', 'theme-switcher-head');

        if (class_exists(Livewire::class)) {
            Livewire::component('theme-switcher', ThemeSwitcherComponent::class);
            Livewire::component('profile.update-appearance-form', UpdateAppearanceForm::class);
        }

        $this->publishes([
            __DIR__.'/../config/theme-switcher.php' => config_path('theme-switcher.php'),
        ], 'theme-switcher');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/theme-switcher'),
        ], 'theme-switcher-views');

        $this->publishes([
            __DIR__.'/../resources/lang' => lang_path('vendor/theme-switcher'),
        ], 'theme-switcher-translations');

        $this->publishes([
            __DIR__.'/../resources/css/theme-switcher.css' => resource_path('css/vendor/theme-switcher.css'),
        ], 'theme-switcher-css');

        $this->publishes([
            __DIR__.'/../resources/js/theme-switcher.js' => resource_path('js/vendor/theme-switcher.js'),
        ], 'theme-switcher-assets');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/theme-switcher.php', 'theme-switcher');
    }
}
