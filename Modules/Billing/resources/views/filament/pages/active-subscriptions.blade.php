<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Stats Overview --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-[#1f2937] rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Active Subscriptions</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['active_count'] ?? 0 }}</p>
                    </div>
                    <div class="bg-green-100 dark:bg-green-500/10 rounded-lg p-3">
                        <x-heroicon-o-credit-card class="w-6 h-6 text-green-600 dark:text-green-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#1f2937] rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">In Trial</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">{{ $stats['trialing_count'] ?? 0 }}</p>
                    </div>
                    <div class="bg-yellow-100 dark:bg-yellow-500/10 rounded-lg p-3">
                        <x-heroicon-o-clock class="w-6 h-6 text-yellow-600 dark:text-yellow-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#1f2937] rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Monthly Spend</p>
                        <p class="text-3xl font-bold text-gray-800 dark:text-white mt-1">
                            ${{ number_format($stats['monthly_spend'] ?? 0, 2) }}
                        </p>
                    </div>
                    <div class="bg-primary-100 dark:bg-primary-500/10 rounded-lg p-3">
                        <x-heroicon-o-currency-dollar class="w-6 h-6 text-primary-600 dark:text-primary-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#1f2937] rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Next Billing</p>
                        <p class="text-xl font-bold text-gray-800 dark:text-white mt-1">
                            @if($stats['next_billing_date'])
                                {{ \Carbon\Carbon::parse($stats['next_billing_date'])->toFormattedDateString() }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div class="bg-blue-100 dark:bg-blue-500/10 rounded-lg p-3">
                        <x-heroicon-o-calendar class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                    </div>
                </div>
            </div>
        </div>

        {{-- Subscriptions Table --}}
        <div class="bg-white dark:bg-[#1f2937] rounded-xl shadow-sm overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                <div>
                    <h2 class="text-lg font-semibold text-gray-800 dark:text-white">Your Subscriptions</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage your active subscriptions</p>
                </div>
                <a href="{{ route('billing.portal') }}"
                   class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition">
                    <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 mr-2" />
                    Billing Portal
                </a>
            </div>

            @if(empty($groupedSubscriptions))
                <div class="p-10 text-center">
                    <div class="flex justify-center mb-4">
                        <div class="bg-yellow-100 dark:bg-yellow-500/10 rounded-full p-3">
                            <x-heroicon-o-exclamation-circle class="w-8 h-8 text-yellow-500 dark:text-yellow-400" />
                        </div>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-1">No Active Subscriptions</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">You don't currently have any active subscriptions.</p>
                    <div class="mt-5">
                        <a href="{{ route('filament.billing.pages.new-subscription') }}"
                           class="inline-flex items-center px-5 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-300">
                            <x-heroicon-o-plus class="w-4 h-4 mr-2" />
                            Add Subscription
                        </a>
                    </div>
                </div>
            @else
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach($groupedSubscriptions as $groupName => $subscriptions)
                        <div class="p-6">
                            <h3 class="text-md font-semibold text-gray-800 dark:text-white mb-4 flex items-center">
                                <x-heroicon-o-folder class="w-5 h-5 mr-2 text-gray-400" />
                                {{ $groupName }}
                            </h3>

                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                                @foreach($subscriptions as $subscription)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 hover:shadow-md transition-shadow">
                                        <div class="flex justify-between items-start mb-3">
                                            <div>
                                                <h4 class="font-semibold text-lg text-gray-800 dark:text-white">
                                                    {{ $subscription['plan']['name'] }}
                                                </h4>
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    {{ $subscription['stripe_status'] === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' }}">
                                                    {{ ucfirst($subscription['stripe_status']) }}
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-2xl font-bold text-gray-800 dark:text-white">
                                                    ${{ number_format($subscription['plan']['price'], 2) }}
                                                </p>
                                                <p class="text-sm text-gray-500 dark:text-gray-400">/{{ $subscription['plan']['billing_interval'] }}</p>
                                            </div>
                                        </div>

                                        @if($subscription['plan']['description'])
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                                {{ $subscription['plan']['description'] }}
                                            </p>
                                        @endif

                                        <div class="space-y-2 text-sm mb-4">
                                            <div class="flex justify-between">
                                                <span class="text-gray-500 dark:text-gray-400">Renews on:</span>
                                                <span class="text-gray-800 dark:text-white">
                                                    {{ $subscription['ends_at'] ? \Carbon\Carbon::parse($subscription['ends_at'])->toFormattedDateString() : 'N/A' }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500 dark:text-gray-400">Payment:</span>
                                                <span class="text-gray-800 dark:text-white">{{ strtoupper($subscription['plan']['currency']) }}</span>
                                            </div>
                                        </div>

                                        <div class="flex gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                            <button
                                                wire:click="cancelSubscription({{ $subscription['id'] }})"
                                                wire:confirm="Are you sure you want to cancel this subscription?"
                                                class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition">
                                                <x-heroicon-o-trash class="w-4 h-4 mr-2" />
                                                Cancel
                                            </button>
                                            <a href="{{ route('billing.portal') }}"
                                               target="_blank"
                                               class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-sm font-medium rounded-lg transition" rel="noopener noreferrer">
                                                <x-heroicon-o-cog class="w-4 h-4 mr-2" />
                                                Manage
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-filament-panels::page>
