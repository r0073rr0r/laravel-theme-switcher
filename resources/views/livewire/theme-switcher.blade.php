<div class="m-2 p-1">
<button
    type="button"
    x-data="{
        currentPreference: @js($preference),
        currentTheme: window.masonTheme?.getResolvedTheme() ?? @js($theme),
        syncFromEvent(detail = {}) {
            this.currentPreference = ['light', 'dark', 'system'].includes(detail.preference) ? detail.preference : this.currentPreference;
            this.currentTheme = ['light', 'dark', 'system'].includes(detail.theme)
                ? detail.theme
                : (window.masonTheme?.getResolvedTheme() ?? this.currentTheme);
        },
        syncFromDom() {
            this.currentTheme = this.currentPreference === 'system'
                ? 'system'
                : (window.masonTheme?.getResolvedTheme() ?? this.currentTheme);
        },
        nextLabel() {
            if (this.currentPreference === 'light') return @js(__('theme-switcher::theme-switcher.switch_to_dark_mode'));
            if (this.currentPreference === 'dark') return @js(__('theme-switcher::theme-switcher.switch_to_system_mode'));

            return @js(__('theme-switcher::theme-switcher.switch_to_light_mode'));
        }
    }"
    x-init="syncFromDom()"
    x-on:theme-changed.window="syncFromDom()"
    x-on:theme-switcher-synced.window="syncFromEvent($event.detail)"
    wire:click="toggle"
    x-bind:aria-label="nextLabel()"
    x-bind:title="nextLabel()"
    class="group relative inline-flex h-10 w-10 items-center justify-center rounded-full
           border border-gray-200 bg-white text-gray-500 shadow-sm
           transition-all duration-300 ease-out
           hover:-translate-y-0.5 hover:shadow-md hover:border-indigo-300 hover:text-indigo-600
           active:scale-95
           focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2
           dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300
           dark:hover:border-indigo-400 dark:hover:text-indigo-300 dark:hover:shadow-lg">

    <!-- glow -->
    <span class="absolute inset-0 rounded-full opacity-0 group-hover:opacity-100 transition duration-300
                 bg-gradient-to-br from-indigo-500/10 to-purple-500/10 dark:from-indigo-400/10 dark:to-purple-400/10"></span>

    <!-- SYSTEM -->
    <svg x-show="currentPreference === 'system'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-75"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-75"
         xmlns="http://www.w3.org/2000/svg"
         class="h-6 w-6 relative z-10"
         viewBox="0 0 24 24"
         fill="none"
         stroke="currentColor"
         stroke-width="1.8"
         style="display: none;">
        <rect x="3" y="4" width="18" height="12" rx="2"></rect>
        <path d="M8 20h8"></path>
        <path d="M12 16v4"></path>
    </svg>

    <!-- SUN -->
    <svg x-show="currentPreference !== 'system' && currentTheme === 'dark'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-75 rotate-[-90deg]"
         x-transition:enter-end="opacity-100 scale-100 rotate-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 rotate-0"
         x-transition:leave-end="opacity-0 scale-75 rotate-90deg"
         xmlns="http://www.w3.org/2000/svg"
         class="h-6 w-6 relative z-10"
         viewBox="0 0 24 24"
         fill="currentColor"
         style="display: none;">

        <circle cx="12" cy="12" r="5"/>

        <g stroke="currentColor" stroke-width="2" stroke-linecap="round">
            <line x1="12" y1="1.5" x2="12" y2="4"/>
            <line x1="12" y1="20" x2="12" y2="22.5"/>
            <line x1="1.5" y1="12" x2="4" y2="12"/>
            <line x1="20" y1="12" x2="22.5" y2="12"/>

            <line x1="4.2" y1="4.2" x2="6" y2="6"/>
            <line x1="18" y1="18" x2="19.8" y2="19.8"/>

            <line x1="4.2" y1="19.8" x2="6" y2="18"/>
            <line x1="18" y1="6" x2="19.8" y2="4.2"/>
        </g>
    </svg>

    <!-- MOON -->
    <svg x-show="currentPreference !== 'system' && currentTheme !== 'dark'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-75 rotate-90deg"
         x-transition:enter-end="opacity-100 scale-100 rotate-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 rotate-0"
         x-transition:leave-end="opacity-0 scale-75 rotate-[-90deg]"
         xmlns="http://www.w3.org/2000/svg"
         class="h-6 w-6 relative z-10"
         viewBox="0 0 24 24"
         fill="currentColor">

        <path d="M12.5 2a9.5 9.5 0 1 0 9.5 9.5c0-.34-.02-.68-.05-1.02
                 a7.5 7.5 0 1 1-9.43-8.43c-.01-.02-.01-.03-.02-.05Z"/>
    </svg>

</button>
</div>
