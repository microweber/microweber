<div class="space-y-6">
    <!-- Order Information -->
    <div class="grid grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500">{{ __('Order Number') }}</p>
            <p class="font-medium">{{ $order->order_reference_id }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">{{ __('Order Date') }}</p>
            <p class="font-medium">{{ $order->created_at->format('M j, Y') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500">{{ __('Status') }}</p>
            <p class="font-medium">
                @if($order->order_status === 'completed')
                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                        {{ __('Completed') }}
                    </span>
                @elseif($order->order_status === 'pending')
                    <span class="inline-flex items-center rounded-md bg-yellow-50 px-2 py-1 text-xs font-medium text-yellow-800 ring-1 ring-inset ring-yellow-600/20">
                        {{ __('Pending') }}
                    </span>
                @else
                    <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                        {{ __('New') }}
                    </span>
                @endif
            </p>
        </div>
        <div>
            <p class="text-sm text-gray-500">{{ __('Payment Status') }}</p>
            <p class="font-medium">
                @if($order->is_paid)
                    <span class="inline-flex items-center rounded-md bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                        {{ __('Paid') }}
                    </span>
                @else
                    <span class="inline-flex items-center rounded-md bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/10">
                        {{ __('Unpaid') }}
                    </span>
                @endif
            </p>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="border-t pt-4">
        <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Customer Information') }}</h4>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">{{ __('Name') }}</p>
                <p>{{ $order->first_name }} {{ $order->last_name }}</p>
            </div>
            <div>
                <p class="text-gray-500">{{ __('Email') }}</p>
                <p>{{ $order->email }}</p>
            </div>
            <div>
                <p class="text-gray-500">{{ __('Phone') }}</p>
                <p>{{ $order->phone ?: __('N/A') }}</p>
            </div>
        </div>
    </div>

    <!-- Shipping Address -->
    <div class="border-t pt-4">
        <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Shipping Address') }}</h4>
        <div class="text-sm">
            <p>{{ $order->address }}</p>
            @if($order->address2)
                <p>{{ $order->address2 }}</p>
            @endif
            <p>
                {{ $order->city }}{{ $order->city && $order->state ? ', ' : '' }}{{ $order->state }}
                {{ $order->zip }}
            </p>
            <p>{{ $order->country }}</p>
        </div>
    </div>

    <!-- Order Items -->
    <div class="border-t pt-4">
        <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Order Items') }}</h4>
        <div class="space-y-3">
            @forelse($order->cart as $cartItem)
                <div class="flex items-center justify-between py-2 border-b border-gray-100">
                    <div class="flex items-center space-x-3">
                        @if($cartItem->products && $cartItem->products->first())
                            <img src="{{ $cartItem->products->first()->thumbnail() }}" alt="{{ $cartItem->products->first()->title }}" class="h-12 w-12 rounded object-cover">
                            <div>
                                <p class="font-medium text-sm">{{ $cartItem->products->first()->title }}</p>
                                <p class="text-xs text-gray-500">{{ __('Qty:') }} {{ $cartItem->qty }}</p>
                            </div>
                        @else
                            <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center text-xs text-gray-500">
                                {{ __('N/A') }}
                            </div>
                            <div>
                                <p class="font-medium text-sm">{{ __('Product') }} #{{ $cartItem->rel_id }}</p>
                                <p class="text-xs text-gray-500">{{ __('Qty:') }} {{ $cartItem->qty }}</p>
                            </div>
                        @endif
                    </div>
                    <p class="font-medium text-sm">{{ number_format($cartItem->price * $cartItem->qty, 2) }} {{ $order->currency }}</p>
                </div>
            @empty
                <p class="text-sm text-gray-500">{{ __('No items found') }}</p>
            @endforelse
        </div>
    </div>

    <!-- Order Totals -->
    <div class="border-t pt-4">
        <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Order Total') }}</h4>
        <div class="space-y-2 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-600">{{ __('Subtotal') }}</span>
                <span>{{ number_format($order->amount ?? 0, 2) }} {{ $order->currency }}</span>
            </div>
            <div class="flex justify-between font-semibold text-base pt-2 border-t">
                <span>{{ __('Total') }}</span>
                <span>{{ number_format($order->amount ?? 0, 2) }} {{ $order->currency }}</span>
            </div>
        </div>
    </div>

    @if($order->other_info)
        <div class="border-t pt-4">
            <h4 class="text-sm font-semibold text-gray-900 mb-3">{{ __('Additional Information') }}</h4>
            <p class="text-sm text-gray-600">{{ $order->other_info }}</p>
        </div>
    @endif
</div>
