<div class="shop-products">

    <div class="product position-relative">
        {{-- AI-265 (task-2026-05-13-de78ce) — product card image bounded
             optimization slice. Migrated from a hand-rolled <img> with only
             `src` + lazy/async (no srcset, no placeholder) to the
             responsive_thumbnail() helper which centralises srcset/sizes/
             alt/loading/decoding emission per ADR-0001 helper-layer
             principle. Eager-first-N defaults to 2 so above-the-fold
             cards paint as part of LCP; remaining cards stay lazy.
             A `background-color` placeholder on the wrapper reserves
             layout space (no CLS) and replaces the white flash while
             the image network request resolves. The full LQIP/blur-up
             + WebP variant pipeline + 20%-LCP test remain deferred to
             AI-265 follow-up tickets when the WebP variant generator
             lands in MediaManager.

             History — audit-test 2026-05-08 PM TASK-017 / TICKET-AB:
             original migration was from `<div style="background-image: url(...)">`
             to a real `<img>`; same Option-B layout (object-fit:cover within
             position:relative wrapper, aspect-ratio 1/1, thumbnail dropped
             1000x1000 → 800x600 for ~36% bandwidth saving). --}}
        <a class="text-decoration-none" href="{{content_link($product->id)}}">
            <div class="background-image-holder position-relative mw-product-card-image-placeholder"
                 style="aspect-ratio: 1 / 1; overflow: hidden;">
                {!! responsive_thumbnail($product->mediaUrl(), 800, 600, [
                    'alt' => $product->title,
                    'class' => 'position-absolute top-0 start-0 w-100 h-100',
                    'style' => 'object-fit: cover;',
                    'sizes' => '(max-width: 575.98px) 100vw, (max-width: 991.98px) 50vw, 33vw',
                ]) !!}

                <div @if($product->getContentDataByFieldName('label-color'))
                         style="background-color: {{$product->getContentDataByFieldName('label-color')}} "
                    @endif >
                    @if($product->getContentDataByFieldName('label-type') == 'percent')
                        <div class="discount-label">
                                                    <span class="discount-percentage">
                                                          {{$product->getDiscountPercentage()}} %
                                                    </span>
                            <span class="discount-label-text"><?php _lang("Discount"); ?></span>
                        </div>

                    @endif
                    @if($product->getContentDataByFieldName('label-type') == 'text' and $product->getContentDataByFieldName('label'))

                        <div class="position-absolute  top-0 left-0 m-2" style="z-index: 3;">
                            <div class="badge text-white px-3 pb-1 pt-2 rounded-0" style="background-color: {{$product->getContentDataByFieldName('label-color')}};">{{$product->getContentDataByFieldName('label')}}</div>
                        </div>
                    @endif
                </div>

            </div>
            <h4 class="mt-3">{{$product->title}}</h4>
        </a>

        <p>{{ Str::limit($product->description, 150) }}</p>

        {{-- task-2026-05-22-c58ec3 / AI-906 — wire show_price setting. --}}
        @if($showPrice ?? true)
        <div class="d-flex items-center text-center align-items-center price-holder">

            @if($product->hasSpecialPrice())
                <p class="price-old mb-0 fs-3"><?php print currency_format($product->price); ?></p>
                <p class="price mb-0 fs-3"><?php print currency_format($product->specialPrice); ?></p>
            @else
                <p class="price mb-0 fs-3"><?php print currency_format($product->price); ?></p>
            @endif

        </div>
        @endif

        {{-- audit-test 2026-05-08 PM TASK-017 / TICKET-AB finding #10:
             prior `<a href="?tags[]={{ $tag->slug }}">` wiped all existing query
             params (sort, page, search, price-range). wire:click="filterTag(...)"
             routes through ShopComponent's ShopTagsTrait::filterTag which
             merges the new tag into $this->tags + resets page to 1, preserving
             every other filter. Same pattern as filters/tags/tag-button.blade.php. --}}
        @foreach($product->tags as $tag)
            <span class="badge badge-lg">
                <button type="button"
                        class="btn btn-link p-0 align-baseline text-decoration-none"
                        wire:click="filterTag('{{ $tag->slug }}')">{{ $tag->name }}</button>
            </span>
        @endforeach

        {{-- task-2026-05-17-046a37 / AI-861 — storefront add-to-cart CTA.
             Pre-fix the product card carried only title + price + tags — NO buy
             affordance anywhere on /shop, leaving the commerce funnel at 0% even
             though products themselves rendered. Each card now carries an
             explicit `mw-add-to-cart-btn` button (the canonical class consumed
             by the shop.js delegated click handler used by the
             <module type="shop/cart_add"/> templates at
             Modules/Cart/.../default.blade.php + .../shop_inner.blade.php).
             data-content-id + data-price + data-title match the shape those
             templates emit so the same JS handler fires the cart-add
             transaction whether the click originates from the shop grid OR
             the product-detail page.

             For variant products (multiple prices configured via custom_fields),
             this card-level button performs the SIMPLE-add (default price);
             the product-detail page still hosts the full variant-picker UI via
             <module type="shop/cart_add"/>. Title/image anchor click navigation
             stays intact — the button is below the price-holder and does not
             capture the card-wide link region. --}}
        {{-- task-2026-05-22-c58ec3 / AI-906 — wire show_add_to_cart setting. --}}
        @if($showAddToCart ?? true)
        <button type="button"
                class="btn btn-primary mt-3 mw-add-to-cart-btn"
                data-content-id="{{ $product->id }}"
                data-price="{{ $product->price }}"
                data-title="{{ $product->title }}"
                aria-label="{{ _e('Add to cart', true) }}: {{ $product->title }}">
            <i class="mdi mdi-cart" aria-hidden="true"></i>
            {{ _e('Add to cart', true) }}
        </button>
        @endif
    </div>
</div>
