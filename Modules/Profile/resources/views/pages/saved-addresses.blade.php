<x-filament-panels::page>
    <div class="max-w-5xl mx-auto">
        {{-- Page Description --}}
        <div class="mb-6">
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('Manage your saved shipping and billing addresses. These addresses will be available during checkout for faster order placement.') }}
            </p>
        </div>

        {{-- Table Component --}}
        <div class="bg-white dark:bg-gray-900 rounded-xl shadow-sm ring-1 ring-gray-200 dark:ring-white/10">
            {{ $this->table }}
        </div>

        {{-- Help Text --}}
        <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
            <div class="flex items-start gap-3">
                <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5"/>
                <div>
                    <h4 class="text-sm font-medium text-blue-900 dark:text-blue-100">
                        {{ __('Address Tips') }}
                    </h4>
                    <ul class="mt-2 text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        <li>{{ __('• Add a descriptive label (e.g., "Home", "Office") to easily identify addresses') }}</li>
                        <li>{{ __('• Mark addresses as "Billing" or "Shipping" type for automatic selection at checkout') }}</li>
                        <li>{{ __('• Keep your addresses up-to-date to avoid delivery issues') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
