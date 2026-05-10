<div class="cart-items-container">
    @if(empty($cartItems))
        <div style="text-align:center; padding:2rem 0;">
            <x-heroicon-o-shopping-cart class="w-16 h-16 mx-auto text-gray-300 mb-4" />
            <p style="color:#6b7280;">{{ __('Your cart is empty') }}</p>
            <a href="{{ site_url() }}" class="btn btn-primary" style="margin-top:1rem; display:inline-block;">
                {{ __('Continue Shopping') }}
            </a>
        </div>
    @else
        <div class="checkout-cart-items">
            @foreach($cartItems as $item)
                <div class="checkout-cart-item">
                    @if(!empty($item['item_image']))
                        <div class="checkout-cart-item-image">
                            <img
                                src="{{ $item['item_image'] }}"
                                alt="{{ $item['title'] ?? '' }}"
                            />
                        </div>
                    @endif

                    <div class="checkout-cart-item-details">
                        <h4 class="checkout-cart-item-title">
                            {{ $item['title'] ?? '' }}
                        </h4>

                        @if(!empty($item['custom_fields']))
                            <p class="checkout-cart-item-fields">
                                {{ $item['custom_fields'] }}
                            </p>
                        @endif

                        <div class="checkout-cart-item-row">
                            <span class="checkout-cart-item-qty">
                                {{ __('Qty') }}: {{ $item['qty'] ?? 1 }}
                            </span>
                            <span class="checkout-cart-item-price">
                                {{ currency_format(($item['price'] ?? 0) * ($item['qty'] ?? 1)) }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="checkout-cart-subtotal">
            <span class="checkout-cart-subtotal-label">{{ __('Subtotal') }}</span>
            <span class="checkout-cart-subtotal-value">{{ currency_format($cartTotal) }}</span>
        </div>

        <div class="checkout-cart-add-more">
            <a href="{{ site_url() }}">
                {{ __('Add more items') }}
            </a>
        </div>
    @endif
</div>

<style>
    .cart-items-container {
        min-height: 200px;
    }

    .checkout-cart-items {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .checkout-cart-item {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem;
        background: #fff;
        border-radius: 0.5rem;
        border: 1px solid #e5e7eb;
    }

    .checkout-cart-item-image {
        flex-shrink: 0;
    }

    .checkout-cart-item-image img {
        width: 5rem;
        height: 5rem;
        object-fit: cover;
        border-radius: 0.375rem;
    }

    .checkout-cart-item-details {
        flex-grow: 1;
        min-width: 0;
    }

    .checkout-cart-item-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: #111827;
        margin: 0;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .checkout-cart-item-fields {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0.25rem 0 0;
    }

    .checkout-cart-item-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-top: 0.5rem;
    }

    .checkout-cart-item-qty {
        font-size: 0.875rem;
        color: #4b5563;
    }

    .checkout-cart-item-price {
        font-size: 0.875rem;
        font-weight: 500;
        color: #111827;
    }

    .checkout-cart-subtotal {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 1.5rem;
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.5rem;
    }

    .checkout-cart-subtotal-label {
        font-size: 1rem;
        font-weight: 500;
        color: #111827;
    }

    .checkout-cart-subtotal-value {
        font-size: 1.25rem;
        font-weight: 700;
        color: #111827;
    }

    .checkout-cart-add-more {
        margin-top: 1rem;
        text-align: center;
    }

    .checkout-cart-add-more a {
        font-size: 0.875rem;
        color: var(--primary-600, #2563eb);
        text-decoration: none;
        /* Cycle-161 (2026-05-10): "Add more items" link measured 98x16
           in the agent-test mobile audit — below WCAG 2.5.5 / iOS HIG
           44x44 floor. Inline-flex + min-height keeps the visual link
           text the same size while expanding the tap target. padding
           gives extra horizontal room without disturbing the inline
           layout. */
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 44px;
        min-width: 44px;
        padding: 6px 12px;
    }

    .checkout-cart-add-more a:hover {
        text-decoration: underline;
    }
</style>
