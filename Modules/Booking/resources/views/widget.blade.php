@php
    /** @var int $productId */
    $bkSetting = \class_exists(\Modules\Booking\Models\BookingProductSetting::class)
        ? \Modules\Booking\Models\BookingProductSetting::active()->where('product_id', $productId)->first()
        : null;
    $bkBuild = function_exists('asset')
        ? asset('vendor/microweber-packages/frontend-assets/build/booking.js')
        : '/vendor/microweber-packages/frontend-assets/build/booking.js';
    $bkCss = function_exists('asset')
        ? asset('vendor/microweber-packages/frontend-assets/build/booking.css')
        : '/vendor/microweber-packages/frontend-assets/build/booking.css';
    // Unit price: per-product override, else the product price.
    $bkUnitPrice = $bkSetting && $bkSetting->unit_price !== null
        ? (float) $bkSetting->unit_price
        : (float) (get_product_prices($productId, true)[0]['value'] ?? 0);
    $bkCurrency = function_exists('currency_get_default') ? (currency_get_default() ?: 'USD') : 'USD';
@endphp

@if($bkSetting)
    <link rel="stylesheet" href="{{ $bkCss }}">
    <div class="mw-booking-widget"
         data-mw-booking
         data-product-id="{{ $productId }}"
         data-booking-type="{{ $bkSetting->booking_type }}"
         data-price-unit="{{ $bkSetting->price_unit }}"
         data-unit-price="{{ $bkUnitPrice }}"
         data-currency="{{ $bkCurrency }}"
         data-slot-minutes="{{ $bkSetting->slot_minutes }}">

        @if($bkSetting->booking_type === 'time_slot')
            <label>{{ _e('Choose a date', true) }}</label>
            <input type="text" class="mw-booking-date" placeholder="{{ _e('Select date', true) }}" readonly>
            <label>{{ _e('Available times', true) }}</label>
            <select class="mw-booking-slot"></select>
        @else
            <label>{{ _e('Check-in — Check-out', true) }}</label>
            <input type="text" class="mw-booking-range" placeholder="{{ _e('Select your dates', true) }}" readonly>
            <div class="mw-booking-row">
                <div>
                    <label>{{ _e('Rooms', true) }}</label>
                    <input type="number" class="mw-booking-units" min="1" value="1">
                </div>
                <div>
                    <label>{{ _e('Guests', true) }}</label>
                    <input type="number" class="mw-booking-guests" min="1" value="2">
                </div>
            </div>
        @endif

        <div class="mw-booking-price"></div>
        <button type="button" class="mw-booking-submit" disabled>{{ _e('Book now', true) }}</button>
        <div class="mw-booking-msg" role="alert"></div>
    </div>
    <script type="module" src="{{ $bkBuild }}"></script>
@endif
