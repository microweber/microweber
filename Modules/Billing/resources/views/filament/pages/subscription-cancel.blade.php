<x-filament-panels::page>

</x-filament-panels::page>
<div class="w-full min-h-screen flex items-center justify-center">
    <div class="bg-white dark:bg-[#1f2937] shadow-2xl rounded-2xl p-10 max-w-sm text-center">
        <div class="flex justify-center mb-5">
            <div class="bg-red-100 dark:bg-red-500/10 rounded-full p-4">
                <svg class="w-10 h-10 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>
        <h1 class="text-2xl font-bold text-red-700 dark:text-white mb-2">Subscription Cancelled</h1>
        <p class="text-gray-600 dark:text-gray-400">Your subscription was cancelled.</p>

        <div class="mt-6">
            <a href="{{ route('filament.billing.home') }}"
               class="inline-block px-6 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-all duration-300">
                Go to Billing
            </a>
        </div>
    </div>
</div>
