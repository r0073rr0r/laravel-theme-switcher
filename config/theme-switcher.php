<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Preference
    |--------------------------------------------------------------------------
    |
    | Used when no authenticated user preference or cookie value is available.
    | Must exist in the allowed_preferences list below.
    |
    */
    'default_preference' => 'system',

    /*
    |--------------------------------------------------------------------------
    | Allowed Preferences
    |--------------------------------------------------------------------------
    |
    | Controls which theme preferences the package accepts and exposes.
    | Supported values are: light, dark, system.
    |
    */
    'allowed_preferences' => ['light', 'dark', 'system'],

    /*
    |--------------------------------------------------------------------------
    | Toggle Cycle Order
    |--------------------------------------------------------------------------
    |
    | Defines the order used by the theme toggle button when cycling through
    | the available preferences. Invalid or disallowed values are ignored.
    |
    */
    'cycle_order' => ['light', 'dark', 'system'],

    /*
    |--------------------------------------------------------------------------
    | Animations
    |--------------------------------------------------------------------------
    |
    | Controls visual transitions used by the toggle button and the profile
    | appearance form. If disabled, the package renders without transitions.
    |
    */
    'animations' => [
        'enabled' => true,
        'duration' => 300,
        'icon_transition' => true,
        'hover_effects' => true,
        'respect_reduced_motion' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Persistence
    |--------------------------------------------------------------------------
    |
    | Choose whether the selected preference should be persisted in cookies,
    | the authenticated user record, or both.
    |
    */
    'persistence' => [
        'cookie_enabled' => true,
        'cookie_name_preference' => 'theme_preference',
        'cookie_name_theme' => 'theme',
        'cookie_minutes' => 60 * 24 * 365,
        'database_enabled' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | UI
    |--------------------------------------------------------------------------
    |
    | UI-specific options for the rendered switcher and appearance form.
    |
    */
    'ui' => [
        'show_system_option' => true,
        'button_size' => 'md',
        'rounded' => 'full',
        'show_tooltip' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    |
    | Allows consumers to disable browser events if they want to handle theme
    | synchronization on their own.
    |
    */
    'events' => [
        'dispatch_theme_changed' => true,
        'dispatch_preference_updated' => true,
    ],
];
