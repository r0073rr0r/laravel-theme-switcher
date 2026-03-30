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

it('prefers the authenticated user over cookies on mount', function () {
    $user = new FakeUser('dark');
    Auth::shouldReceive('user')->andReturn($user);

    $request = request()->duplicate(cookies: [
        'theme_preference' => 'light',
        'theme' => 'light',
    ]);
    app()->instance('request', $request);

    $component = new ThemeSwitcher();
    $component->mount();

    expect($component->preference)->toBe('dark');
    expect($component->theme)->toBe('light');
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

it('renders a fixed square toggle button so the control stays circular', function () {
    config()->set('theme-switcher.default_preference', 'light');
    Auth::shouldReceive('user')->andReturn(null);

    Livewire::test(ThemeSwitcher::class)
        ->assertSeeHtml('inline-block')
        ->assertSeeHtml('shrink-0')
        ->assertSeeHtml('p-0')
        ->assertSeeHtml('aspect-ratio: 1 / 1;');
});

it('renders configured size, rounded style, and omits the tooltip when disabled', function () {
    config()->set('theme-switcher.ui.button_size', 'lg');
    config()->set('theme-switcher.ui.rounded', 'md');
    config()->set('theme-switcher.ui.show_tooltip', false);
    Auth::shouldReceive('user')->andReturn(null);

    Livewire::test(ThemeSwitcher::class)
        ->assertSeeHtml('h-12 w-12')
        ->assertSeeHtml('rounded-md')
        ->assertDontSeeHtml('x-bind:title=');
});

it('does not render the system icon when the system option is disabled', function () {
    config()->set('theme-switcher.allowed_preferences', ['light', 'dark']);
    config()->set('theme-switcher.ui.show_system_option', false);
    Auth::shouldReceive('user')->andReturn(null);

    Livewire::test(ThemeSwitcher::class)
        ->assertDontSeeHtml('currentPreference === &#039;system&#039;')
        ->assertDontSee('Follow system');
});

it('removes motion classes when animations and reduced motion helpers are disabled', function () {
    config()->set('theme-switcher.animations.enabled', false);
    config()->set('theme-switcher.animations.respect_reduced_motion', false);
    Auth::shouldReceive('user')->andReturn(null);

    Livewire::test(ThemeSwitcher::class)
        ->assertDontSeeHtml('transition-all ease-out')
        ->assertDontSeeHtml('active:scale-95')
        ->assertDontSeeHtml('motion-reduce:transition-none');
});

it('toggles, queues cookies, saves the user, and dispatches browser events', function () {
    config()->set('theme-switcher.cycle_order', ['light', 'dark', 'system']);
    $user = new FakeUser('light');
    Auth::shouldReceive('user')->andReturn($user);
    Cookie::spy();

    Livewire::test(ThemeSwitcher::class)
        ->call('toggle')
        ->assertSet('preference', 'dark')
        ->assertSet('theme', 'dark')
        ->assertDispatched('theme-preference-updated', preference: 'dark')
        ->assertDispatched('theme-changed', preference: 'dark', theme: 'dark');

    expect($user->theme_preference)->toBe('dark');
    expect($user->saveCalls)->toBe(1);
    Cookie::shouldHaveReceived('queue')->twice();
});

it('does not dispatch browser events when they are disabled', function () {
    config()->set('theme-switcher.events.dispatch_theme_changed', false);
    config()->set('theme-switcher.events.dispatch_preference_updated', false);
    Auth::shouldReceive('user')->andReturn(null);
    Cookie::spy();

    Livewire::test(ThemeSwitcher::class)
        ->call('toggle')
        ->assertNotDispatched('theme-preference-updated')
        ->assertNotDispatched('theme-changed');
});

it('starts the cycle from the first allowed value when the current preference is invalid', function () {
    Auth::shouldReceive('user')->andReturn(null);

    Livewire::test(ThemeSwitcher::class)
        ->set('preference', 'bogus')
        ->call('toggle')
        ->assertSet('preference', 'light')
        ->assertSet('theme', 'light');
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

it('syncs state from the browser event and falls back to defaults for invalid values', function () {
    config()->set('theme-switcher.default_preference', 'dark');
    Auth::shouldReceive('user')->andReturn(null);

    Livewire::test(ThemeSwitcher::class)
        ->call('syncState', 'light', 'dark')
        ->assertSet('preference', 'light')
        ->assertSet('theme', 'dark')
        ->call('syncState', 'invalid', 'invalid')
        ->assertSet('preference', 'dark')
        ->assertSet('theme', 'dark');
});
