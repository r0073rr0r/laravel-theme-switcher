@php
    $optionMeta = [
        'light' => [
            'label' => __('theme-switcher::theme-switcher.light_mode'),
            'description' => __('theme-switcher::theme-switcher.light_mode_description'),
            'iconClass' => 'text-amber-500',
            'icon' => '<path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Zm0 4a1 1 0 0 1-1-1v-1a1 1 0 1 1 2 0v1a1 1 0 0 1-1 1Zm0-18a1 1 0 0 1-1-1V2a1 1 0 1 1 2 0v1a1 1 0 0 1-1 1Zm10 8a1 1 0 1 1 0 2h-1a1 1 0 1 1 0-2h1ZM4 12a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2h1Zm14.364 6.95a1 1 0 0 1 1.414-1.414l.707.707a1 1 0 0 1-1.414 1.414l-.707-.707Zm-12.02-12.02a1 1 0 0 1 1.414-1.414l.707.707A1 1 0 0 1 7.05 7.637l-.707-.707Zm12.727.707a1 1 0 0 1 0-1.414l.707-.707a1 1 0 0 1 1.414 1.414l-.707.707a1 1 0 0 1-1.414 0Zm-12.02 12.02a1 1 0 0 1 0-1.414l.707-.707a1 1 0 1 1 1.414 1.414l-.707.707a1 1 0 0 1-1.414 0Z"/>',
        ],
        'dark' => [
            'label' => __('theme-switcher::theme-switcher.dark_mode'),
            'description' => __('theme-switcher::theme-switcher.dark_mode_description'),
            'iconClass' => 'text-indigo-500',
            'icon' => '<path d="M21.752 15.002A9.718 9.718 0 0 1 12 22a10 10 0 0 1-9.954-9.046A10 10 0 0 1 13 2.248a1 1 0 0 1 .493 1.882 8 8 0 0 0 8.259 13.377 1 1 0 0 1 1.245 1.495 9.93 9.93 0 0 1-1.245 1Z"/>',
        ],
        'system' => [
            'label' => __('theme-switcher::theme-switcher.follow_system'),
            'description' => __('theme-switcher::theme-switcher.follow_system_description'),
            'iconClass' => 'text-gray-500 dark:text-gray-300',
            'icon' => '<path d="M4 5a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3h-4l-4 4v-4H7a3 3 0 0 1-3-3V5Zm3-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4v1.586L12.586 14H17a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H7Z"/>',
        ],
    ];

    $cardClasses = implode(' ', array_filter([
        'rounded-xl border p-4',
        $animationsEnabled ? 'transition' : null,
        $animationsEnabled && $hoverEffectsEnabled ? 'hover:border-gray-300 dark:hover:border-gray-600' : null,
    ]));
@endphp

<x-form-section submit="save">
    <x-slot name="title">
        {{ __('theme-switcher::theme-switcher.appearance') }}
    </x-slot>

    <x-slot name="description">
        {{ __('theme-switcher::theme-switcher.appearance_description') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 space-y-4">
            <div class="grid gap-3">
                @foreach ($allowedPreferences as $option)
                    @php($meta = $optionMeta[$option] ?? $optionMeta['light'])
                    <label class="block cursor-pointer">
                        <input type="radio" value="{{ $option }}" wire:model.live="themePreference" class="sr-only">
                        <div class="{{ $cardClasses }} {{ $themePreference === $option ? 'border-indigo-500 bg-indigo-50 shadow-sm dark:border-indigo-400 dark:bg-indigo-500/10' : 'border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-900' }}">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $meta['label'] }}</div>
                                    <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $meta['description'] }}</div>
                                </div>
                                <div class="rounded-full border border-gray-200 bg-white p-2 dark:border-gray-600 dark:bg-gray-800 {{ $meta['iconClass'] }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                        {!! $meta['icon'] !!}
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </label>
                @endforeach
            </div>

            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('theme-switcher::theme-switcher.appearance_hint') }}
            </p>
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('theme-switcher::theme-switcher.saved') }}
        </x-action-message>

        <x-button>
            {{ __('theme-switcher::theme-switcher.save') }}
        </x-button>
    </x-slot>
</x-form-section>
