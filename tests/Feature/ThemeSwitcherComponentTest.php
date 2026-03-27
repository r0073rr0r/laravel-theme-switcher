<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Livewire\Livewire;
use r0073rr0r\ThemeSwitcher\Livewire\Profile\UpdateAppearanceForm;
use r0073rr0r\ThemeSwitcher\Livewire\ThemeSwitcher;
use r0073rr0r\ThemeSwitcher\Tests\Fixtures\FakeUser;

it('uses the configured default preference on mount', function () {
    config()->set('theme-switcher.default_preference', 'dark');
    Auth::shouldReceive('user')->andReturn(null);

    Livewire::test(ThemeSwitcher::class)
        ->assertSet('preference', 'dark')
        ->assertSet('theme', 'dark');
});

it('supports light dark only mode in toggle and form ui', function () {
    config()->set('theme-switcher.allowed_preferences', ['light', 'dark']);
    config()->set('theme-switcher.ui.show_system_option', false);
    config()->set('theme-switcher.default_preference', 'light');
    Auth::shouldReceive('user')->andReturn(null);

    Livewire::test(ThemeSwitcher::class)
        ->assertSet('preference', 'light')
        ->call('toggle')
        ->assertSet('preference', 'dark')
        ->call('toggle')
        ->assertSet('preference', 'light');

    Livewire::test(UpdateAppearanceForm::class)
        ->assertSee('Light mode')
        ->assertSee('Dark mode')
        ->assertDontSee('Follow system');
});

it('does not queue cookies when cookie persistence is disabled', function () {
    config()->set('theme-switcher.persistence.cookie_enabled', false);
    Auth::shouldReceive('user')->andReturn(null);
    Cookie::spy();

    Livewire::test(UpdateAppearanceForm::class)
        ->set('themePreference', 'dark')
        ->call('save');

    Cookie::shouldNotHaveReceived('queue');
});

it('does not save the authenticated user when database persistence is disabled', function () {
    config()->set('theme-switcher.persistence.database_enabled', false);
    $user = new FakeUser('light');
    Auth::shouldReceive('user')->andReturn($user);
    Cookie::spy();

    Livewire::test(UpdateAppearanceForm::class)
        ->set('themePreference', 'dark')
        ->call('save');

    expect($user->saveCalls)->toBe(0);
});
