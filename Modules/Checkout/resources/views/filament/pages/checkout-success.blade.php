<x-filament::page>
    <div class="flex items-center justify-center min-h-[300px]">
        <div class="text-center">
            <div class="mb-4">
                <x-heroicon-o-check-circle class="inline-block w-16 h-16 text-success-500" />
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Order Completed Successfully!
            </h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Thank you for your purchase. Your order has been successfully processed.
            </p>
            <div class="mt-6">
                <x-filament::button
                    color="primary"
                    tag="a"
                    href="{{ url('/') }}"
                >
                    Continue Shopping
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament::page>
