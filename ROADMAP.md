# Theme Switcher Config Plan

## Goal

Add a real configuration layer to the package so consumers can control default behavior, animations, persistence, and UI behavior without editing package source files.

## Scope

1. Define a public config API for the theme switcher.
2. Replace hardcoded backend values with config-driven behavior.
3. Replace hardcoded frontend behavior with config-driven behavior.
4. Add validation and safe fallbacks for invalid config.
5. Add automated tests for the new config surface.
6. Document configuration and upgrade behavior in the README.

## Proposed Config Options

```php
return [
    'default_preference' => 'system',
    'allowed_preferences' => ['light', 'dark', 'system'],
    'cycle_order' => ['light', 'dark', 'system'],
    'animations' => [
        'enabled' => true,
        'duration' => 300,
        'icon_transition' => true,
        'hover_effects' => true,
        'respect_reduced_motion' => true,
    ],
    'persistence' => [
        'cookie_enabled' => true,
        'cookie_name_preference' => 'theme_preference',
        'cookie_name_theme' => 'theme',
        'cookie_minutes' => 60 * 24 * 365,
        'database_enabled' => true,
    ],
    'ui' => [
        'show_system_option' => true,
        'button_size' => 'md',
        'rounded' => 'full',
        'show_tooltip' => true,
    ],
    'events' => [
        'dispatch_theme_changed' => true,
        'dispatch_preference_updated' => true,
    ],
];
```

## Execution Plan

### 1. Define The Config API

- Fill `config/theme-switcher.php` with stable public options and defaults.
- Keep the API small and explicit so later changes stay backward-compatible.
- Ensure `default_preference` is always constrained by `allowed_preferences`.

### 2. Wire Config Into Backend Logic

- Introduce a shared config/support layer for:
  - allowed preferences
  - default fallback preference
  - cycle order normalization
  - persistence flags and cookie names
- Update `ThemeSwitcher` to use config instead of hardcoded values.
- Update `UpdateAppearanceForm` to use the same rules.

### 3. Wire Config Into Blade/JS Behavior

- Make toggle labels and displayed options depend on `allowed_preferences`.
- Disable or enable animation classes and Alpine transitions from config.
- Respect `cycle_order` in the browser-facing state sync.
- Keep the existing `window.masonTheme` integration intact.

### 4. Validation And Fallbacks

- Ignore invalid values in `allowed_preferences`.
- Ignore invalid or duplicate values in `cycle_order`.
- Fall back to a safe default when config is incomplete.
- Prevent invalid preferences from being saved to cookies or database.

### 5. Tests

- Add tests for config defaults.
- Add tests for custom default preference.
- Add tests for `light/dark` only mode.
- Add tests for disabled cookie persistence.
- Add tests for disabled database persistence.
- Add tests for invalid config fallback behavior.

### 6. Documentation

- Document each config key in `README.md`.
- Show a publish-and-customize flow.
- Explain behavior when assets are already published.
- Note accessibility behavior for reduced motion.

## Delivery Notes

- Favor backward compatibility for the default install.
- Keep config-driven behavior identical to current behavior unless a user opts in to a new setting.
- Avoid introducing package-level breaking changes in the public component aliases.
