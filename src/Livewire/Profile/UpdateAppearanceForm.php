<?php

namespace r0073rr0r\ThemeSwitcher\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;
use r0073rr0r\ThemeSwitcher\Support\ThemeSwitcherConfig;

class UpdateAppearanceForm extends Component
{
    public string $themePreference = 'system';

    public string $theme = 'system';

    public function mount(): void
    {
        $defaultPreference = ThemeSwitcherConfig::defaultPreference();
        $userPreference = Auth::user()?->theme_preference;
        $cookiePreference = request()->cookie(ThemeSwitcherConfig::cookiePreferenceName());
        $themeCookie = request()->cookie(ThemeSwitcherConfig::cookieThemeName());

        $this->themePreference = ThemeSwitcherConfig::normalizePreference($userPreference)
            ?? ThemeSwitcherConfig::normalizePreference($cookiePreference)
            ?? $defaultPreference;

        $this->theme = ThemeSwitcherConfig::normalizePreference($themeCookie)
            ?? $this->themePreference;
    }

    public function save(): void
    {
        $this->validate([
            'themePreference' => ['required', 'in:'.implode(',', ThemeSwitcherConfig::allowedPreferences())],
        ]);

        if (ThemeSwitcherConfig::databaseEnabled() && ($user = Auth::user())) {
            $user->forceFill([
                'theme_preference' => $this->themePreference,
            ])->save();
        }

        $this->theme = $this->themePreference;

        if (ThemeSwitcherConfig::cookieEnabled()) {
            Cookie::queue(ThemeSwitcherConfig::cookiePreferenceName(), $this->themePreference, ThemeSwitcherConfig::cookieMinutes());
            Cookie::queue(ThemeSwitcherConfig::cookieThemeName(), $this->theme, ThemeSwitcherConfig::cookieMinutes());
        }

        if (ThemeSwitcherConfig::dispatchThemeChanged()) {
            $this->dispatch(
                'theme-changed',
                preference: $this->themePreference,
                theme: $this->theme
            );
        }

        $this->dispatch('saved');
    }

    public function render(): View
    {
        return view('theme-switcher::profile.update-appearance-form', [
            'allowedPreferences' => ThemeSwitcherConfig::allowedPreferences(),
            'animationsEnabled' => ThemeSwitcherConfig::animationsEnabled(),
            'hoverEffectsEnabled' => ThemeSwitcherConfig::hoverEffectsEnabled(),
        ]);
    }
}
