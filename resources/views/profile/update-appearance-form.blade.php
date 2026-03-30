@php
    $optionMeta = [
        'light' => [
            'label' => __('theme-switcher::theme-switcher.light_mode'),
            'description' => __('theme-switcher::theme-switcher.light_mode_description'),
            'iconClass' => 'text-indigo-500',
            'iconFill' => 'currentColor',
            'iconStroke' => 'none',
            'iconStrokeWidth' => null,
            'icon' => '<path d="M12.5 2a9.5 9.5 0 1 0 9.5 9.5c0-.34-.02-.68-.05-1.02 a7.5 7.5 0 1 1-9.43-8.43c-.01-.02-.01-.03-.02-.05Z"/>',
        ],
        'dark' => [
            'label' => __('theme-switcher::theme-switcher.dark_mode'),
            'description' => __('theme-switcher::theme-switcher.dark_mode_description'),
            'iconClass' => 'text-amber-500',
            'iconFill' => 'currentColor',
            'iconStroke' => 'none',
            'iconStrokeWidth' => null,
            'icon' => '<circle cx="12" cy="12" r="5"/><g stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="12" y1="1.5" x2="12" y2="4"/><line x1="12" y1="20" x2="12" y2="22.5"/><line x1="1.5" y1="12" x2="4" y2="12"/><line x1="20" y1="12" x2="22.5" y2="12"/><line x1="4.2" y1="4.2" x2="6" y2="6"/><line x1="18" y1="18" x2="19.8" y2="19.8"/><line x1="4.2" y1="19.8" x2="6" y2="18"/><line x1="18" y1="6" x2="19.8" y2="4.2"/></g>',
        ],
        'system' => [
            'label' => __('theme-switcher::theme-switcher.follow_system'),
            'description' => __('theme-switcher::theme-switcher.follow_system_description'),
            'iconClass' => 'text-gray-500 dark:text-gray-300',
            'iconFill' => 'none',
            'iconStroke' => 'currentColor',
            'iconStrokeWidth' => '1.8',
            'icon' => '<rect x="3" y="4" width="18" height="12" rx="2"></rect><path d="M8 20h8"></path><path d="M12 16v4"></path>',
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
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="{{ $meta['iconFill'] }}" stroke="{{ $meta['iconStroke'] }}" @if($meta['iconStrokeWidth']) stroke-width="{{ $meta['iconStrokeWidth'] }}" @endif>
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
