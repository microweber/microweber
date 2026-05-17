{{--
Cart Add Module - Shop Inner Template
Type: layout
Name: Shop Inner
Description: Template designed for use within shop product layouts
--}}

<script>


</script>

<div class="product-info-layout-1">
    @if($for_id !== false && $for !== false)
        <div class="mw-add-to-cart-holder mw-add-to-cart-{{ $params['id'] ?? '' }}">
            @if(is_array($data))
                <input type="hidden" name="for" value="{{ $for }}"/>
                <input type="hidden" name="for_id" value="{{ $for_id }}"/>
            @endif

            @if(empty($data))
                {{-- task-2026-05-17-046a37 / AI-861 — public surface MUST carry
                     a buy CTA even when no prices are configured. Same fix shape
                     as Modules/Cart/resources/views/templates/default.blade.php
                     — gate the admin editor prompt behind is_admin() + render a
                     fallback `mw-add-to-cart-btn` for public users anchored at
                     the product's base price column (or 0 for misconfigured
                     products). --}}
                @if(is_admin())
                    <div class="mw-open-module-settings">
                        {{ _e('Click here to edit custom fields', true) }}
                    </div>
                @else
                    @php
                        $mwAi861Cd = content_data($for_id);
                        $mwAi861FallbackPrice = isset($mwAi861Cd['price']) ? (float) $mwAi861Cd['price'] : 0;
                    @endphp
                    <div class="mw-price-item">
                        <div class="price-display mb-3">
                            <strong class="price-value">{{ currency_format($mwAi861FallbackPrice) }}</strong>
                        </div>
                        <div class="add-to-cart-actions d-flex align-items-center">
                            @if(!$in_stock)
                                <button class="btn-default button-add-to-cart mw-add-to-cart-disabled-btn" type="button"
                                        aria-disabled="true"
                                        aria-label="{{ _e('Out of stock', true) }}: {{ $title }}"
                                        data-alert-message="{{ _e('This item is out of stock and cannot be ordered', true) }}">
                                    <i class="mdi mdi-cart" aria-hidden="true"></i>
                                    {{ _e('Out of stock', true) }}
                                </button>
                            @else
                                <button class="btn-default button-add-to-cart mw-add-to-cart-btn" type="button"
                                        aria-label="{{ _e($button_text !== false ? $button_text : 'Add to cart', true) }}: {{ $title }}"
                                        data-content-id="{{ $for_id ?? '' }}"
                                        data-price="{{ $mwAi861FallbackPrice }}"
                                        data-title="{{ $title }}">
                                    <i class="mdi mdi-cart" aria-hidden="true"></i>
                                    {{ _e($button_text !== false ? $button_text : 'Add to cart', true) }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            @else
                <module type="custom_fields" data-content-id="{{ intval($for_id) }}" data-skip-type="price" id="cart_fields_{{ $params['id'] ?? '' }}"/>

                @if(is_array($data))
                    <div class="price">
                        @foreach($data as $key => $v)
                            <div class="mw-price-item">
                                <div class="price-display mb-3">
                                    <strong class="price-value">{{ currency_format($v) }}</strong>
                                    @if(is_string($key) && trim(strtolower($key)) !== 'price')
                                        <span class="price-label">{{ $key }}</span>
                                    @endif
                                </div>

                                <div class="add-to-cart-actions d-flex align-items-center">
                                    <div class="mw-qty-field">
                                        <button type="button" onclick="mw.tools.decrease_quantity(this);">
                                            <i>−</i>
                                        </button>
                                        <input type="number" name="qty" value="1" min="1"/>
                                        <button type="button" onclick="mw.tools.increase_quantity(this);">
                                            <i>+</i>
                                        </button>
                                    </div>

                                    {{-- audit-test 2026-05-08 PM TASK-018 / TICKET-AQ-residual
                                         (extra-sweep): inline onclick= -> delegated
                                         mw-add-to-cart-btn / mw-add-to-cart-disabled-btn
                                         pattern. --}}
                                    @if(!$in_stock)
                                        <button class="btn-default button-add-to-cart mw-add-to-cart-disabled-btn"
                                                type="button"
                                                aria-disabled="true"
                                                aria-label="{{ _e('Out of stock', true) }}: {{ $title }}"
                                                data-alert-message="{{ _e('This item is out of stock and cannot be ordered', true) }}">
                                            <i class="icon-shopping-cart" aria-hidden="true"></i>
                                            {{ _e("Out of stock", true) }}
                                        </button>
                                    @else
                                        <button class="btn-default button-add-to-cart mw-add-to-cart-btn"
                                                type="button"
                                                aria-label="{{ _e($button_text !== false ? $button_text : 'Add to cart', true) }}: {{ $title }}"
                                                data-content-id="{{ $for_id ?? '' }}"
                                                data-price="{{ $v }}"
                                                data-title="{{ $title }}">
                                            <i class="icon-shopping-cart" aria-hidden="true"></i>
                                            {{ _e($button_text !== false ? $button_text : "Add to cart", true) }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>
    @endif
</div>
