@php
    $nextLabels = [];

    foreach ($cycleOrder as $index => $cyclePreference) {
        $nextPreference = $cycleOrder[($index + 1) % count($cycleOrder)];
        $nextLabels[$cyclePreference] = match ($nextPreference) {
            'dark' => __('theme-switcher::theme-switcher.switch_to_dark_mode'),
            'system' => __('theme-switcher::theme-switcher.switch_to_system_mode'),
            default => __('theme-switcher::theme-switcher.switch_to_light_mode'),
        };
    }

    $buttonClasses = implode(' ', array_filter([
        'group relative inline-flex shrink-0 items-center justify-center rounded-full border border-gray-200 bg-white p-0 text-gray-500 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300',
        $buttonSizeClass,
        $animationsEnabled ? 'transition-all ease-out' : null,
        $animationsEnabled && $hoverEffectsEnabled ? 'hover:-translate-y-0.5 hover:shadow-md hover:border-indigo-300 hover:text-indigo-600 dark:hover:border-indigo-400 dark:hover:text-indigo-300 dark:hover:shadow-lg' : null,
        $animationsEnabled ? 'active:scale-95' : null,
        $reducedMotionEnabled ? 'motion-reduce:transition-none motion-reduce:transform-none' : null,
    ]));

    $glowClasses = implode(' ', array_filter([
        'absolute inset-0 rounded-full bg-gradient-to-br from-indigo-500/10 to-purple-500/10 dark:from-indigo-400/10 dark:to-purple-400/10',
        $animationsEnabled && $hoverEffectsEnabled ? 'opacity-0 transition group-hover:opacity-100' : 'hidden',
        $reducedMotionEnabled ? 'motion-reduce:transition-none' : null,
    ]));

    $iconTransition = $iconTransitionsEnabled
        ? "x-transition:enter=\"transition ease-out duration-{$animationDuration}\" x-transition:enter-start=\"opacity-0 scale-75\" x-transition:enter-end=\"opacity-100 scale-100\" x-transition:leave=\"transition ease-in duration-200\" x-transition:leave-start=\"opacity-100 scale-100\" x-transition:leave-end=\"opacity-0 scale-75\""
        : '';

    $spinForwardTransition = $iconTransitionsEnabled
        ? "x-transition:enter=\"transition ease-out duration-{$animationDuration}\" x-transition:enter-start=\"opacity-0 scale-75 rotate-[-90deg]\" x-transition:enter-end=\"opacity-100 scale-100 rotate-0\" x-transition:leave=\"transition ease-in duration-200\" x-transition:leave-start=\"opacity-100 scale-100 rotate-0\" x-transition:leave-end=\"opacity-0 scale-75 rotate-90deg\""
        : '';

    $spinBackwardTransition = $iconTransitionsEnabled
        ? "x-transition:enter=\"transition ease-out duration-{$animationDuration}\" x-transition:enter-start=\"opacity-0 scale-75 rotate-90deg\" x-transition:enter-end=\"opacity-100 scale-100 rotate-0\" x-transition:leave=\"transition ease-in duration-200\" x-transition:leave-start=\"opacity-100 scale-100 rotate-0\" x-transition:leave-end=\"opacity-0 scale-75 rotate-[-90deg]\""
        : '';
@endphp

<div
    class="m-1 inline-block p-1"
    data-theme-switcher-config
    data-allowed-preferences='@json($allowedPreferences)'
    data-cycle-order='@json($cycleOrder)'
>
    <button
        type="button"
        x-data="{
            allowedPreferences: @js($allowedPreferences),
            cycleOrder: @js($cycleOrder),
            labels: @js($nextLabels),
            currentPreference: @js($preference),
            currentTheme: window.masonTheme?.getResolvedTheme() ?? @js($theme),
            normalize(value) {
                return this.allowedPreferences.includes(value) ? value : null;
            },
            syncFromEvent(detail = {}) {
                this.currentPreference = this.normalize(detail.preference) ?? this.currentPreference;
                this.currentTheme = this.normalize(detail.theme) ?? (window.masonTheme?.getResolvedTheme() ?? this.currentTheme);
            },
            syncFromDom() {
                const resolved = window.masonTheme?.getResolvedTheme() ?? this.currentTheme;
                this.currentTheme = this.currentPreference === 'system' && this.allowedPreferences.includes('system')
                    ? 'system'
                    : (this.normalize(resolved) ?? this.currentTheme);
            },
            nextLabel() {
                return this.labels[this.currentPreference] ?? this.labels[this.cycleOrder[0]] ?? '';
            }
        }"
        x-init="syncFromDom()"
        x-on:theme-changed.window="syncFromDom()"
        x-on:theme-switcher-synced.window="syncFromEvent($event.detail)"
        wire:click="toggle"
        x-bind:aria-label="nextLabel()"
        @if($showTooltip)
            x-bind:title="nextLabel()"
        @endif
        class="{{ $buttonClasses }}"
        style="aspect-ratio: 1 / 1;"
    >
        <span class="{{ $glowClasses }}"></span>
        <span class="relative z-10 inline-flex h-8 w-8 items-center justify-center rounded-full border border-gray-200 bg-white dark:border-gray-600 dark:bg-gray-800">

        @if(in_array('system', $allowedPreferences, true))
            <svg
                x-show="currentPreference === 'system'"
                {!! $iconTransition !!}
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5"
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.8"
                style="display: none;"
            >
                <rect x="3" y="4" width="18" height="12" rx="2"></rect>
                <path d="M8 20h8"></path>
                <path d="M12 16v4"></path>
            </svg>
        @endif

        @if(in_array('dark', $allowedPreferences, true))
            <svg
                x-show="currentPreference !== 'system' && currentTheme === 'dark'"
                {!! $spinForwardTransition !!}
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 text-amber-500"
                viewBox="0 0 24 24"
                fill="currentColor"
                style="display: none;"
            >
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
        @endif

        @if(in_array('light', $allowedPreferences, true))
            <svg
                x-show="currentPreference !== 'system' && currentTheme !== 'dark'"
                {!! $spinBackwardTransition !!}
                xmlns="http://www.w3.org/2000/svg"
                class="h-5 w-5 text-indigo-500"
                viewBox="0 0 24 24"
                fill="currentColor"
                style="display: none;"
            >
                <path d="M12.5 2a9.5 9.5 0 1 0 9.5 9.5c0-.34-.02-.68-.05-1.02 a7.5 7.5 0 1 1-9.43-8.43c-.01-.02-.01-.03-.02-.05Z"/>
            </svg>
        @endif
        </span>
    </button>
</div>
