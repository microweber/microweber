{{--
Cart Add Module - Bootstrap Template
Type: layout
Name: Bootstrap
Description: Bootstrap-styled cart add template with modern design
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
<div class="mw-add-to-cart-holder mw-add-to-cart-{{ $params['id'] ?? '' }} card">
    @if($for == 'content' && intval($for_id) == 0)
        @php $for_id = 0; @endphp
    @endif

    @if(is_array($data))
        <input type="hidden" name="for" value="{{ $for }}"/>
        <input type="hidden" name="for_id" value="{{ $for_id }}"/>
    @endif

    @if(empty($data))
        <div class="card-body">
            <div class="alert alert-info mw-open-module-settings">
                <i class="fa fa-info-circle me-2"></i>
                {{ _e('Click here to edit custom fields', true) }}
            </div>
        </div>
    @else
        <div class="card-body">
            <module type="custom_fields" data-content-id="{{ intval($for_id) }}" data-skip-type="price" id="cart_fields_{{ $params['id'] ?? '' }}"/>

            @if(is_array($data))
                @php $i = 1; @endphp
                @foreach($data as $key => $v)
                    <div class="mw-price-item border-bottom pb-3 mb-3">
                        @php $keyslug_class = str_slug(strtolower($key)); @endphp

                        <div class="d-flex justify-content-between align-items-center">
                            <div class="price-info">
                                @if(is_string($key) && trim(strtolower($key)) !== 'price')
                                    <small class="text-muted d-block">{{ $key }}</small>
                                @endif
                                <h4 class="price-value text-primary mb-0">{{ currency_format($v) }}</h4>
                            </div>

                            {{-- audit-test 2026-05-08 PM TASK-018 / TICKET-AQ-residual
                                 (extra-sweep): the add-to-cart inline onclick= and the
                                 out-of-stock inline onclick= were the in-scope migrations.
                                 The qty +/- handlers (mw.tools.decrease_quantity /
                                 mw.tools.increase_quantity, lines 67 + 71) are kept
                                 as-is because they are out-of-scope for TICKET-AQ
                                 (different delegated handler, different ticket family
                                 if/when CSP rolls out). --}}
                            <div class="cart-actions">
                                @if(!$in_stock)
                                    <button class="btn btn-outline-secondary mw-add-to-cart-disabled-btn" type="button"
                                            aria-disabled="true"
                                            aria-label="{{ _e('Out of stock', true) }}: {{ $title }}"
                                            data-alert-message="{{ _e('This item is out of stock and cannot be ordered', true) }}">
                                        <i class="fas fa-times me-2" aria-hidden="true"></i>
                                        {{ _e("Out of stock", true) }}
                                    </button>
                                @else
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="quantity-selector btn-group" role="group">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="mw.tools.decrease_quantity(this);">
                                                <i class="fas fa-minus"></i>
                                            </button>
                                            <input type="number" name="qty" value="1" min="1" class="form-control form-control-sm text-center" style="width: 60px;">
                                            <button class="btn btn-outline-secondary btn-sm" type="button" onclick="mw.tools.increase_quantity(this);">
                                                <i class="fas fa-plus"></i>
                                            </button>
                                        </div>

                                        <button class="btn btn-primary mw-add-to-cart-btn" type="button"
                                                aria-label="{{ _e($button_text !== false ? $button_text : 'Add to cart', true) }}: {{ $title }}"
                                                data-content-id="{{ $for_id ?? '' }}"
                                                data-price="{{ $v }}"
                                                data-title="{{ $title }}">
                                            <i class="fas fa-shopping-cart me-2" aria-hidden="true"></i>
                                            {{ _e($button_text !== false ? $button_text : "Add to cart", true) }}
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @php $i++; @endphp
                @endforeach
            @endif
        </div>
    @endif
</div>
@endif
