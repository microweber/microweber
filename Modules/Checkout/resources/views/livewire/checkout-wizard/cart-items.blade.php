<div class="cart-items-container">
    @if(empty($cartItems))
        <div class="text-center py-8">
            <x-heroicon-o-shopping-cart class="w-16 h-16 mx-auto text-gray-300 mb-4" />
            <p class="text-gray-500">{{ __('Your cart is empty') }}</p>
            <a href="{{ site_url() }}" class="btn btn-primary mt-4">
                {{ __('Continue Shopping') }}
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($cartItems as $item)
                <div class="flex items-start space-x-4 p-4 bg-white rounded-lg border border-gray-200">
                    @if(!empty($item['item_image']))
                        <div class="flex-shrink-0">
                            <img
                                src="{{ $item['item_image'] }}"
                                alt="{{ $item['title'] ?? '' }}"
                                class="w-20 h-20 object-cover rounded"
                            />
                        </div>
                    @endif

                    <div class="flex-grow min-w-0">
                        <h4 class="text-sm font-medium text-gray-900 truncate">
                            {{ $item['title'] ?? '' }}
                        </h4>

                        @if(!empty($item['custom_fields']))
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $item['custom_fields'] }}
                            </p>
                        @endif

                        <div class="flex items-center justify-between mt-2">
                            <span class="text-sm text-gray-600">
                                {{ __('Qty') }}: {{ $item['qty'] ?? 1 }}
                            </span>
                            <span class="text-sm font-medium text-gray-900">
                                {{ currency_format(($item['price'] ?? 0) * ($item['qty'] ?? 1)) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
            <div class="flex justify-between items-center">
                <span class="text-base font-medium text-gray-900">{{ __('Subtotal') }}</span>
                <span class="text-xl font-bold text-gray-900">{{ currency_format($cartTotal) }}</span>
            </div>
        </div>

        <div class="mt-4 text-center">
            <a href="{{ site_url() }}" class="text-sm text-primary hover:underline">
                {{ __('Add more items') }}
            </a>
        </div>
    @endif
</div>

<style>
    .cart-items-container {
        min-height: 200px;
    }
</style>
