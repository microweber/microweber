<x-filament::page>
    <h2 class="text-2xl font-bold mb-6">My Active Subscriptions</h2>
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">Your Active Subscriptions</h2>
        <a href="{{ route('billing.portal') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm transition">
            Manage Billing Portal
        </a>
    </div>
    @if(empty($this->activeSubscriptions))
        <div class="p-4 bg-yellow-100 text-yellow-800 rounded">
            You have no active subscriptions.
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @foreach($this->activeSubscriptions as $subscription)
                <div class="p-4 border rounded shadow-sm">
                    <h3 class="font-semibold text-lg mb-2">
                        {{ $subscription['plan']['name'] ?? 'Unknown Plan' }}
                    </h3>
                    <p class="mb-2">
                        {{ $subscription['plan']['description'] ?? '' }}
                    </p>
                    <p class="text-sm mb-1">
                        <strong>Started:</strong> {{ \Carbon\Carbon::parse($subscription['starts_at'])->toDayDateTimeString() ?? 'N/A' }}
                    </p>
                    <p class="text-sm mb-1">
                        <strong>Ends:</strong> {{ $subscription['ends_at'] ? \Carbon\Carbon::parse($subscription['ends_at'])->toDayDateTimeString() : 'N/A' }}
                    </p>
                    <p class="text-sm mb-1">
                        <strong>Status:</strong> {{ ucfirst($subscription['stripe_status'] ?? 'unknown') }}
                    </p>
                </div>
            @endforeach
        </div>
    @endif
</x-filament::page>
