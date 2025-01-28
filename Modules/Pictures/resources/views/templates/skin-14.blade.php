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
                <img src="{{ isset($data[0]['filename']) ? thumbnail($data[0]['filename'], 1080, 1080) : '' }}"
                     id="{{ $pictureElementId }}" />
            </div>

            @if(sizeof($data) > 1)
                <div class="shop-inner-gallery-thumbnails mt-4 d-flex">
                    @php $count = -1; @endphp
                    @if(empty($data))
                        <p>No pictures added. Please add pictures to the gallery.</p>
                    @else
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
