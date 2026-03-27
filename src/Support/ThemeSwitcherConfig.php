<?php

namespace r0073rr0r\ThemeSwitcher\Support;

class ThemeSwitcherConfig
{
    /**
     * @return array<int, string>
     */
    public static function allowedPreferences(): array
    {
        $allowed = config('theme-switcher.allowed_preferences', ['light', 'dark', 'system']);
        $allowed = is_array($allowed) ? $allowed : ['light', 'dark', 'system'];

        if (! static::showSystemOption()) {
            $allowed = array_filter($allowed, static fn (mixed $value): bool => $value !== 'system');
        }

        $normalized = [];

        foreach ($allowed as $value) {
            if (! is_string($value)) {
                continue;
            }

            $value = strtolower($value);

            if (in_array($value, ['light', 'dark', 'system'], true) && ! in_array($value, $normalized, true)) {
                $normalized[] = $value;
            }
        }

        return $normalized !== [] ? $normalized : ['light', 'dark'];
    }

    public static function defaultPreference(): string
    {
        $default = config('theme-switcher.default_preference', 'system');
        $preference = static::normalizePreference(is_string($default) ? $default : null);
        $allowed = static::allowedPreferences();

        return $preference !== null && in_array($preference, $allowed, true)
            ? $preference
            : $allowed[0];
    }

    /**
     * @return array<int, string>
     */
    public static function cycleOrder(): array
    {
        $order = config('theme-switcher.cycle_order', ['light', 'dark', 'system']);
        $order = is_array($order) ? $order : ['light', 'dark', 'system'];

        $normalized = [];
        $allowed = static::allowedPreferences();

        foreach ($order as $value) {
            $preference = static::normalizePreference(is_string($value) ? $value : null);

            if ($preference !== null && in_array($preference, $allowed, true) && ! in_array($preference, $normalized, true)) {
                $normalized[] = $preference;
            }
        }

        foreach ($allowed as $preference) {
            if (! in_array($preference, $normalized, true)) {
                $normalized[] = $preference;
            }
        }

        return $normalized;
    }

    public static function normalizePreference(?string $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $value = strtolower($value);

        return in_array($value, static::allowedPreferencesRaw(), true) ? $value : null;
    }

    public static function cookieEnabled(): bool
    {
        return (bool) config('theme-switcher.persistence.cookie_enabled', true);
    }

    public static function databaseEnabled(): bool
    {
        return (bool) config('theme-switcher.persistence.database_enabled', true);
    }

    public static function cookiePreferenceName(): string
    {
        $name = config('theme-switcher.persistence.cookie_name_preference', 'theme_preference');

        return is_string($name) && $name !== '' ? $name : 'theme_preference';
    }

    public static function cookieThemeName(): string
    {
        $name = config('theme-switcher.persistence.cookie_name_theme', 'theme');

        return is_string($name) && $name !== '' ? $name : 'theme';
    }

    public static function cookieMinutes(): int
    {
        $minutes = config('theme-switcher.persistence.cookie_minutes', 60 * 24 * 365);

        return is_numeric($minutes) ? max(1, (int) $minutes) : 60 * 24 * 365;
    }

    public static function dispatchThemeChanged(): bool
    {
        return (bool) config('theme-switcher.events.dispatch_theme_changed', true);
    }

    public static function dispatchPreferenceUpdated(): bool
    {
        return (bool) config('theme-switcher.events.dispatch_preference_updated', true);
    }

    public static function animationsEnabled(): bool
    {
        return (bool) config('theme-switcher.animations.enabled', true);
    }

    public static function iconTransitionsEnabled(): bool
    {
        return static::animationsEnabled() && (bool) config('theme-switcher.animations.icon_transition', true);
    }

    public static function hoverEffectsEnabled(): bool
    {
        return static::animationsEnabled() && (bool) config('theme-switcher.animations.hover_effects', true);
    }

    public static function reducedMotionEnabled(): bool
    {
        return (bool) config('theme-switcher.animations.respect_reduced_motion', true);
    }

    public static function animationDuration(): int
    {
        $duration = config('theme-switcher.animations.duration', 300);

        return is_numeric($duration) ? max(0, (int) $duration) : 300;
    }

    public static function showTooltip(): bool
    {
        return (bool) config('theme-switcher.ui.show_tooltip', true);
    }

    public static function showSystemOption(): bool
    {
        return (bool) config('theme-switcher.ui.show_system_option', true);
    }

    public static function buttonSizeClass(): string
    {
        return match (config('theme-switcher.ui.button_size', 'md')) {
            'sm' => 'h-8 w-8',
            'lg' => 'h-12 w-12',
            default => 'h-10 w-10',
        };
    }

    public static function roundedClass(): string
    {
        return match (config('theme-switcher.ui.rounded', 'full')) {
            'md' => 'rounded-md',
            'lg' => 'rounded-lg',
            'xl' => 'rounded-xl',
            default => 'rounded-full',
        };
    }

    /**
     * @return array<int, string>
     */
    private static function allowedPreferencesRaw(): array
    {
        return ['light', 'dark', 'system'];
    }
}

