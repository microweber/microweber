<div class="space-y-4">


    @if(isset($cartItems) and $cartItems)

        @foreach($cartItems as $item)
            <div class="grid grid-cols-6 gap-4 items-center bg-white p-4 rounded-lg shadow">
                <div class="col-span-1">
                    @if(isset($item['picture']) and $item['picture'])
                        <img src="{{ $item['picture'] }}" alt="{{ $item['title'] }}"
                             class="w-20 h-20 object-cover rounded">
                    @else




                    @endif
                </div>

                <div class="col-span-2">
                    <h3 class="text-sm font-medium text-gray-900">{{ $item['title'] }}</h3>
                </div>

                <div class="col-span-1">
                    <p class="text-sm text-gray-600">{{ $item['price'] }}</p>
                </div>

                <div class="col-span-1">
                    <input
                        type="number"
                        min="1"
                        wire:model.live.debounce.1500ms="cartItems.{{ $loop->index }}.qty"
                        wire:change="updateQuantity('{{ $item['id'] }}', $event.target.value)"
                        value="{{ $item['qty'] }}"
                        class="block w-20 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                    >
                </div>

                <div class="col-span-1">
                    <button
                        wire:click="removeItem('{{ $item['id'] }}')"
                        type="button"
                        class="rounded-md bg-red-600 p-2 text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                             stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                        </svg>
                    </button>
                </div>
            </div>
        @endforeach


        @if(isset($cartTotals) and $cartTotals)
            <!-- Cart Totals Section -->
            <div class="mt-8 bg-white p-4 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Cart Totals</h3>
                <div class="space-y-2">
                    @foreach($cartTotals as $key => $total)
                        <div class="flex justify-between {{ $key === 'total' ? 'pt-4 border-t border-gray-200' : '' }}">
                        <span class="{{ $key === 'total' ? 'text-lg font-medium text-gray-900' : 'text-gray-600' }}">
                            {{ $total['label'] }}:
                        </span>
                            <span class="{{ $key === 'total' ? 'text-lg font-bold text-gray-900' : 'font-medium' }}">
                            {{ $total['amount'] }}
                        </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @else
        <p class="mt-2 text-gray-600 dark:text-gray-400">
            You have no items in your cart.
        </p>
        <div class="mt-6">
            <x-filament::button
                color="primary"
                tag="a"
                href="{{ url('/') }}"
            >
                Continue Shopping
            </x-filament::button>
        </div>


    @endif
</div>
