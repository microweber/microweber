<div class="order-summary-container">
    <div class="space-y-3">
        <div class="flex justify-between text-sm">
            <span class="text-gray-600">{{ __('Subtotal') }}</span>
            <span class="font-medium">{{ currency_format($subtotal) }}</span>
        </div>

        @if($shipping > 0)
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">{{ __('Shipping') }}</span>
                <span class="font-medium">{{ currency_format($shipping) }}</span>
            </div>
        @else
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">{{ __('Shipping') }}</span>
                <span class="font-medium text-green-600">{{ __('Free') }}</span>
            </div>
        @endif

        @php
            $discount = coupon_get_applied() ? cart_get_discount() : 0;
            $tax = cart_get_tax() ?? 0;
        @endphp

        @if($discount > 0)
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">{{ __('Discount') }}</span>
                <span class="font-medium text-green-600">-{{ currency_format($discount) }}</span>
            </div>
        @endif

        @if($tax > 0)
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">{{ __('Tax') }}</span>
                <span class="font-medium">{{ currency_format($tax) }}</span>
            </div>
        @endif

        <div class="border-t pt-3 mt-3">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold">{{ __('Total') }}</span>
                <span class="text-xl font-bold text-primary">{{ currency_format($total) }}</span>
            </div>
        </div>
    </div>
</div>

<style>
    .order-summary-container {
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.5rem;
    }
</style>
