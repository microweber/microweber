<x-filament-panels::page>
    <div class="flex items-center justify-center h-[80vh]">
        <div class="bg-white dark:bg-[#1f2937]  shadow-2xl rounded-2xl p-10 max-w-sm text-center">
            <div class="flex justify-center mb-5">
                <div class="bg-green-100 dark:bg-green-500/10 rounded-full p-4">
                    <svg class="w-10 h-10 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
            </div>
            <h1 class="text-2xl font-bold text-green-700 dark:text-white mb-2">Success!</h1>
            <p class="text-gray-600 dark:text-gray-400">Your subscription was successful.</p>

            <div class="mt-6">
                <a href="{{ route('filament.billing.home') }}"
                   class="inline-block px-6 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg transition-all duration-300">
                    Go to Dashboard
                </a>
            </div>
        </div>
    </div>

</x-filament-panels::page>
