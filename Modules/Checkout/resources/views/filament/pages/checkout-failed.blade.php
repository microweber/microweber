<x-filament::page>
    <div class="flex items-center justify-center min-h-[300px]">
        <div class="text-center">
            <div class="mb-4">
                <x-heroicon-o-x-circle class="inline-block w-16 h-16 text-danger-500" />
            </div>
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">
                Checkout Failed
            </h2>

            {{-- Display the main error message --}}
            <div class="mt-4">
                @if (session('error'))
                    <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
            </div>

            {{-- Display any additional error details --}}
            @if ($errors->any())
                <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/10 rounded-lg">
                    <ul class="list-disc list-inside text-sm text-red-600 dark:text-red-400">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <p class="mt-4 text-gray-600 dark:text-gray-400">
                We apologize, but there was an issue processing your order. Please review the error details above and try again.
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


                @if (session()->has('error_details'))
                    <div class="mt-8 p-4 text-left bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2">Technical Details:</h3>
                        <pre class="text-xs text-gray-600 dark:text-gray-400 overflow-auto">{{ session('error_details') }}</pre>
                    </div>
                @endif

        </div>
    </div>

</x-filament::page>
