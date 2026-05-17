<div class="gap-y-4">

    @if(isset($cartItems) and $cartItems)
        @foreach($cartItems as $item)
            <div class="flex items-center bg-white dark:bg-gray-800 p-4 rounded-lg shadow transition-colors gap-x-4">
                <div>
                    <a href="{{ $item['url'] ?? '#' }}" target="_blank" rel="noopener noreferrer">
                        @if(isset($item['picture']) and $item['picture'])
                            <img src="{{ $item['picture'] }}" alt="{{ $item['title'] }}"
                                 class="w-20 h-20 object-cover rounded border border-gray-200 dark:border-gray-700 shadow-sm">
                        @else
                            @svg('heroicon-o-cube', 'w-20 h-20 text-gray-300 dark:text-gray-600')
                        @endif
                    </a>
                </div>

                <div class="flex-1 min-w-0">
                    <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate p-2">{{ $item['title'] }}</h3>
                </div>

                <div class="w-24 text-right">
                    <p class="text-sm  p-2">{{ format_currency($item['price']) }} </p>


                </div>

                <div class="w-24">
                    <input
                        type="number"
                        min="1"
                        wire:model.live.debounce.1500ms="cartItems.{{ $loop->index }}.qty"
                        wire:input.debounce.300ms="updateQuantity('{{ $item['id'] }}', $event.target.value)"
                        value="{{ $item['qty'] }}"
                        class="block w-20 rounded-md border border-gray-300 dark:border-gray-700 py-1.5 text-gray-900 dark:text-gray-100 bg-white dark:bg-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 dark:placeholder:text-gray-500 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 transition-colors"
                    >
                </div>

                <div>
                    <button
                        wire:click="removeItem('{{ $item['id'] }}')"
                        type="button"
                        class="rounded-md bg-red-600 dark:bg-red-700 p-2 text-white shadow-sm hover:bg-red-500 dark:hover:bg-red-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600 dark:focus-visible:outline-red-700 transition-colors"
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
            <div class="mt-8 bg-white dark:bg-gray-800 p-4 rounded-lg shadow transition-colors">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Cart Totals</h3>
                <div class="gap-y-2">
                    {{-- cart_manager->totals() returns a mixed shape: `subtotal` and `total` are
                         arrays with [label, value, amount]; `shipping`, `tax`, `discount` are
                         empty strings when not applicable. Filter to arrays before indexing. --}}
                    @foreach($cartTotals as $key => $total)
                        @if(!is_array($total))
                            @continue
                        @endif
                        <div
                            class="flex justify-between {{ $key === 'total' ? 'pt-4 border-t border-gray-200 dark:border-gray-700' : '' }}">
                        <span
                            class="{{ $key === 'total' ? 'text-lg font-medium text-gray-900 dark:text-gray-100' : 'text-gray-600 dark:text-gray-300' }}">
                            {{ $total['label'] }}:
                        </span>
                            <span
                                class="{{ $key === 'total' ? 'text-lg font-bold text-gray-900 dark:text-gray-100' : 'font-medium dark:text-gray-200' }}">
                            {{ $total['amount'] }}
                        </span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @else
        {{-- task-2026-05-17-0e6cfa / AI-796 Slice C — empty-state copy
             pass + Slice B single-CTA shape. Replaces the prior
             "You have no items in your cart." line + dark-indigo
             Filament button (Filament's <x-filament::button> isn't
             reliably loaded on every public template; Big2 re-skinned
             it salmon orange). New shape: heading + body + ONE CTA
             — matches the project-wide empty-state ONE-CTA rule
             (AI-759 cross-surface pattern, 4th instance: Live-edit
             Create, Orders, Comments, /cart). Bare <a> styled by
             the parent .mw-cart-standalone-page CSS at brand-blue
             #0d6efd (matches .mw-table-empty-cta in
             microweber-filament-theme.css). --}}
        <div class="mw-cart-empty">
            <h2 class="mw-cart-empty__heading">{{ __('Your cart is empty') }}</h2>
            {{-- Use Microweber's _e() with $to_return=true; Laravel's __() helper treats trailing periods as namespace separators and silently returns empty for namespaced keys with no matching translation file. --}}
            <p class="mw-cart-empty__body">{{ _e('Browse our products and add items.', true) }}</p>
            <div class="mw-cart-empty__actions">
                <a href="{{ url('/') }}" class="mw-cart-empty-cta" aria-label="{{ __('Continue shopping') }}">
                    {{ __('Continue shopping') }}
                </a>
            </div>
        </div>

    @endif
</div>
