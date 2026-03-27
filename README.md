# Laravel Theme Switcher

[![Packagist Version](https://img.shields.io/packagist/v/r0073rr0r/laravel-theme-switcher)](https://packagist.org/packages/r0073rr0r/laravel-theme-switcher)
[![Total Downloads](https://img.shields.io/packagist/dt/r0073rr0r/laravel-theme-switcher)](https://packagist.org/packages/r0073rr0r/laravel-theme-switcher)
[![Monthly Downloads](https://img.shields.io/packagist/dm/r0073rr0r/laravel-theme-switcher)](https://packagist.org/packages/r0073rr0r/laravel-theme-switcher)
[![PHP Version](https://img.shields.io/packagist/php-v/r0073rr0r/laravel-theme-switcher)](https://packagist.org/packages/r0073rr0r/laravel-theme-switcher)
[![License](https://img.shields.io/packagist/l/r0073rr0r/laravel-theme-switcher)](https://packagist.org/packages/r0073rr0r/laravel-theme-switcher)
[![GitHub Stars](https://img.shields.io/github/stars/r0073rr0r/laravel-theme-switcher?style=social)](https://github.com/r0073rr0r/laravel-theme-switcher/stargazers)
[![GitHub Issues](https://img.shields.io/github/issues/r0073rr0r/laravel-theme-switcher)](https://github.com/r0073rr0r/laravel-theme-switcher/issues)
[![GitHub Forks](https://img.shields.io/github/forks/r0073rr0r/laravel-theme-switcher?style=social)](https://github.com/r0073rr0r/laravel-theme-switcher/network)
[![Tests](https://github.com/r0073rr0r/laravel-theme-switcher/actions/workflows/tests.yml/badge.svg)](https://github.com/r0073rr0r/laravel-theme-switcher/actions/workflows/tests.yml)
[![CodeQL](https://github.com/r0073rr0r/laravel-theme-switcher/workflows/CodeQL/badge.svg)](https://github.com/r0073rr0r/laravel-theme-switcher/actions/workflows/codeql.yml)
[![PHP Composer](https://github.com/r0073rr0r/laravel-theme-switcher/workflows/PHP%20Composer/badge.svg)](https://github.com/r0073rr0r/laravel-theme-switcher/actions/workflows/codeql.yml)

A Laravel package that integrates with Jetstream and Livewire to provide a reusable light/dark/system theme switcher component, package translations, and publishable CSS/JS resources.

## Table of Contents

- [Requirements](#requirements)
- [Installation](#installation)
- [Updating](#updating)
- [Setup](#setup)
- [Usage](#usage)
- [Theme Toggle](#theme-toggle)
- [Appearance Form](#appearance-form)
- [Translations](#translations)
- [Customization](#customization)
- [Notes](#notes)
- [License](#license)
- [Contributing](#contributing)

## Requirements

- PHP 8.2+
- Laravel 12.x
- Livewire 3.x or 4.x
- Jetstream 5.x

## Installation

Install the package via Composer:

```bash
composer require r0073rr0r/laravel-theme-switcher
```

Publish the package config:

```bash
php artisan vendor:publish --tag=theme-switcher
```

Publish the package views if you want to override them:

```bash
php artisan vendor:publish --tag=theme-switcher-views
```

Publish the package translations if you want to customize the text:

```bash
php artisan vendor:publish --tag=theme-switcher-translations
```

Publish the package CSS:

```bash
php artisan vendor:publish --tag=theme-switcher-css
```

Publish the package JavaScript:

```bash
php artisan vendor:publish --tag=theme-switcher-assets
```

Run the migration so the package can persist the user's `theme_preference` value on the `users` table:

```bash
php artisan migrate
```

## Updating

When updating the package, republish the resources you actually use so your application gets the latest views, translations, and frontend files:

```bash
composer update r0073rr0r/laravel-theme-switcher
php artisan vendor:publish --tag=theme-switcher-views --force
php artisan vendor:publish --tag=theme-switcher-translations --force
php artisan vendor:publish --tag=theme-switcher-css --force
php artisan vendor:publish --tag=theme-switcher-assets --force
```

If you have customized any published files, review the changes before overwriting them.

## Setup

After publishing CSS, import it into your application's main stylesheet, for example in `resources/css/app.css`:

```css
@import './vendor/theme-switcher.css';
```

After publishing JavaScript, import it into your application's main JS entry file, for example in `resources/js/app.js`:

```js
import './vendor/theme-switcher';
```

The package JavaScript expects a global theme manager at `window.masonTheme` with methods compatible with:

```js
window.masonTheme.getResolvedTheme()
window.masonTheme.applyPreference(preference, { persist: true })
```

This is used to keep the Livewire component and browser theme state synchronized.

## Usage

### Theme Toggle

Render the Livewire component where you want the theme toggle button:

```blade
<livewire:theme-switcher />
```

Or:

```blade
@livewire('theme-switcher')
```

The component:

- Reads the current theme preference from the authenticated user when available
- Falls back to cookies when no authenticated preference exists
- Cycles through `light`, `dark`, and `system`
- Persists the selected preference in cookies
- Updates the authenticated user's `theme_preference` column when available

### Appearance Form

The package also registers a Livewire component alias for Jetstream profile settings:

```blade
@livewire('profile.update-appearance-form')
```

If you want to show it on the default Jetstream profile page, open:

```text
resources/views/profile/show.blade.php
```

Then add this block near the top of the main content area, for example after the profile information form:

```blade
<div class="mt-10 sm:mt-0">
    @livewire('profile.update-appearance-form')
</div>

<x-section-border />
```

If you prefer to reference the class directly, you can also use:

```blade
@livewire(\r0073rr0r\ThemeSwitcher\Livewire\Profile\UpdateAppearanceForm::class)
```

The form view rendered by that component comes from the package:

```text
resources/views/profile/update-appearance-form.blade.php
```

If you publish the package views, you can override that file and adapt it to your own account settings flow.

## Translations

The package loads its own translations automatically through the `theme-switcher` namespace, so publishing translations is optional.

Example keys:

```php
__('theme-switcher::theme-switcher.appearance')
__('theme-switcher::theme-switcher.save')
```

How translation fallback works:

- If the application does not publish translations, Laravel uses the package translation files directly
- If translations are published, Laravel will prefer `lang/vendor/theme-switcher`
- If the current locale does not exist, Laravel will try the application's `fallback_locale`

This package already includes:

- `resources/lang/en/theme-switcher.php`
- `resources/lang/sr/theme-switcher.php`

That means a typical application with `fallback_locale=en` will still show English text even when the active locale has no dedicated theme-switcher translation yet.

## Customization

You can customize the package resources after publishing them:

- Views: `resources/views/vendor/theme-switcher`
- Translations: `lang/vendor/theme-switcher`
- CSS: `resources/css/vendor/theme-switcher.css`
- JavaScript: `resources/js/vendor/theme-switcher.js`

## Notes

- The package CSS is intended to be included in the consumer application's build pipeline
- The package JavaScript is intended to be imported into the consumer application's JS entry file
- The package no longer publishes unrelated third-party assets
- The authenticated user model is expected to support a `theme_preference` column if you want per-user persistence
- The service provider registers both `theme-switcher` and `profile.update-appearance-form` Livewire aliases

## License

This package is open-sourced software licensed under the [MIT license](LICENSE).

## Contributing

Pull requests are welcome. For larger changes, open an issue first so the scope is clear before implementation.
