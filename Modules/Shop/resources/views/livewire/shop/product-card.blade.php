<div class="shop-products">

    <div class="product position-relative">
        {{-- audit-test 2026-05-08 PM TASK-017 / TICKET-AB:
             Migrated from `<div style="background-image: url('{{ safe_css_url(...) }}')">`
             to a real `<img>` inside a position:relative wrapper. The wrapper is
             absolute-positioned so badge/discount/overlay children stay on top
             via z-index without restructuring the card layout.
             - object-fit:cover preserves the prior background-size:cover visual
             - aspect-ratio 1/1 + thumbnail size dropped from 1000x1000 to 800x600
               (no perceptible quality loss on a ~450px-tall card; ~36% bandwidth
               saving on the image-heavy shop grid)
             - alt + loading=lazy + decoding=async added per a11y/perf brief
             Same shape used by Slider/default cycle-41 and Categories/images cycle-39. --}}
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
    </div>
</div>
