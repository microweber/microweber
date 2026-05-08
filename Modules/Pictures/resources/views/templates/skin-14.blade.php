{{--
type: layout
name: skin 14
description: Skin 14
--}}

@php
    $pictureElementId = 'module-image-' . ($params['id'] ?? '');
@endphp

@if(isset($data))
    <div class="new-skin-shop">
        <div class="shop-inner-gallery row">
            <div class="shop-inner-big-image position-relative ps-lg-0">
                {{-- AI-74 / TICKET-H (cycle-69 2026-05-08): hero image now
                     emits a full responsive <img> via the cycle-55 helper —
                     adds srcset + sizes + decoding=async on top of the
                     existing alt + lazy. The hero is above-the-fold so
                     loading=eager. --}}
                {!! responsive_thumbnail($data[0]['filename'] ?? '', 1080, 1080, [
                    'id' => $pictureElementId,
                    'alt' => __('Product image'),
                    'class' => 'img-fluid',
                    'loading' => 'eager',
                ]) !!}
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
                                 was a CSS-injection sink; migrated to `<a><img src="..." alt loading="lazy"></a>` —
                                 closes the vector + adds alt text + lazy-loading. --}}
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
