<x-filament-panels::page>
    <div class="w-full min-h-[60vh] flex items-center justify-center">
        <div class="bg-white dark:bg-[#1f2937] shadow-2xl rounded-2xl p-8 max-w-lg w-full">
            <div class="flex justify-center mb-5">
                <div class="bg-red-100 dark:bg-red-500/10 rounded-full p-4">
                    <x-heroicon-o-x-circle class="w-10 h-10 text-red-500 dark:text-red-400" />
                </div>
            </div>
            <h1 class="text-2xl font-bold text-red-700 dark:text-white mb-2 text-center">Subscription Cancelled</h1>
            <p class="text-gray-600 dark:text-gray-400 text-center mb-6">Your subscription was cancelled.</p>

            @if($subscription)
                <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4 mb-6">
                    <h2 class="text-sm font-semibold text-gray-800 dark:text-white mb-2">Cancelled Plan</h2>
                    <p class="text-gray-600 dark:text-gray-400">{{ $subscription['plan_name'] ?? 'Unknown Plan' }}</p>
                </div>
            @endif

            <div class="border-t border-gray-200 dark:border-gray-700 pt-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Help Us Improve</h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm mb-4">We're sorry to see you go. Your feedback helps us improve our service.</p>

                <form wire:submit="submitReason">
                    {{ $this->form }}

                    <div class="mt-6 flex gap-3">
                        <button
                            type="submit"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-300">
                            Submit Feedback
                        </button>
                        <a href="{{ route('filament.billing.home') }}"
                           class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-sm font-medium rounded-lg transition-all duration-300">
                            Skip & Go Home
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-filament-panels::page>
