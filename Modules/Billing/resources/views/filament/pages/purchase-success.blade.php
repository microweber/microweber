<x-filament-panels::page>
    <div class="w-full min-h-[60vh] flex items-center justify-center">
        @if($error)
            <div class="bg-white dark:bg-[#1f2937] shadow-2xl rounded-2xl p-10 max-w-md text-center">
                <div class="flex justify-center mb-5">
                    <div class="bg-red-100 dark:bg-red-500/10 rounded-full p-4">
                        <x-heroicon-o-exclamation-triangle class="w-10 h-10 text-red-500 dark:text-red-400" />
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-red-700 dark:text-white mb-2">Error</h1>
                <p class="text-gray-600 dark:text-gray-400">{{ $error }}</p>
                <div class="mt-6">
                    <a href="{{ route('filament.billing.home') }}"
                       class="inline-block px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-300">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        @else
            <div class="bg-white dark:bg-[#1f2937] shadow-2xl rounded-2xl p-8 max-w-lg w-full">
                <div class="flex justify-center mb-5">
                    <div class="bg-green-100 dark:bg-green-500/10 rounded-full p-4">
                        <x-heroicon-o-check-circle class="w-10 h-10 text-green-500 dark:text-green-400" />
                    </div>
                </div>
                <h1 class="text-2xl font-bold text-green-700 dark:text-white mb-2 text-center">Success!</h1>
                <p class="text-gray-600 dark:text-gray-400 text-center mb-6">Your purchase was successful.</p>

                @if($checkoutSession)
                    <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Order Summary</h2>

                        <div class="space-y-3">
                            @if($order)
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Order ID:</span>
                                    <span class="font-medium text-gray-800 dark:text-white">#{{ $order['id'] }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Amount:</span>
                                    <span class="font-medium text-gray-800 dark:text-white">
                                        {{ $order['currency'] }} ${{ number_format($order['amount'], 2) }}
                                    </span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Transaction ID:</span>
                                    <span class="font-medium text-gray-800 dark:text-white text-sm">{{ $order['transaction_id'] }}</span>
                                </div>

                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Date:</span>
                                    <span class="font-medium text-gray-800 dark:text-white">{{ $order['created_at'] }}</span>
                                </div>
                            @else
                                <div class="flex justify-between">
                                    <span class="text-gray-600 dark:text-gray-400">Amount:</span>
                                    <span class="font-medium text-gray-800 dark:text-white">
                                        {{ $checkoutSession['currency'] }} ${{ number_format($checkoutSession['amount_total'], 2) }}
                                    </span>
                                </div>
                            @endif

                            <div class="flex justify-between">
                                <span class="text-gray-600 dark:text-gray-400">Payment Status:</span>
                                <span class="font-medium text-green-600 dark:text-green-400 capitalize">
                                    {{ $checkoutSession['payment_status'] }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($invoice && isset($invoice['pdf_url']))
                        <div class="flex gap-3">
                            <button
                                wire:click="downloadInvoice"
                                class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-300">
                                <x-heroicon-o-document-arrow-down class="w-5 h-5 mr-2" />
                                Download Invoice
                            </button>
                            <a href="{{ route('filament.billing.home') }}"
                               class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-800 dark:text-white text-sm font-medium rounded-lg transition-all duration-300">
                                Go to Dashboard
                            </a>
                        </div>
                    @else
                        <div class="text-center">
                            <a href="{{ route('filament.billing.home') }}"
                               class="inline-block px-8 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-all duration-300">
                                Go to Dashboard
                            </a>
                        </div>
                    @endif
                @endif
            </div>
        @endif
    </div>
</x-filament-panels::page>
