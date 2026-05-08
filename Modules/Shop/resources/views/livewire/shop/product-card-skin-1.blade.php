<div class="mw-online-shop-skin-1-product position-relative">
    {{-- audit-test 2026-05-08 PM TASK-017 / TICKET-AB:
         Same Option-B migration as Shop/product-card.blade.php — bg-image div
         replaced with a real <img> inside a position:relative wrapper.
         Badge/discount/overlay children stay on top via z-index without
         restructuring. See product-card.blade.php for the full rationale. --}}
    <a class="text-decoration-none" href="{{content_link($product->id)}}">
        <div class="background-image-holder position-relative" style="aspect-ratio: 1 / 1; overflow: hidden;">
            <img src="{{ $product->thumbnail(800, 600) }}"
                 alt="{{ $product->title }}"
                 loading="lazy"
                 decoding="async"
                 class="position-absolute top-0 start-0 w-100 h-100"
                 style="object-fit: cover;">

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
</div>
