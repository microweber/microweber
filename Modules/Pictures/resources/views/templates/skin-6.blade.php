{{--
type: layout
name: Shop Inner
description: Skin 6
--}}

@php
    $pictureElementId = 'module-image-' . ($params['id'] ?? '');
@endphp

@if(isset($data))
    <div class="shop-inner-gallery">
        @if(sizeof($data) > 1)
            <div class="shop-inner-gallery-thumbnails">
                @php $count = -1; @endphp
                @if(empty($data))
                    {{-- task-2026-05-17-525769 / AI-812 — wrap admin-targeted empty-state copy in is_admin() gate;
                         adopt AI-780a typed empty-state pattern (heading + body + CTA pointing to admin_url('media')).
                         Pre-fix the bare <p> rendered "No pictures added. Please add pictures to the module." to
                         anonymous frontend visitors — admin-targeted copy leaked to public surface. --}}
                    @if (is_admin())
                        <div class="mw-canvas-empty-state" data-mw-ai780-content-type="picture">
                            <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                            <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                            <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                        </div>
                    @endif
                        {{-- audit-test 2026-05-07 PM TICKET-AV bundle: `<a style="background-image: url(...)">`
                             was a CSS-injection sink; migrated to `<a><img>`. --}}
                        @foreach($data as $item)
                    @php $count++; @endphp
                    <a class="mx-0"
                       href="{{ thumbnail($item['filename'] ?? '', 1080, 1080) }}"
                       data-mw-product-image="{{ thumbnail($item['filename'] ?? '', 1920, 1920) }}" data-mw-product-image-target="{{ $pictureElementId }}" data-mw-product-image-index="{{ $count }}">
                        {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                        {!! responsive_thumbnail($item['filename'] ?? '', 800, 800, [
                            'alt' => $item['title'] ?? $item['description'] ?? __('Product image'),
                            'class' => 'img-fluid d-block',
                        ]) !!}
                    </a>
                @endforeach
                @endif
            </div>
        @endif
        <div class="shop-inner-big-image position-relative">
            @php
                $price = get_product_prices(content_id(), true);
            @endphp

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

                @if($percentChange > 0)
                    <div class="discount-label">
                        <span class="discount-percentage">
                            {{ number_format($percentChange, 2) }}%
                        </span>
                        <span class="discount-label-text">{{ _lang("Discount") }}</span>
                    </div>
                @endif
            @endif

<img src="{{ isset($data[0]['filename']) ? thumbnail($data[0]['filename'], 1080, 1080) : '' }}"
                         id="{{ $pictureElementId }}" alt="{{ __('Product image') }}"  class="img-fluid"/>
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

        var gallery = {!! isset($data) ? json_encode($data) : '[]' !!};

        document.getElementById('{{ $pictureElementId }}').addEventListener('click', function(){
            mw.gallery(gallery, Number(this.dataset.index || 0));
        });
    </script>
@endif
