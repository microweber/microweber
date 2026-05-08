{{--
type: layout
name: Shop Inner For Templates
description: Default skin for shop inner of the templates
--}}

@php
    $pictureElementId = 'module-image-' . ($params['id'] ?? '');
    $itemData = content_data(content_id());

    if (!isset($itemData['label'])) {
        $itemData['label'] = '';
    }
    if (!isset($itemData['label-color'])) {
        $itemData['label-color'] = '';
    }
@endphp

@if(isset($data))
    <div class="shop-inner-gallery">
        @if(sizeof($data) > 1)
            <div class="shop-inner-gallery-thumbnails">
                @php $count = -1; @endphp
                @if(empty($data))
                    <p class="mw-pictures-clean">No pictures added. Please add pictures to the module.</p>
                @else
                    {{-- audit-test 2026-05-07 PM TICKET-AV bundle: `<a style="background-image: url(...)">`
                         was a CSS-injection sink; migrated to `<a><img>`. --}}
                    @foreach($data as $item)
                        @php $count++; @endphp
                        <a class="mx-0"
                           href="{{ thumbnail($item['filename'] ?? '', 1080, 1080) }}"
                           data-mw-product-image="{{ thumbnail($item['filename'] ?? '', 1920, 1920) }}" data-mw-product-image-target="{{ $pictureElementId }}" data-mw-product-image-index="{{ $count }}">
                            {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                            {!! responsive_thumbnail($item['filename'] ?? '', 800, 800, [
                                'alt' => __('Product image'),
                                'class' => 'img-fluid d-block',
                            ]) !!}
                        </a>
                    @endforeach
                @endif
            </div>
        @endif
        <div class="shop-inner-big-image position-relative">
            @php
                $price = app()->shop_manager->get_product_prices(content_id(), true);
            @endphp

            @if(isset($itemData['label-type']) && $itemData['label-type'] === 'text')
                <div class="position-absolute top-0 left-0 m-2" style="z-index: 3;">
                    <div class="badge text-white px-3 pb-1 pt-2 rounded-0" style="background-color: {{ $itemData['label-color'] }};">{{ $itemData['label'] }}</div>
                </div>
            @endif

            @if(isset($price[0]) && isset($price[0]['original_value']))
                @php
                    $oldFigure = floatval($price[0]['custom_value']);
                    $newFigure = floatval($price[0]['original_value']);
                    $percentChange = 0;
                @endphp

                @if($oldFigure < $newFigure)
                    @php
                        $percentChange = (1 - $oldFigure / $newFigure) * 100;
                    @endphp
                @endif

                @if(isset($itemData['label-type']) && $itemData['label-type'] === 'percent' && $percentChange > 0)
                    <div class="discount-label">
                        <span class="discount-percentage">
                            {{ number_format($percentChange, 1) }}%
                        </span>
                        <span class="discount-label-text">{{ _lang("Discount") }}</span>
                    </div>
                @endif
            @endif

            @if(isset($data[0]['filename']))
                {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper.
                     loading=eager because this is the gallery's primary above-the-fold image. --}}
                {!! responsive_thumbnail($data[0]['filename'], 1080, 1080, [
                    'alt' => __('Product image'),
                    'class' => 'img-fluid',
                    'id' => $pictureElementId,
                    'loading' => 'eager',
                ]) !!}
            @else
                {{-- AI-61 / TICKET-EE (cycle-80 2026-05-08): hero
                     image fallback when no product image is set.
                     Prior shape rendered an empty .shop-inner-big-image
                     div — sighted users saw a broken/skewed layout
                     because the surrounding column expected a
                     1080-tall image to drive its height. Now we
                     render an inline-SVG placeholder so the column
                     has a reasonable intrinsic aspect ratio AND
                     screen-reader users hear "No product image
                     available" instead of nothing.

                     Inline SVG (vs. <img src=asset(...)>) avoids a
                     404 risk if the asset hasn't been published —
                     the placeholder is small enough to inline.

                     Same `id` on the <figure> so the JS gallery
                     binding (line 109+ below) finds the element
                     when content has no images and renders an empty
                     gallery instead of NPE-ing. role="img" + aria-label
                     give the figure an accessible name. The inner
                     <svg> is aria-hidden so SR doesn't double-announce. --}}
                <figure class="img-fluid mw-shop-inner-big-image-placeholder"
                        id="{{ $pictureElementId }}"
                        role="img"
                        aria-label="{{ __('No product image available') }}"
                        data-mw-no-product-image
                        style="aspect-ratio: 1 / 1; display: flex; align-items: center; justify-content: center; background: #f5f5f5; color: #999; margin: 0;">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"
                         width="120" height="120" fill="currentColor" aria-hidden="true">
                        <path d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h560q33 0 56.5 23.5T840-760v560q0 33-23.5 56.5T760-120H200Zm0-80h560v-560H200v560Zm40-80h480L570-480 450-320l-90-120-120 160Zm-40 80v-560 560Z"/>
                    </svg>
                    <figcaption class="visually-hidden">{{ __('No product image available') }}</figcaption>
                </figure>
            @endif
        </div>
    </div>

    <script>
        var setProductImage = function (id, url, index) {
            var el = document.getElementById(id);
            el.dataset.index = index;
            var parent = el.parentElement;
            mw.spinner(({element: parent, size: 60, decorate: true})).show();
            mw.element({
                tag: 'img',
                props: {
                    src: url,
                    onload: function (){
                        el.src = url;
                        mw.spinner(({element: parent})).hide();
                    }
                }
            })
        }

        document.addEventListener('DOMContentLoaded', function() {
            var gallery = {!! isset($data) ? json_encode($data) : '[]' !!};

            var elGallery = document.getElementById('{{ $pictureElementId }}');
            if(elGallery) {
                elGallery.addEventListener('click', function () {
                    mw.gallery(gallery, Number(this.dataset.index || 0));
                });
            }
        });
    </script>
@else
    @include('modules.pictures::partials.no-pictures')
@endif
