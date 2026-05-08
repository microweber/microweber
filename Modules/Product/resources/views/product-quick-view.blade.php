<div class="row">

    <div class="col-md-6">
        <img src="{{$product->thumbnail(800,800, true)}}" alt="{{ $product->title ?? '' }}" />
    </div>
    <div class="col-md-6">

        <a href="{{content_link($product->id)}}">
            <div class="title pb-3">{{$product->title}}</div>
        </a>

        <div class="row justify-content-center">
            <div class="col-6 col-md-12 price-holder">
                <p>
                    <span class="price"><?php print currency_format($product->price); ?></span>
                </p>
            </div>

            {{-- audit-test 2026-05-08 PM TASK-018 / TICKET-AQ-residual
                 (extra-sweep): <a href="javascript:;" onclick=...> ->
                 <button> with mw-add-to-cart-btn + data-attrs. --}}
            <div class="d-flex justify-content-end">
                <?php if ($product->inStock == true): ?>
                <button type="button"
                        class="btn btn-outline-primary mw-add-to-cart-btn"
                        aria-label="<?php _lang('Add to cart', 'templates/shopmag') ?>: {{ $product->title }}"
                        data-content-id="{{ $product->id }}"
                        data-price="{{ $product->price }}"
                        data-title="{{ $product->title }}"><i class="mdi mdi-cart" aria-hidden="true"></i> <?php _lang("Add to cart", 'templates/shopmag') ?></button>
                <?php else: ?>
                <span class="text-danger p-1"><i class="material-icons" style="font-size: 18px;" aria-hidden="true">remove_shopping_cart</i> <?php _lang("Out of Stock", 'templates/shopmag') ?></span>
                <?php endif; ?>
            </div>
        </div>

    </div>


    <input type="hidden" name="content_id" value="{{$product->id}}"/>

</div>
