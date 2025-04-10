<x-filament::page>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Active Subscriptions</h2>
        <a href="{{ route('billing.portal') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm transition">
        Manage Subscriptions
        </a>
    </div>
    @if(empty($this->activeSubscriptions))
        <div class="p-4 bg-yellow-100 text-yellow-800 rounded">
            You have no active subscriptions.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-1 gap-6">
            @foreach($this->activeSubscriptions as $subscription)
                <div class="p-4 border rounded shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">
                        {{ $subscription['plan']['name'] ?? 'Unknown Plan' }}
                    </h3>
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
    @endif
</x-filament::page>
