<div class="currency-switcher" x-data="{ open: false }">
    @if(count($currencies) > 1)
        <div class="relative">
            <button
                type="button"
                @click="open = !open"
                class="flex items-center space-x-2 px-4 py-2 rounded-lg border border-gray-300 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-800 dark:border-gray-600 dark:hover:bg-gray-700"
            >
                @php
                    $current = collect($currencies)->firstWhere('code', $selectedCurrency);
                @endphp
                <span class="font-medium text-gray-900 dark:text-white">
                    {{ $current['symbol'] ?? '$' }} {{ $selectedCurrency }}
                </span>
                <svg
                    class="w-4 h-4 text-gray-500 dark:text-gray-400 transition-transform"
                    :class="{ 'rotate-180': open }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>

            <div
                x-show="open"
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-100"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
                style="display: none;"
            >
                <div class="py-1">
                    <div class="px-4 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                        {{ _e('Select Currency', true) }}
                    </div>
                    
                    @foreach($currencies as $currency)
                        <button
                            type="button"
                            wire:click="switchCurrency('{{ $currency['code'] }}')"
                            wire:loading.attr="disabled"
                            class="w-full text-left px-4 py-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors {{ $selectedCurrency === $currency['code'] ? 'bg-primary-50 dark:bg-primary-900/20 border-l-4 border-primary-500' : 'border-l-4 border-transparent' }}"
                        >
                            <div class="flex items-center space-x-3">
                                <span class="text-lg">{{ $currency['symbol'] }}</span>
                                <div>
                                    <div class="font-medium text-gray-900 dark:text-white">
                                        {{ $currency['name'] }}
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $currency['code'] }}
                                    </div>
                                </div>
                            </div>
                            
                            @if($currency['is_default'])
                                <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded-full dark:bg-green-900 dark:text-green-200">
                                    {{ _e('Default', true) }}
                                </span>
                            @endif
                            
                            @if($selectedCurrency === $currency['code'])
                                <svg class="w-5 h-5 text-primary-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            @endif
                        </button>
                    @endforeach
                </div>
            </div>
        </div>

        <div wire:loading wire:target="switchCurrency" class="mt-2 text-sm text-gray-600 dark:text-gray-400">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 inline text-primary-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            {{ _e('Updating currency...', true) }}
        </div>
    @endif
</div>

@once
    @push('scripts')
        <script>
            document.addEventListener('livewire:initialized', () => {
                Livewire.on('refresh-page', () => {
                    window.location.reload();
                });
            });
        </script>
    @endpush
@endonce
