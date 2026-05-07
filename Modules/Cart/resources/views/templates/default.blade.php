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

                    {{-- audit-test 2026-05-07 Cart Option B (TICKET-AQ shipped, supersedes
                         cycle-36 finding #11 a11y patch + cycle-40 addslashes hotfix):
                         Replaced inline `onclick=...` with data-attributes + a
                         delegated click listener registered in shop.js. Closes:
                         (a) the strict-CSP `script-src 'self'` blocker (no inline JS)
                         (b) the `O'Brien` apostrophe break (data-attrs are HTML-attr
                             context all the way through; dataset.X returns the decoded
                             string with no JS-string sub-context to break out of)
                         (c) cycle-36 finding #12 — out-of-stock button used HTML
                             `disabled="disabled"` AND inline `onclick`; disabled
                             buttons don't fire click in any browser, so the alert
                             was unreachable. Replaced with `aria-disabled="true"`
                             so the button stays focusable and the delegated listener
                             can show the alert. --}}
                    @if(!$in_stock)
                        <button class="btn btn-secondary float-end mw-add-to-cart-disabled-btn" type="button"
                                aria-disabled="true"
                                aria-label="{{ _e('Out of stock', true) }}: {{ $title }}"
                                data-alert-message="{{ _e('This item is out of stock and cannot be ordered', true) }}">
                            <i class="mdi mdi-cart" aria-hidden="true"></i>
                            {{ _e("Out of stock", true) }}
                        </button>
                    @else
                        <button class="btn btn-primary float-end mw-add-to-cart-btn" type="button"
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
@endif
