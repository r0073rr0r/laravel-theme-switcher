<?php

namespace r0073rr0r\ThemeSwitcher\Livewire\Profile;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Livewire\Component;

class UpdateAppearanceForm extends Component
{
    public string $themePreference = 'system';

    public string $theme = 'system';

    public function mount(): void
    {
        $userPreference = Auth::user()?->theme_preference;
        $cookiePreference = request()->cookie('theme_preference');
        $themeCookie = request()->cookie('theme');

        $this->themePreference = in_array($userPreference, ['light', 'dark', 'system'], true)
            ? $userPreference
            : (in_array($cookiePreference, ['light', 'dark', 'system'], true) ? $cookiePreference : 'system');

        $this->theme = in_array($themeCookie, ['light', 'dark', 'system'], true)
            ? $themeCookie
            : $this->themePreference;
    }

    public function save(): void
    {
        $this->validate([
            'themePreference' => ['required', 'in:light,dark,system'],
        ]);

        if ($user = Auth::user()) {
            $user->forceFill([
                'theme_preference' => $this->themePreference,
            ])->save();
        }

        $this->theme = $this->themePreference;

        Cookie::queue('theme_preference', $this->themePreference, 60 * 24 * 365);
        Cookie::queue('theme', $this->theme, 60 * 24 * 365);

        $this->dispatch(
            'theme-changed',
            preference: $this->themePreference,
            theme: $this->theme
        );

        $this->dispatch('saved');
    }

    public function render(): View
    {
        return view('theme-switcher::profile.update-appearance-form');
    }
}
