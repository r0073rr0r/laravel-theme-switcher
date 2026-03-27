<?php

namespace r0073rr0r\ThemeSwitcher\Livewire;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Livewire\Attributes\On;
use Livewire\Component;
use r0073rr0r\ThemeSwitcher\Support\ThemeSwitcherConfig;

class ThemeSwitcher extends Component
{
    public string $theme = 'light';

    public string $preference = 'system';

    public function mount(): void
    {
        $defaultPreference = ThemeSwitcherConfig::defaultPreference();
        $userPreference = Auth::user()?->theme_preference;
        $cookiePreference = request()->cookie(ThemeSwitcherConfig::cookiePreferenceName());
        $resolvedTheme = request()->cookie(ThemeSwitcherConfig::cookieThemeName(), $defaultPreference);

        $this->preference = ThemeSwitcherConfig::normalizePreference($userPreference)
            ?? ThemeSwitcherConfig::normalizePreference($cookiePreference)
            ?? $defaultPreference;

        $this->theme = ThemeSwitcherConfig::normalizePreference($resolvedTheme)
            ?? $this->preference;
    }

    public function toggle(): void
    {
        $cycleOrder = ThemeSwitcherConfig::cycleOrder();
        $currentIndex = array_search($this->preference, $cycleOrder, true);
        $nextIndex = $currentIndex === false ? 0 : (($currentIndex + 1) % count($cycleOrder));
        $nextPreference = $cycleOrder[$nextIndex];

        $this->preference = $nextPreference;
        $this->theme = $nextPreference;

        if (ThemeSwitcherConfig::databaseEnabled() && ($user = Auth::user())) {
            $user->forceFill([
                'theme_preference' => $nextPreference,
            ])->save();
        }

        if (ThemeSwitcherConfig::cookieEnabled()) {
            Cookie::queue(ThemeSwitcherConfig::cookiePreferenceName(), $this->preference, ThemeSwitcherConfig::cookieMinutes());
            Cookie::queue(ThemeSwitcherConfig::cookieThemeName(), $this->theme, ThemeSwitcherConfig::cookieMinutes());
        }

        if (ThemeSwitcherConfig::dispatchPreferenceUpdated()) {
            $this->dispatch('theme-preference-updated', preference: $this->preference);
        }

        if (ThemeSwitcherConfig::dispatchThemeChanged()) {
            $this->dispatch('theme-changed', preference: $this->preference, theme: $this->theme);
        }
    }

    #[On('theme-switcher-synced')]
    public function syncState(string $preference, string $theme): void
    {
        $this->preference = ThemeSwitcherConfig::normalizePreference($preference)
            ?? ThemeSwitcherConfig::defaultPreference();
        $this->theme = ThemeSwitcherConfig::normalizePreference($theme)
            ?? $this->preference;
    }

    public function render(): View
    {
        return view('theme-switcher::livewire.theme-switcher', [
            'allowedPreferences' => ThemeSwitcherConfig::allowedPreferences(),
            'cycleOrder' => ThemeSwitcherConfig::cycleOrder(),
            'animationsEnabled' => ThemeSwitcherConfig::animationsEnabled(),
            'iconTransitionsEnabled' => ThemeSwitcherConfig::iconTransitionsEnabled(),
            'hoverEffectsEnabled' => ThemeSwitcherConfig::hoverEffectsEnabled(),
            'reducedMotionEnabled' => ThemeSwitcherConfig::reducedMotionEnabled(),
            'animationDuration' => ThemeSwitcherConfig::animationDuration(),
            'buttonSizeClass' => ThemeSwitcherConfig::buttonSizeClass(),
            'roundedClass' => ThemeSwitcherConfig::roundedClass(),
            'showTooltip' => ThemeSwitcherConfig::showTooltip(),
        ]);
    }
}
