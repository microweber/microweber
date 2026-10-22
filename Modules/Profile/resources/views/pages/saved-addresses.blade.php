<x-filament-panels::page class="mw-records-page">
    {{-- Page Description --}}
    <div class="mb-6">
        <p class="text-sm text-gray-500 dark:text-gray-400">
            {{ __('Manage your saved shipping and billing addresses. These addresses will be available during checkout for faster order placement.') }}
        </p>
    </div>

    {{-- Table — the admin resource-list card chrome (white surface, lg radius,
         soft shadow, clipped corners) is supplied by the .mw-records-page marker
         on the page wrapper; do NOT hand-roll a second card wrapper here or it
         double-wraps at a mismatched radius. --}}
    {{ $this->table }}

    {{-- Help Text --}}
    <div class="mt-6 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-lg border border-gray-200 dark:border-gray-700">
        <div class="flex items-start gap-3">
            <x-heroicon-o-information-circle class="w-5 h-5 text-gray-500 dark:text-gray-400 flex-shrink-0 mt-0.5"/>
            <div>
                <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">
                    {{ __('Address Tips') }}
                </h4>
                <ul class="mt-2 text-sm text-gray-600 dark:text-gray-300 space-y-1">
                    <li>{{ __('• Add a descriptive label (e.g., "Home", "Office") to easily identify addresses') }}</li>
                    <li>{{ __('• Mark addresses as "Billing" or "Shipping" type for automatic selection at checkout') }}</li>
                    <li>{{ __('• Keep your addresses up-to-date to avoid delivery issues') }}</li>
                </ul>
            </div>
        </div>
    </div>
</x-filament-panels::page>
