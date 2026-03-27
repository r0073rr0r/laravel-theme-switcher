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
                <label class="block cursor-pointer">
                    <input type="radio" value="light" wire:model.live="themePreference" class="sr-only">
                    <div class="rounded-xl border p-4 transition {{ $themePreference === 'light' ? 'border-indigo-500 bg-indigo-50 shadow-sm dark:border-indigo-400 dark:bg-indigo-500/10' : 'border-gray-200 bg-white hover:border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-600' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ __('theme-switcher::theme-switcher.light_mode') }}</div>
                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('theme-switcher::theme-switcher.light_mode_description') }}</div>
                            </div>
                            <div class="rounded-full border border-gray-200 bg-white p-2 text-amber-500 dark:border-gray-600 dark:bg-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 18a6 6 0 1 0 0-12 6 6 0 0 0 0 12Zm0 4a1 1 0 0 1-1-1v-1a1 1 0 1 1 2 0v1a1 1 0 0 1-1 1Zm0-18a1 1 0 0 1-1-1V2a1 1 0 1 1 2 0v1a1 1 0 0 1-1 1Zm10 8a1 1 0 1 1 0 2h-1a1 1 0 1 1 0-2h1ZM4 12a1 1 0 1 1 0 2H3a1 1 0 1 1 0-2h1Zm14.364 6.95a1 1 0 0 1 1.414-1.414l.707.707a1 1 0 0 1-1.414 1.414l-.707-.707Zm-12.02-12.02a1 1 0 0 1 1.414-1.414l.707.707A1 1 0 0 1 7.05 7.637l-.707-.707Zm12.727.707a1 1 0 0 1 0-1.414l.707-.707a1 1 0 0 1 1.414 1.414l-.707.707a1 1 0 0 1-1.414 0Zm-12.02 12.02a1 1 0 0 1 0-1.414l.707-.707a1 1 0 1 1 1.414 1.414l-.707.707a1 1 0 0 1-1.414 0Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </label>

                <label class="block cursor-pointer">
                    <input type="radio" value="dark" wire:model.live="themePreference" class="sr-only">
                    <div class="rounded-xl border p-4 transition {{ $themePreference === 'dark' ? 'border-indigo-500 bg-indigo-50 shadow-sm dark:border-indigo-400 dark:bg-indigo-500/10' : 'border-gray-200 bg-white hover:border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-600' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ __('theme-switcher::theme-switcher.dark_mode') }}</div>
                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('theme-switcher::theme-switcher.dark_mode_description') }}</div>
                            </div>
                            <div class="rounded-full border border-gray-200 bg-white p-2 text-indigo-500 dark:border-gray-600 dark:bg-gray-800">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M21.752 15.002A9.718 9.718 0 0 1 12 22a10 10 0 0 1-9.954-9.046A10 10 0 0 1 13 2.248a1 1 0 0 1 .493 1.882 8 8 0 0 0 8.259 13.377 1 1 0 0 1 1.245 1.495 9.93 9.93 0 0 1-1.245 1Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </label>

                <label class="block cursor-pointer">
                    <input type="radio" value="system" wire:model.live="themePreference" class="sr-only">
                    <div class="rounded-xl border p-4 transition {{ $themePreference === 'system' ? 'border-indigo-500 bg-indigo-50 shadow-sm dark:border-indigo-400 dark:bg-indigo-500/10' : 'border-gray-200 bg-white hover:border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:hover:border-gray-600' }}">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-gray-100">{{ __('theme-switcher::theme-switcher.follow_system') }}</div>
                                <div class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ __('theme-switcher::theme-switcher.follow_system_description') }}</div>
                            </div>
                            <div class="rounded-full border border-gray-200 bg-white p-2 text-gray-500 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M4 5a3 3 0 0 1 3-3h10a3 3 0 0 1 3 3v8a3 3 0 0 1-3 3h-4l-4 4v-4H7a3 3 0 0 1-3-3V5Zm3-1a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4v1.586L12.586 14H17a1 1 0 0 0 1-1V5a1 1 0 0 0-1-1H7Z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </label>
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
