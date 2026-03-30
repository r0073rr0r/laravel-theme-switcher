<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cookie;
use Livewire\Livewire;
use r0073rr0r\ThemeSwitcher\Livewire\Profile\UpdateAppearanceForm;
use r0073rr0r\ThemeSwitcher\Tests\Fixtures\FakeUser;

it('mounts from cookie values when no authenticated user exists', function () {
    Auth::shouldReceive('user')->andReturn(null);

    $request = request()->duplicate(cookies: [
        'theme_preference' => 'dark',
        'theme' => 'light',
    ]);
    app()->instance('request', $request);

    $component = new UpdateAppearanceForm();
    $component->mount();

    expect($component->themePreference)->toBe('dark');
    expect($component->theme)->toBe('light');
});

it('saves the appearance preference, persists it, and dispatches events', function () {
    $user = new FakeUser('light');
    Auth::shouldReceive('user')->andReturn($user);
    Cookie::spy();

    Livewire::test(UpdateAppearanceForm::class)
        ->set('themePreference', 'dark')
        ->call('save')
        ->assertSet('theme', 'dark')
        ->assertDispatched('theme-changed', preference: 'dark', theme: 'dark')
        ->assertDispatched('saved');

    expect($user->theme_preference)->toBe('dark');
    expect($user->saveCalls)->toBe(1);
    Cookie::shouldHaveReceived('queue')->twice();
});

it('validates the submitted appearance preference against allowed values', function () {
    Auth::shouldReceive('user')->andReturn(null);

    Livewire::test(UpdateAppearanceForm::class)
        ->set('themePreference', 'invalid')
        ->call('save')
        ->assertHasErrors(['themePreference']);
});

it('renders the appearance form without hover transitions when animations are disabled', function () {
    config()->set('theme-switcher.animations.enabled', false);
    Auth::shouldReceive('user')->andReturn(null);

    Livewire::test(UpdateAppearanceForm::class)
        ->assertDontSeeHtml('hover:border-gray-300')
        ->assertSee('Appearance');
});

it('does not dispatch the theme changed event when disabled', function () {
    config()->set('theme-switcher.events.dispatch_theme_changed', false);
    Auth::shouldReceive('user')->andReturn(null);
    Cookie::spy();

    Livewire::test(UpdateAppearanceForm::class)
        ->set('themePreference', 'dark')
        ->call('save')
        ->assertDispatched('saved')
        ->assertNotDispatched('theme-changed');
});

it('registers the livewire aliases and blade head component', function () {
    expect(app('livewire')->exists('theme-switcher'))->toBeTrue();
    expect(app('livewire')->exists('profile.update-appearance-form'))->toBeTrue();

    $html = Blade::render('<x-theme-switcher-head />');

    expect($html)->toContain('window.masonTheme = {');
});
