@php
/*
Livewire component for locale switching
Usage: <livewire:multilanguage.locale-switcher />
*/
@endphp

<div class="relative" x-data="{ open: @entangle('isOpen') }">
    <!-- Current Language Button -->
    <button
        @click="open = !open"
        type="button"
        class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:text-gray-200 dark:hover:bg-gray-700"
        aria-expanded="false"
        aria-haspopup="true"
    >
        <div class="flex items-center space-x-2">
            @if(isset($supportedLanguages['current']['iconUrl']) && $supportedLanguages['current']['iconUrl'])
                <img src="{{ $supportedLanguages['current']['iconUrl'] }}" alt="" class="w-5 h-5 rounded">
            @else
                <span class="text-lg">{{ $supportedLanguages['current']['abr'] ?? strtoupper(substr($currentLocale, 0, 2)) }}</span>
            @endif
            <span class="hidden sm:inline">{{ $supportedLanguages['current']['display_name'] ?? $currentLocale }}</span>
        </div>
        <svg class="w-4 h-4 ml-2 text-gray-400 transition-transform duration-200" :class="{ 'rotate-180': open }" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </button>

    <!-- Language Dropdown -->
    <div
        x-show="open"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        @click.away="open = false"
        class="absolute right-0 z-50 w-56 mt-2 origin-top-right bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none dark:bg-gray-800 dark:border-gray-700 dark:divide-gray-700"
        role="menu"
        aria-orientation="vertical"
        aria-labelledby="language-menu-button"
        tabindex="-1"
    >
        <div class="py-1">
            <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase dark:text-gray-400">
                Select Language
            </div>

            @if(!empty($supportedLanguages['others']))
                @foreach($supportedLanguages['others'] as $language)
                    <button
                        wire:click="changeLocale('{{ $language['locale'] }}')"
                        type="button"
                        class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700"
                        role="menuitem"
                    >
                        <div class="flex items-center justify-between w-full">
                            <div class="flex items-center space-x-3">
                                @if(isset($language['iconUrl']) && $language['iconUrl'])
                                    <img src="{{ $language['iconUrl'] }}" alt="" class="w-5 h-5 rounded">
                                @else
                                    <span class="text-base">{{ $language['abr'] }}</span>
                                @endif
                                <span>{{ $language['display_name'] }}</span>
                            </div>
                        </div>
                    </button>
                @endforeach
            @else
                <div class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                    No other languages available
                </div>
            @endif
        </div>

        <div class="py-1">
            <div class="px-4 py-2 text-xs text-gray-400">
                Current: {{ $supportedLanguages['current']['display_name'] ?? $currentLocale }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            Livewire.on('localeChanged', ({ locale }) => {
                // Reload page with new locale
                const currentUrl = new URL(window.location.href);
                currentUrl.searchParams.set('localeRedirect', locale);
                window.location.href = currentUrl.toString();
            });
        });
    </script>
    @endpush
</div>
