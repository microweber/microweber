<div class="mw-online-shop-skin-1-product position-relative">
    {{-- AI-265 (task-2026-05-13-de78ce) — skin-1 product card image
         bounded optimization slice. Same migration as product-card.blade.php:
         hand-rolled <img> → responsive_thumbnail() (gains srcset/sizes
         centralised in the helper) + wrapper placeholder background-color
         (no-CLS layout reservation, no white flash). See product-card.blade.php
         for the full rationale. WebP variants + LQIP blur deferred to
         AI-265 follow-ups. --}}
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

    <div class="d-flex items-center text-center align-items-center price-holder">

        @if($product->hasSpecialPrice())
            <p class="price-old mb-0 fs-3"><?php print currency_format($product->price); ?></p>
            <p class="price mb-0 fs-3"><?php print currency_format($product->specialPrice); ?></p>
        @else
            <p class="price mb-0 fs-3"><?php print currency_format($product->price); ?></p>
        @endif

    </div>

    {{-- audit-test 2026-05-08 PM TASK-017 / TICKET-AB finding #10:
         tag-chip switched from `<a href="?tags[]=">` (which wiped existing
         query params) to wire:click="filterTag" via ShopTagsTrait — see
         product-card.blade.php for the full note. --}}
    @foreach($product->tags as $tag)
        <span class="badge badge-lg">
            <button type="button"
                    class="btn btn-link p-0 align-baseline text-decoration-none"
                    wire:click="filterTag('{{ $tag->slug }}')">{{ $tag->name }}</button>
        </span>
    @endforeach

    {{-- task-2026-05-17-046a37 / AI-861 — storefront add-to-cart CTA.
         Sibling fix to product-card.blade.php — skin-1 carried the same
         no-buy-affordance gap. See product-card.blade.php docblock for
         the full rationale (mw-add-to-cart-btn = canonical shop.js click
         handler hook; data-content-id + data-price + data-title shape
         mirrors the Cart-module templates that own the variant-picker
         flow on the product-detail page). --}}
    <button type="button"
            class="btn btn-primary mt-3 mw-add-to-cart-btn"
            data-content-id="{{ $product->id }}"
            data-price="{{ $product->price }}"
            data-title="{{ $product->title }}"
            aria-label="{{ _e('Add to cart', true) }}: {{ $product->title }}">
        <i class="mdi mdi-cart" aria-hidden="true"></i>
        {{ _e('Add to cart', true) }}
    </button>
</div>
