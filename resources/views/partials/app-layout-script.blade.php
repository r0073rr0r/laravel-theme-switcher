(() => {
    const THEME_KEY = 'theme';
    const THEME_PREFERENCE_KEY = 'theme_preference';
    const THEME_MAX_AGE = 60 * 60 * 24 * 365;
    const serverThemePreference = @js($serverThemePreference);
    const systemThemeQuery = window.matchMedia('(prefers-color-scheme: dark)');

    const getCookie = (name) => {
        const match = document.cookie.match(new RegExp(`(?:^|; )${name}=([^;]+)`));

        return match ? decodeURIComponent(match[1]) : null;
    };

    const normalizeTheme = (theme) => theme === 'dark' ? 'dark' : 'light';
    const normalizePreference = (preference) => ['light', 'dark', 'system'].includes(preference) ? preference : null;
    const resolveTheme = (preference) => {
        const normalizedPreference = normalizePreference(preference) ?? 'system';

        if (normalizedPreference === 'system') {
            return systemThemeQuery.matches ? 'dark' : 'light';
        }

        return normalizedPreference;
    };
    const persistState = (preference, theme) => {
        window.localStorage.setItem(THEME_PREFERENCE_KEY, preference);
        window.localStorage.setItem(THEME_KEY, theme);
        document.cookie = `${THEME_PREFERENCE_KEY}=${encodeURIComponent(preference)}; path=/; max-age=${THEME_MAX_AGE}; SameSite=Lax`;
        document.cookie = `${THEME_KEY}=${encodeURIComponent(theme)}; path=/; max-age=${THEME_MAX_AGE}; SameSite=Lax`;
    };
    const applyState = (preference, options = {}) => {
        const { persist = true } = options;
        const normalizedPreference = normalizePreference(preference) ?? 'system';
        const theme = resolveTheme(normalizedPreference);
        const isDarkTheme = theme === 'dark';

        document.documentElement.classList.toggle('dark', isDarkTheme);
        document.documentElement.dataset.theme = theme;
        document.documentElement.dataset.themePreference = normalizedPreference;

        const themeMeta = document.getElementById('theme-color-meta');
        if (themeMeta) {
            themeMeta.setAttribute('content', isDarkTheme ? '#020617' : '#ffffff');
        }

        if (persist) {
            persistState(normalizedPreference, theme);
        }

        return { preference: normalizedPreference, theme };
    };

    const initialPreference = normalizePreference(serverThemePreference)
        ?? normalizePreference(window.localStorage.getItem(THEME_PREFERENCE_KEY))
        ?? normalizePreference(getCookie(THEME_PREFERENCE_KEY))
        ?? normalizePreference(window.localStorage.getItem(THEME_KEY))
        ?? normalizePreference(getCookie(THEME_KEY))
        ?? 'system';

    applyState(initialPreference);

    window.masonTheme = {
        applyPreference(nextPreference, options = {}) {
            return applyState(nextPreference, options);
        },
        apply(nextTheme) {
            return applyState(normalizeTheme(nextTheme));
        },
        toggleExplicit() {
            const nextTheme = this.getResolvedTheme() === 'dark' ? 'light' : 'dark';

            return applyState(nextTheme);
        },
        getFlatpickrTheme() {
            return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        },
        getPreference() {
            return document.documentElement.dataset.themePreference ?? 'system';
        },
        getResolvedTheme() {
            return document.documentElement.dataset.theme === 'dark' ? 'dark' : 'light';
        },
    };

    systemThemeQuery.addEventListener('change', () => {
        if (window.masonTheme.getPreference() === 'system') {
            applyState('system');
            window.dispatchEvent(new CustomEvent('theme-changed', {
                detail: {
                    preference: 'system',
                    theme: window.masonTheme.getResolvedTheme(),
                },
            }));
        }
    });
})();
