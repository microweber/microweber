<div class="review-order-container">
    {{-- Contact Information --}}
    <div class="review-section">
        <div class="review-section-header">
            <h4>{{ __('Contact Information') }}</h4>
            <button
                type="button"
                wire:click="$dispatch('wizard::goToStep', { step: 2 })"
                class="review-edit-btn"
            >
                {{ __('Edit') }}
            </button>
        </div>
        <div class="review-section-body">
            <p>{{ $data['first_name'] ?? '' }} {{ $data['last_name'] ?? '' }}</p>
            <p>{{ $data['email'] ?? '' }}</p>
            <p>{{ $data['phone'] ?? '' }}</p>
        </div>
    </div>

    {{-- Shipping Address --}}
    <div class="review-section">
        <div class="review-section-header">
            <h4>{{ __('Shipping Address') }}</h4>
            <button
                type="button"
                wire:click="$dispatch('wizard::goToStep', { step: 2 })"
                class="review-edit-btn"
            >
                {{ __('Edit') }}
            </button>
        </div>
        <div class="review-section-body">
            <p>{{ $data['address'] ?? '' }}</p>
            <p>{{ $data['city'] ?? '' }}, {{ $data['state'] ?? '' }} {{ $data['postal_code'] ?? '' }}</p>
            <p>{{ app()->country_manager->getCountryName($data['country'] ?? '') }}</p>
        </div>
    </div>

    {{-- Shipping Method --}}
    <div class="review-section">
        <div class="review-section-header">
            <h4>{{ __('Shipping Method') }}</h4>
            <button
                type="button"
                wire:click="$dispatch('wizard::goToStep', { step: 3 })"
                class="review-edit-btn"
            >
                {{ __('Edit') }}
            </button>
        </div>
        <div class="review-section-body">
            @php
                $shippingProviderId = $data['shipping_provider_id'] ?? null;
                $shippingMethod = $shippingProviderId ? app()->shipping_method_manager->getProviderById($shippingProviderId) : null;
                $shippingCost = $shippingProviderId ? app()->shipping_method_manager->getShippingCost($shippingProviderId, []) : 0;
            @endphp
            <p>{{ $shippingMethod['name'] ?? ucfirst($shippingMethod['provider'] ?? __('Standard Shipping')) }}</p>
            <p style="font-weight:500;">
                @if($shippingCost > 0)
                    {{ currency_format($shippingCost) }}
                @else
                    <span style="color:#16a34a;">{{ __('Free') }}</span>
                @endif
            </p>
        </div>
    </div>

    {{-- Payment Method --}}
    <div class="review-section">
        <div class="review-section-header">
            <h4>{{ __('Payment Method') }}</h4>
            <button
                type="button"
                wire:click="$dispatch('wizard::goToStep', { step: 4 })"
                class="review-edit-btn"
            >
                {{ __('Edit') }}
            </button>
        </div>
        <div class="review-section-body">
            @php
                $paymentProviderId = $data['payment_provider_id'] ?? null;
                $paymentMethod = $paymentProviderId ? app()->payment_method_manager->getProviderById($paymentProviderId) : null;
            @endphp
            <p>{{ $paymentMethod['name'] ?? ucfirst($paymentMethod['provider'] ?? __('Standard Payment')) }}</p>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="review-section">
        <h4 style="margin-bottom:0.75rem;">{{ __('Order Items') }}</h4>
        <div class="review-items-list">
            @foreach($cartItems as $item)
                <div class="review-item">
                    @if(!empty($item['item_image']))
                        <img
                            src="{{ $item['item_image'] }}"
                            alt="{{ $item['title'] ?? '' }}"
                            class="review-item-image"
                        />
                    @endif
                    <div class="review-item-info">
                        <p class="review-item-title">{{ $item['title'] ?? '' }}</p>
                        <p class="review-item-qty">{{ __('Qty') }}: {{ $item['qty'] ?? 1 }}</p>
                    </div>
                    <div class="review-item-price">
                        {{ currency_format(($item['price'] ?? 0) * ($item['qty'] ?? 1)) }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Final Summary --}}
    <div class="review-summary">
        @php
            $subtotal = $cartTotal;
            $shippingId = $data['shipping_provider_id'] ?? null;
            $shipping = $shippingId ? app()->shipping_method_manager->getShippingCost($shippingId, []) : 0;
            $discount = coupon_get_applied() ? cart_get_discount() : 0;
            $tax = cart_get_tax() ?? 0;
            $total = $subtotal + $shipping - $discount + $tax;
        @endphp

        <div class="review-summary-row">
            <span>{{ __('Subtotal') }}</span>
            <span>{{ currency_format($subtotal) }}</span>
        </div>

        <div class="review-summary-row">
            <span>{{ __('Shipping') }}</span>
            <span>{{ $shipping > 0 ? currency_format($shipping) : __('Free') }}</span>
        </div>

        @if($discount > 0)
            <div class="review-summary-row">
                <span>{{ __('Discount') }}</span>
                <span style="color:#16a34a;">-{{ currency_format($discount) }}</span>
            </div>
        @endif

        @if($tax > 0)
            <div class="review-summary-row">
                <span>{{ __('Tax') }}</span>
                <span>{{ currency_format($tax) }}</span>
            </div>
        @endif

        <div class="review-summary-total">
            <span>{{ __('Order Total') }}</span>
            <span class="review-total-amount">{{ currency_format($total) }}</span>
        </div>
    </div>
</div>

<style>
    .review-order-container {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        max-width: 100%;
    }

    .review-section {
        background: #f9fafb;
        border-radius: 0.5rem;
        padding: 1rem;
    }

    .review-section h4 {
        font-weight: 500;
        color: #111827;
        margin: 0;
        font-size: 0.95rem;
    }

    .review-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .review-edit-btn {
        font-size: 0.875rem;
        color: rgb(var(--primary-600, 13 110 253));
        background: none;
        border: none;
        cursor: pointer;
        padding: 0;
    }

    .review-edit-btn:hover {
        text-decoration: underline;
    }

    .review-section-body {
        font-size: 0.875rem;
        color: #4b5563;
    }

    .review-section-body p {
        margin: 0.125rem 0;
    }

    .review-items-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .review-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #e5e7eb;
    }

    .review-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .review-item-image {
        width: 3rem;
        height: 3rem;
        object-fit: cover;
        border-radius: 0.375rem;
    }

    .review-item-info {
        flex-grow: 1;
    }

    .review-item-title {
        font-size: 0.875rem;
        font-weight: 500;
        color: #111827;
        margin: 0;
    }

    .review-item-qty {
        font-size: 0.75rem;
        color: #6b7280;
        margin: 0.125rem 0 0;
    }

    .review-item-price {
        font-size: 0.875rem;
        font-weight: 500;
        color: #111827;
    }

    .review-summary {
        background: rgba(59, 130, 246, 0.05);
        border-radius: 0.5rem;
        padding: 1rem;
        border: 1px solid rgba(59, 130, 246, 0.2);
    }

    .review-summary-row {
        display: flex;
        justify-content: space-between;
        font-size: 0.875rem;
        color: #4b5563;
        margin-bottom: 0.5rem;
    }

    .review-summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e5e7eb;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
    }

    .review-summary-total span:first-child {
        font-size: 1.125rem;
        font-weight: 600;
    }

    .review-total-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: rgb(var(--primary-600, 13 110 253));
    }
</style>
