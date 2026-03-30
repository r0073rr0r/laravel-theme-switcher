@php
    use Illuminate\Support\Facades\Auth;
    use r0073rr0r\ThemeSwitcher\Support\ThemeSwitcherConfig;

    $serverThemePreference = ThemeSwitcherConfig::normalizePreference(
        Auth::user()?->theme_preference
    ) ?? ThemeSwitcherConfig::normalizePreference(
        request()->cookie(ThemeSwitcherConfig::cookiePreferenceName())
    ) ?? ThemeSwitcherConfig::defaultPreference();
@endphp

<meta id="theme-color-meta" name="theme-color" content="#ffffff">
<script>
    @include('theme-switcher::partials.app-layout-script', ['serverThemePreference' => $serverThemePreference])
</script>
