<?php

use r0073rr0r\ThemeSwitcher\Support\ThemeSwitcherConfig;

it('falls back to safe defaults for invalid config', function () {
    config()->set('theme-switcher.allowed_preferences', ['invalid']);
    config()->set('theme-switcher.default_preference', 'system');
    config()->set('theme-switcher.cycle_order', ['bogus', 'dark', 'dark']);
    config()->set('theme-switcher.ui.show_system_option', false);

    expect(ThemeSwitcherConfig::allowedPreferences())->toBe(['light', 'dark']);
    expect(ThemeSwitcherConfig::defaultPreference())->toBe('light');
    expect(ThemeSwitcherConfig::cycleOrder())->toBe(['dark', 'light']);
});

it('normalizes ui and persistence config helpers', function () {
    config()->set('theme-switcher.persistence.cookie_name_preference', '');
    config()->set('theme-switcher.persistence.cookie_name_theme', 123);
    config()->set('theme-switcher.persistence.cookie_minutes', 0);
    config()->set('theme-switcher.animations.duration', -50);
    config()->set('theme-switcher.ui.button_size', 'sm');
    config()->set('theme-switcher.ui.rounded', 'xl');

    expect(ThemeSwitcherConfig::cookiePreferenceName())->toBe('theme_preference');
    expect(ThemeSwitcherConfig::cookieThemeName())->toBe('theme');
    expect(ThemeSwitcherConfig::cookieMinutes())->toBe(1);
    expect(ThemeSwitcherConfig::animationDuration())->toBe(0);
    expect(ThemeSwitcherConfig::buttonSizeClass())->toBe('h-8 w-8');
    expect(ThemeSwitcherConfig::roundedClass())->toBe('rounded-xl');
});

it('respects explicit helper toggles and fallback values', function () {
    config()->set('theme-switcher.persistence.cookie_enabled', false);
    config()->set('theme-switcher.persistence.database_enabled', false);
    config()->set('theme-switcher.events.dispatch_theme_changed', false);
    config()->set('theme-switcher.events.dispatch_preference_updated', false);
    config()->set('theme-switcher.animations.enabled', false);
    config()->set('theme-switcher.animations.icon_transition', true);
    config()->set('theme-switcher.animations.hover_effects', true);
    config()->set('theme-switcher.animations.respect_reduced_motion', false);
    config()->set('theme-switcher.ui.show_tooltip', false);
    config()->set('theme-switcher.ui.show_system_option', false);
    config()->set('theme-switcher.ui.button_size', 'bogus');
    config()->set('theme-switcher.ui.rounded', 'bogus');

    expect(ThemeSwitcherConfig::cookieEnabled())->toBeFalse();
    expect(ThemeSwitcherConfig::databaseEnabled())->toBeFalse();
    expect(ThemeSwitcherConfig::dispatchThemeChanged())->toBeFalse();
    expect(ThemeSwitcherConfig::dispatchPreferenceUpdated())->toBeFalse();
    expect(ThemeSwitcherConfig::animationsEnabled())->toBeFalse();
    expect(ThemeSwitcherConfig::iconTransitionsEnabled())->toBeFalse();
    expect(ThemeSwitcherConfig::hoverEffectsEnabled())->toBeFalse();
    expect(ThemeSwitcherConfig::reducedMotionEnabled())->toBeFalse();
    expect(ThemeSwitcherConfig::showTooltip())->toBeFalse();
    expect(ThemeSwitcherConfig::showSystemOption())->toBeFalse();
    expect(ThemeSwitcherConfig::buttonSizeClass())->toBe('h-10 w-10');
    expect(ThemeSwitcherConfig::roundedClass())->toBe('rounded-full');
});

it('normalizes preferences and completes cycle order from allowed values', function () {
    config()->set('theme-switcher.allowed_preferences', ['dark', 'LIGHT', 'dark', 'system']);
    config()->set('theme-switcher.cycle_order', ['system']);
    config()->set('theme-switcher.default_preference', 'system');

    expect(ThemeSwitcherConfig::normalizePreference('DARK'))->toBe('dark');
    expect(ThemeSwitcherConfig::normalizePreference('invalid'))->toBeNull();
    expect(ThemeSwitcherConfig::allowedPreferences())->toBe(['dark', 'light', 'system']);
    expect(ThemeSwitcherConfig::cycleOrder())->toBe(['system', 'dark', 'light']);
    expect(ThemeSwitcherConfig::defaultPreference())->toBe('system');
});
