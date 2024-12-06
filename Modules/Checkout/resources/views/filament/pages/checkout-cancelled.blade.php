<x-filament::page>
    <div class="flex items-center justify-center min-h-[300px]">
        <div class="text-center">
            <div class="mb-4">
                <x-heroicon-o-minus-circle class="inline-block w-16 h-16 text-warning-500" />
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Checkout Cancelled
            </h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Your checkout process has been cancelled. Your cart items have been saved if you'd like to complete your purchase later.
            </p>
            <div class="mt-6 space-x-4">
                <x-filament::button
                    color="primary"
                    tag="a"
                    href="{{ route('filament.checkout.resources.checkout.index') }}"
                >
                    Resume Checkout
                </x-filament::button>
                <x-filament::button
                    color="gray"
                    tag="a"
                    href="{{ url('/') }}"
                >
                    Continue Shopping
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament::page>
