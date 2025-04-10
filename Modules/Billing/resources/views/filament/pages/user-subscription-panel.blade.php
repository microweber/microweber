<x-filament-panels::page>

    <div class="mb-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-lg font-semibold">Your Active Subscriptions</h2>
            <a href="{{ route('billing.portal') }}" class="inline-flex items-center px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-md shadow-sm transition">
                Manage Billing Portal
            </a>
        </div>

        @if($this->activeSubscriptions->isEmpty())
            <p>You have no active subscriptions.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($this->activeSubscriptions as $subscription)
                    <div class="border rounded-lg p-4 shadow-sm">
                        <div class="font-semibold text-lg mb-2">
                            {{ $subscription->plan->name ?? 'N/A' }}
                        </div>
                        <div class="text-xl font-bold mb-1">
                            ${{ number_format($subscription->plan->price ?? 0, 2) }}
                        </div>
                        <div class="text-sm text-gray-600">
                            {{ ucfirst($subscription->plan->billing_interval ?? 'N/A') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <div>
        {{ $this->form }}

        <div class="mt-4">
            <x-filament::button wire:click="submit" color="primary">
                Update Subscription
            </x-filament::button>
        </div>
    </div>

</x-filament-panels::page>
