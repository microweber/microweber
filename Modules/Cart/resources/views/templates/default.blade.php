{{--
Cart Add Module - Default Template
Type: layout
Name: Default
Description: Default cart add template with prices and add to cart button
--}}

<script>


</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        mw.on.moduleReload('cart_fields_{{ $params['id'] ?? '' }}', function () {
            mw.reload_module('#{{ $params['id'] ?? '' }}');
        });
    });
</script>

@if($for_id !== false && $for !== false)
<div class="mw-add-to-cart-holder mw-add-to-cart-{{ $params['id'] ?? '' }}">
    @if($for == 'content' && intval($for_id) == 0)
        @php $for_id = 0; @endphp
    @endif

    @if(is_array($data))
        <input type="hidden" name="for" value="{{ $for }}"/>
        <input type="hidden" name="for_id" value="{{ $for_id }}"/>
    @endif

    @if(empty($data))
        <div class="mw-open-module-settings">
            {{ _e('Click here to edit custom fields', true) }}
        </div>
    @else
        <br class="mw-add-to-cart-spacer"/>

        <module type="custom_fields" data-content-id="{{ intval($for_id) }}" data-skip-type="price" id="cart_fields_{{ $params['id'] ?? '' }}"/>

        <div class="price">
            @php $i = 1; @endphp
            @foreach($data as $key => $v)
                <div class="mw-price-item d-flex align-items-center justify-content-between">
                    @php $keyslug_class = str_slug(strtolower($key)); @endphp

                    <div class="price-holder">
                        <h5 class="mb-0 price">{{ currency_format($v) }}</h5>
                    </div>

                    @if(!$in_stock)
                        <button class="btn btn-secondary float-end" type="button" disabled="disabled"
                                onclick="mw.alert('{{ addslashes(_e("This item is out of stock and cannot be ordered", true)) }}');">
                            <i class="mdi mdi-cart"></i>
                            {{ _e("Out of stock", true) }}
                        </button>
                    @else
                        <button class="btn btn-primary float-end" type="button"
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
@endif
