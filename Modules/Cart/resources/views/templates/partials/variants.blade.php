{{--
    Product variant selector (admin "Variants" tab / relational combinations).

    Rendered like the custom-fields selector in the cart-add module. Each
    attribute becomes a <select name="mw_variant[<key>]">; shop.js collects
    these named inputs inside .mw-add-to-cart-holder and posts them, and
    CartService resolves the chosen combination (price / sku / stock) and
    records it on the cart line. The data-mw-variants JSON + data-currency-prefix
    let the frontend bundle update the displayed price when a variant changes.
--}}
@if(!empty($variantOptions['attributes'] ?? []))
    <div class="mw-variant-fields mb-2"
         data-mw-variants='{{ json_encode($variantOptions['variants'] ?? [], JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP) }}'
         data-currency-prefix="{{ currency_symbol() ?: '$' }}">
        @foreach($variantOptions['attributes'] as $mwAttr)
            <div class="mw-variant-field mb-2">
                <label class="d-block mb-1 text-muted small">{{ $mwAttr['name'] }}</label>
                <select name="mw_variant[{{ $mwAttr['key'] }}]"
                        class="form-control form-select mw-variant-select"
                        data-attr-key="{{ $mwAttr['key'] }}"
                        aria-label="{{ $mwAttr['name'] }}">
                    @foreach($mwAttr['values'] as $mwVal)
                        <option value="{{ $mwVal['key'] }}">{{ $mwVal['value'] }}</option>
                    @endforeach
                </select>
            </div>
        @endforeach
    </div>
@endif
