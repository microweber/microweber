<div class="review-order-container space-y-6">
    {{-- Contact Information --}}
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-medium text-gray-900">{{ __('Contact Information') }}</h4>
            <button
                type="button"
                wire:click="$dispatch('wizard::goToStep', { step: 2 })"
                class="text-sm text-primary hover:underline"
            >
                {{ __('Edit') }}
            </button>
        </div>
        <div class="text-sm text-gray-600">
            <p>{{ $data['first_name'] ?? '' }} {{ $data['last_name'] ?? '' }}</p>
            <p>{{ $data['email'] ?? '' }}</p>
            <p>{{ $data['phone'] ?? '' }}</p>
        </div>
    </div>

    {{-- Shipping Address --}}
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-medium text-gray-900">{{ __('Shipping Address') }}</h4>
            <button
                type="button"
                wire:click="$dispatch('wizard::goToStep', { step: 2 })"
                class="text-sm text-primary hover:underline"
            >
                {{ __('Edit') }}
            </button>
        </div>
        <div class="text-sm text-gray-600">
            <p>{{ $data['address'] ?? '' }}</p>
            <p>{{ $data['city'] ?? '' }}, {{ $data['state'] ?? '' }} {{ $data['postal_code'] ?? '' }}</p>
            <p>{{ app()->country_manager->getCountryName($data['country'] ?? '') }}</p>
        </div>
    </div>

    {{-- Shipping Method --}}
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-medium text-gray-900">{{ __('Shipping Method') }}</h4>
            <button
                type="button"
                wire:click="$dispatch('wizard::goToStep', { step: 3 })"
                class="text-sm text-primary hover:underline"
            >
                {{ __('Edit') }}
            </button>
        </div>
        <div class="text-sm text-gray-600">
            @php
                $shippingProviderId = $data['shipping_provider_id'] ?? null;
                $shippingMethod = $shippingProviderId ? app()->shipping_method_manager->getProviderById($shippingProviderId) : null;
                $shippingCost = $shippingProviderId ? app()->shipping_method_manager->getShippingCost($shippingProviderId, []) : 0;
            @endphp
            <p>{{ $shippingMethod['name'] ?? ucfirst($shippingMethod['provider'] ?? __('Standard Shipping')) }}</p>
            <p class="font-medium">
                @if($shippingCost > 0)
                    {{ currency_format($shippingCost) }}
                @else
                    <span class="text-green-600">{{ __('Free') }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Payment Method --}}
    <div class="bg-gray-50 rounded-lg p-4">
        <div class="flex justify-between items-center mb-3">
            <h4 class="font-medium text-gray-900">{{ __('Payment Method') }}</h4>
            <button
                type="button"
                wire:click="$dispatch('wizard::goToStep', { step: 4 })"
                class="text-sm text-primary hover:underline"
            >
                {{ __('Edit') }}
            </button>
        </div>
        <div class="text-sm text-gray-600">
            @php
                $paymentProviderId = $data['payment_provider_id'] ?? null;
                $paymentMethod = $paymentProviderId ? app()->payment_method_manager->getProviderById($paymentProviderId) : null;
            @endphp
            <p>{{ $paymentMethod['name'] ?? ucfirst($paymentMethod['provider'] ?? __('Standard Payment')) }}</p>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-gray-50 rounded-lg p-4">
        <h4 class="font-medium text-gray-900 mb-3">{{ __('Order Items') }}</h4>
        <div class="space-y-3">
            @foreach($cartItems as $item)
                <div class="flex items-center space-x-3 pb-3 border-b border-gray-200 last:border-0">
                    @if(!empty($item['item_image']))
                        <img
                            src="{{ $item['item_image'] }}"
                            alt="{{ $item['title'] ?? '' }}"
                            class="w-12 h-12 object-cover rounded"
                        />
                    @endif
                    <div class="flex-grow">
                        <p class="text-sm font-medium text-gray-900">{{ $item['title'] ?? '' }}</p>
                        <p class="text-xs text-gray-500">{{ __('Qty') }}: {{ $item['qty'] ?? 1 }}</p>
                    </div>
                    <div class="text-sm font-medium text-gray-900">
                        {{ currency_format(($item['price'] ?? 0) * ($item['qty'] ?? 1)) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Final Summary --}}
    <div class="bg-primary-50 rounded-lg p-4 border border-primary-200">
        <div class="space-y-2">
            @php
                $subtotal = $cartTotal;
                $shippingId = $data['shipping_provider_id'] ?? null;
                $shipping = $shippingId ? app()->shipping_method_manager->getShippingCost($shippingId, []) : 0;
                $discount = coupon_get_applied() ? cart_get_discount() : 0;
                $tax = cart_get_tax() ?? 0;
                $total = $subtotal + $shipping - $discount + $tax;
            @endphp

            <div class="flex justify-between text-sm">
                <span class="text-gray-600">{{ __('Subtotal') }}</span>
                <span>{{ currency_format($subtotal) }}</span>
            </div>

            <div class="flex justify-between text-sm">
                <span class="text-gray-600">{{ __('Shipping') }}</span>
                <span>{{ $shipping > 0 ? currency_format($shipping) : __('Free') }}</span>
            </div>

            @if($discount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ __('Discount') }}</span>
                    <span class="text-green-600">-{{ currency_format($discount) }}</span>
                </div>
            @endif

            @if($tax > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600">{{ __('Tax') }}</span>
                    <span>{{ currency_format($tax) }}</span>
                </div>
            @endif

            <div class="border-t pt-2 mt-2">
                <div class="flex justify-between items-center">
                    <span class="text-lg font-semibold">{{ __('Order Total') }}</span>
                    <span class="text-2xl font-bold text-primary">{{ currency_format($total) }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .review-order-container {
        max-width: 100%;
    }

    .text-primary {
        color: var(--primary-color, #3b82f6);
    }

    .bg-primary-50 {
        background-color: rgba(59, 130, 246, 0.05);
    }

    .border-primary-200 {
        border-color: rgba(59, 130, 246, 0.2);
    }
</style>
