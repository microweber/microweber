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
                    <p>No pictures added. Please add pictures to the gallery.</p>
                        @foreach($data as $item)
                    @php $count++; @endphp
                    <a class="mx-0"
                       href="{{ thumbnail($item['filename'] ?? '', 1080, 1080) }}"
                       onclick="setProductImage('{{ $pictureElementId }}', '{{ thumbnail($item['filename'] ?? '', 1920, 1920) }}', {{ $count }});return false;"
                       style="background-image: url('{{ thumbnail($item['filename'] ?? '', 800, 800) }}');">
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
                 id="{{ $pictureElementId }}" />
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
