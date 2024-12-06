<x-filament::page>
    <div class="flex items-center justify-center min-h-[300px]">
        <div class="text-center">
            <div class="mb-4">
                <x-heroicon-o-x-circle class="inline-block w-16 h-16 text-danger-500" />
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Checkout Failed
            </h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                We apologize, but there was an issue processing your order.
            </p>
            <div class="mt-6 space-x-4">
                <x-filament::button
                    color="primary"
                    tag="a"
                    href="{{ route('filament.checkout.resources.checkout.index') }}"
                >
                    Try Again
                </x-filament::button>
                <x-filament::button
                    color="gray"
                    tag="a"
                    href="{{ url('/') }}"
                >
                    Return to Shop
                </x-filament::button>
            </div>
        </div>
    </div>
</x-filament::page>
