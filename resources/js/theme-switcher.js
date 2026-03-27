const normalizeTheme = (theme) => ['light', 'dark', 'system'].includes(theme) ? theme : null;
const normalizePreference = (preference) => ['light', 'dark', 'system'].includes(preference) ? preference : null;

window.addEventListener('theme-changed', (event) => {
    if (! window.masonTheme) {
        return;
    }

    const preference = normalizePreference(event.detail?.preference);
    const theme = normalizeTheme(event.detail?.theme);
    let resolved;

    if (preference) {
        resolved = window.masonTheme.applyPreference(preference, { persist: true });
    } else if (theme) {
        resolved = window.masonTheme.applyPreference(theme, { persist: true });
    }

    if (window.Livewire?.dispatch && resolved) {
        window.Livewire.dispatch('theme-switcher-synced', {
            preference: resolved.preference,
            theme: resolved.theme,
        });
    }
});
