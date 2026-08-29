<div class="order-summary-container">
    <div class="order-summary-rows">
        <div class="order-summary-row">
            <span>{{ __('Subtotal') }}</span>
            <span style="font-weight:500;">{{ currency_format($subtotal) }}</span>
        </div>

        @if($shipping > 0)
            <div class="order-summary-row">
                <span>{{ __('Shipping') }}</span>
                <span style="font-weight:500;">{{ currency_format($shipping) }}</span>
            </div>
        @else
            <div class="order-summary-row">
                <span>{{ __('Shipping') }}</span>
                <span style="font-weight:500; color:#16a34a;">{{ __('Free') }}</span>
            </div>
        @endif

        @php
            $discount = coupon_get_applied() ? cart_get_discount() : 0;
            $tax = cart_get_tax() ?? 0;
        @endphp

        @if($discount > 0)
            <div class="order-summary-row">
                <span>{{ __('Discount') }}</span>
                <span style="font-weight:500; color:#16a34a;">-{{ currency_format($discount) }}</span>
            </div>
        @endif

        @if($tax > 0)
            <div class="order-summary-row">
                <span>{{ __('Tax') }}</span>
                <span style="font-weight:500;">{{ currency_format($tax) }}</span>
            </div>
        @endif

        <div class="order-summary-total">
            <span>{{ __('Total') }}</span>
            <span class="order-summary-total-amount">{{ currency_format($total) }}</span>
        </div>
    </div>
</div>

<style>
    .order-summary-container {
        padding: 1rem;
        background: #f9fafb;
        border-radius: 0.5rem;
    }

    .order-summary-rows {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .order-summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.875rem;
        color: #4b5563;
    }

    .order-summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e5e7eb;
        padding-top: 0.75rem;
        margin-top: 0.75rem;
    }

    .order-summary-total span:first-child {
        font-size: 1.125rem;
        font-weight: 600;
    }

    .order-summary-total-amount {
        font-size: 1.25rem;
        font-weight: 700;
        color: rgb(var(--primary-600, 13 110 253));
    }
</style>
