@php
    $cartItems = app()->cart_manager->get() ?? [];
    $cartTotal = cart_total();
@endphp

<div class="bg-white rounded-lg shadow p-6" wire:key="review-order">
    <h3 class="text-lg font-medium text-gray-900 mb-4">Order Summary</h3>

    <div class="space-y-4">

        @if(count($cartItems) == 0)
            <div class="text-sm text-gray-600">No items in cart</div>
        @else
            @foreach($cartItems as $item)
                <div class="flex justify-between items-center" wire:key="item-{{ $item['id'] }}">
                    <div class="flex items-center space-x-3">
                        <span class="text-sm text-gray-600">{{ $item['qty'] }}x</span>
                        <span class="text-sm text-gray-900">{{ $item['title'] }}</span>
                    </div>
                    <span class="text-sm font-medium text-gray-900">{{ currency_format($item['price'] * $item['qty']) }}</span>
                </div>
            @endforeach
        @endif


        <div class="border-t border-gray-200 pt-4 mt-4">
            <div class="flex justify-between items-center">
                <span class="text-base font-medium text-gray-900">Total</span>
                <span class="text-base font-medium text-gray-900">{{ currency_format($cartTotal) }}</span>
            </div>
        </div>
    </div>
</div>
