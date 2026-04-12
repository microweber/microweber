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

                    @if(!$in_stock)
                        <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small float-end"
                                type="button"
                                disabled="disabled"
                                onclick="mw.alert('{{ addslashes(_e("This item is out of stock and cannot be ordered", true)) }}');">
                            <i class="mdi mdi-cart"></i>
                            {{ _e("Out of stock", true) }}
                        </button>
                    @else
                        <button class="mw-ui-btn mw-ui-btn-info mw-ui-btn-outline mw-ui-btn-small float-end"
                                type="button"
                                onclick="mw.cart.add_and_show_modal('{{ $for_id ?? '' }}','{{ $v }}', '{{ $title }}');">
                            <i class="mdi mdi-cart"></i>
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
