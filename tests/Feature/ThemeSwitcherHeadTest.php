<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use r0073rr0r\ThemeSwitcher\Tests\Fixtures\FakeUser;

it('renders the head component alias with the mason theme bootstrap script', function () {
    Auth::shouldReceive('user')->andReturn(new FakeUser('dark'));

    $html = Blade::render('<x-theme-switcher-head />');

    expect($html)
        ->toContain('theme-color-meta')
        ->toContain('window.masonTheme = {')
        ->toContain("const serverThemePreference = 'dark';");
});

it('renders the head include using the cookie preference fallback', function () {
    Auth::shouldReceive('user')->andReturn(null);

    $request = request()->duplicate(cookies: ['theme_preference' => 'dark']);
    app()->instance('request', $request);

    $html = Blade::render("@include('theme-switcher::components.head')");

    expect($html)
        ->toContain('window.masonTheme = {')
        ->toContain("const serverThemePreference = 'dark';");
});
