const validPreferences = ['light', 'dark', 'system'];

const getSwitcherConfig = () => {
    const root = document.querySelector('[data-theme-switcher-config]');

    if (! root) {
        return {
            allowedPreferences: validPreferences,
        };
    }

    try {
        const allowedPreferences = JSON.parse(root.dataset.allowedPreferences ?? '[]')
            .filter((value) => validPreferences.includes(value));

        return {
            allowedPreferences: allowedPreferences.length > 0 ? allowedPreferences : validPreferences,
        };
    } catch {
        return {
            allowedPreferences: validPreferences,
        };
    }
};

const normalizeTheme = (theme) => getSwitcherConfig().allowedPreferences.includes(theme) ? theme : null;
const normalizePreference = (preference) => getSwitcherConfig().allowedPreferences.includes(preference) ? preference : null;

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
