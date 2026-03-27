<?php

namespace r0073rr0r\ThemeSwitcher\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\On;
use Livewire\Component;

class ThemeSwitcher extends Component
{
    public string $theme = 'light';

    public string $preference = 'system';

    public function mount(): void
    {
        $userPreference = Auth::user()?->theme_preference;
        $cookiePreference = request()->cookie('theme_preference');
        $resolvedTheme = request()->cookie('theme', 'light');

        $this->preference = in_array($userPreference, ['light', 'dark', 'system'], true)
            ? $userPreference
            : (in_array($cookiePreference, ['light', 'dark', 'system'], true) ? $cookiePreference : 'system');

        $this->theme = in_array($resolvedTheme, ['light', 'dark', 'system'], true)
            ? $resolvedTheme
            : 'light';
    }

    public function toggle(): void
    {
        $nextPreference = match ($this->preference) {
            'light' => 'dark',
            'dark' => 'system',
            default => 'light',
        };

        $this->preference = $nextPreference;

        $this->theme = $nextPreference;

        if ($user = Auth::user()) {
            $user->forceFill([
                'theme_preference' => $nextPreference,
            ])->save();
        }

        Cookie::queue('theme_preference', $this->preference, 60 * 24 * 365);
        Cookie::queue('theme', $this->theme, 60 * 24 * 365);

        $this->dispatch('theme-preference-updated', preference: $this->preference);
        $this->dispatch('theme-changed', preference: $this->preference, theme: $this->theme);
    }

    #[On('theme-switcher-synced')]
    public function syncState(string $preference, string $theme): void
    {
        $this->preference = in_array($preference, ['light', 'dark', 'system'], true) ? $preference : 'system';
        $this->theme = in_array($theme, ['light', 'dark', 'system'], true) ? $theme : 'light';
    }

    public function render(): View
    {
        return view('theme-switcher::livewire.theme-switcher');
    }
}
