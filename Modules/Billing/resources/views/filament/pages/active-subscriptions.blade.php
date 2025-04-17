<x-filament::page>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Active Subscriptions</h2>
        <a href="{{ route('billing.portal') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm transition">
            Manage Billing Portal
        </a>
    </div>
    @if(empty($groupedSubscriptions))
        <div class="mt-10 flex justify-center">
            <div class="bg-white dark:bg-[#1f2937] p-6 max-w-md text-center">
                <div class="flex justify-center mb-4">
                    <div class="bg-yellow-100 dark:bg-yellow-500/10 rounded-full p-3">
                        <svg class="w-8 h-8 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M12 2a10 10 0 100 20 10 10 0 000-20z" />
                        </svg>
                    </div>
                </div>
                <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">No Active Subscriptions</h2>
                <p class="text-gray-600 dark:text-gray-400 text-sm">You don’t currently have any active subscriptions.</p>

                <div class="mt-5">
                    <a href="{{ route('filament.billing.pages.new-subscription') }}"
                       class="inline-block px-5 py-2 bg-primary-500 hover:bg-primary-500 text-white text-sm font-medium rounded-lg transition-all duration-300">
                        Add Subscription
                    </a>
                </div>
            </div>
        </div>

    @else
        <div class="space-y-8">
            @foreach($groupedSubscriptions as $groupName => $subscriptions)
                <div>
                    <h3 class="text-md font-semibold mb-4">{{ $groupName }}</h3>
                    <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
                        @foreach($subscriptions as $subscription)
                            <div class="p-4 border rounded shadow-sm">
                                <h4 class="font-semibold text-lg mb-2">
                                    {{ $subscription['plan']['name'] ?? 'Unknown Plan' }}
                                </h4>
                                <p class="mb-2">
                                    {{ $subscription['plan']['description'] ?? '' }}
                                </p>
                                <p class="text-sm mb-1">
                                    <strong>Price:</strong> US${{ number_format($subscription['plan']['price'] ?? 0, 2) }} per {{ $subscription['plan']['billing_interval'] ?? 'month' }}
                                </p>
                                <p class="text-sm mb-1">
                                    <strong>Renews on:</strong> {{ $subscription['ends_at'] ? \Carbon\Carbon::parse($subscription['ends_at'])->toFormattedDateString() : 'N/A' }}
                                </p>
                                <p class="text-sm mb-1">
                                    <strong>Payment Method:</strong> Stripe
                                </p>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <button class="p-2 bg-primary-600 hover:bg-primary-700 text-white rounded-full transition" title="Update Subscription" aria-label="Update Subscription">
                                        <x-heroicon-m-pencil class="w-4 h-4" />
                                    </button>
                                    <button class="p-2 bg-red-600 hover:bg-red-700 text-white rounded-full transition" title="Cancel Subscription" aria-label="Cancel Subscription">
                                        <x-heroicon-m-trash class="w-4 h-4" />
                                    </button>
                                    <a href="{{ route('billing.portal') }}" target="_blank" class="p-2 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-full transition" title="Manage Subscription" aria-label="Manage Subscription">
                                        <x-heroicon-m-arrow-top-right-on-square class="w-4 h-4" />
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-filament::page>
