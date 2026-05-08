{{--
type: layout
name: skin 14 - ocean
description: Skin 14 - ocean
--}}

<style>
    .ocean-14 {
        .shop-inner-big-image {
            img {
                display: flex;
                margin: 0 auto;
                width: auto;
                max-width: 50%;
            }
        }
    }
</style>

@php
    $pictureElementId = 'module-image-' . ($params['id'] ?? '');
@endphp

@if(isset($data))
    <div class="new-skin-shop">
        <div class="shop-inner-gallery ocean-14 row">
            <div class="shop-inner-big-image position-relative ps-lg-0">
                <img src="{{ $data[0]['filename'] ?? '' }}" id="{{ $pictureElementId }}" alt="{{ __('Image') }}" />
            </div>

            @if(sizeof($data) > 1)
                <div class="shop-inner-gallery-thumbnails mt-4 d-flex">
                    @php $count = -1; @endphp
                    @if(empty($data))
                        <p class="mw-pictures-clean">No pictures added. Please add pictures to the module.</p>
                    @else
                        @foreach($data as $item)
                            @php $count++; @endphp
                            {{-- audit-test 2026-05-07 PM TICKET-AV bundle: `<a style="background-image: url(...)">`
                                 was a CSS-injection sink; migrated to `<a><img>`. --}}
                            <a class="mx-0"
                               href="{{ thumbnail($item['filename'] ?? '', 1080, 1080) }}"
                               onclick="setProductImage('{{ $pictureElementId }}', '{{ thumbnail($item['filename'] ?? '', 1920, 1920) }}', {{ $count }});return false;">
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
@endif
