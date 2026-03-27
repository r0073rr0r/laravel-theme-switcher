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
