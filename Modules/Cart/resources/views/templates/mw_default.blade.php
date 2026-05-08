{{--
Cart Add Module - MW Default Template
Type: layout
Name: MW Default
Description: Styled cart add template with product image and custom fields
--}}

<script>


</script>

<style>
    .mw-add-product-to-cart-default input,
    .mw-add-product-to-cart-default select,
    .mw-add-product-to-cart-default .mw-custom-field-form-controls {
        display: block;
        width: 100% !important;
    }
</style>

@php
    $product = false;
    if (isset($params['content-id'])) {
        $product = get_content_by_id($params["content-id"]);
        if($product){
            $title = $product['title'];
        } else {
            $title = _e("Product", true);
        }
    } else {
        $title = _e("Product", true);
    }

    $title = false;
    if ($product && isset($product['title'])) {
        $title = $product['title'];
    }

    $picture = false;
    if ($product && isset($product['id'])) {
        $picture = get_picture($product['id']);
    }
@endphp

<div style="max-width:400px; margin: 0 auto;" class="mw-add-product-to-cart-default">
    <h3>{{ $title }}</h3>

    @if($picture)
        <img src="{{ $picture }}" alt="{{ $title }}">
    @endif

    <br/>
    <br class="mw-add-to-cart-spacer"/>

    <module type="custom_fields" data-content-id="{{ intval($for_id) }}" data-skip-type="price" input-class="form-select mw-full-width" id="cart_fields_{{ $params['id'] ?? '' }}"/>

    <br/>
    <br/>

    @if(is_array($data))
        <div class="price">
            @php $i = 1; @endphp
            @foreach($data as $key => $v)
                <div class="mw-price-item m-t-10">
                    <span class="mw-price">
                        @if(is_string($key) && trim(strtolower($key)) == 'price')
                            {{ _e($key, true) }}
                        @else
                            {{ $key }}
                        @endif
                        : {{ currency_format($v) }}
                    </span>

                    {{-- audit-test 2026-05-08 PM TASK-018 / TICKET-AQ-residual:
                         migrated from inline onclick= to the delegated
                         mw-add-to-cart-btn / mw-add-to-cart-disabled-btn pattern
                         shipped under TASK-003 (TICKET-AQ). Listener already
                         live at shop.js:342-360 — Blade-only change here.
                         Out-of-stock: aria-disabled (focusable, no fake-disabled
                         click swallow) + data-alert-message.
                         Add-to-cart: class trigger + data-content-id / data-price
                         / data-title. Closes the apostrophe-in-title JS-string
                         break and the strict-CSP script-src 'self' blocker. --}}
                    @if(!$in_stock)
                        <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small float-end mw-add-to-cart-disabled-btn"
                                type="button"
                                aria-disabled="true"
                                aria-label="{{ _e('Out of stock', true) }}: {{ $title }}"
                                data-alert-message="{{ _e('This item is out of stock and cannot be ordered', true) }}">
                            <i class="mdi mdi-cart" aria-hidden="true"></i>
                            {{ _e("Out of stock", true) }}
                        </button>
                    @else
                        <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small float-end mw-add-to-cart-btn"
                                type="button"
                                aria-label="{{ _e($button_text !== false ? $button_text : 'Add to cart', true) }}: {{ $title }}"
                                data-content-id="{{ $for_id ?? '' }}"
                                data-price="{{ $v }}"
                                data-title="{{ $title }}">
                            <i class="mdi mdi-cart" aria-hidden="true"></i>
                            {{ _e($button_text !== false ? $button_text : "Add to cart", true) }}
                        </button>
                    @endif
                </div>

                @if($i > 1)
                    <br/>
                @endif
                @php $i++; @endphp
            @endforeach
        </div>
    @endif
</div>
