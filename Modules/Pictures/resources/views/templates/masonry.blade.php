{{--
type: layout
name: Masonry
description: Masonry
--}}

@if(isset($data))
    @php
        $rand = uniqid();
    @endphp

    <script>mw.lib.require("masonry");</script>
    <script>mw.moduleCSS("{{ asset('modules/pictures/css/style.css') }}");</script>

    <script>
        mw._masons = mw._masons || [];
        $(document).ready(function () {
            var m = mw.$('#mw-gallery-{{ $rand }}');
            m.masonry({
                "itemSelector": '.masonry-item',
                "gutter": 5
            });
            mw._masons.push(m);
            if (typeof mw._masons_binded === 'undefined') {
                mw._masons_binded = true;
                setInterval(function () {
                    var l = mw._masons.length, i = 0;
                    for (; i < l; i++) {
                        var _m = mw._masons[i];
                        if (mw.$(".masonry-item", _m[0]).length > 0) {
                            _m.masonry({
                                "itemSelector": '.masonry-item',
                                "gutter": 5
                            });
                        }
                    }
                }, 500);
            }
        });
    </script>

    <div class="mw-images-template-masonry" id="mw-gallery-{{ $rand }}" style="position: relative;width: 100%;">
        @php $count = -1; @endphp
        @foreach($data as $item)
            @php $count++; @endphp
            <div class="masonry-item" onclick="mw.gallery(gallery{{ $rand }}, {{ $count }})">
                <img src="{{ thumbnail($item['filename'] ?? '', 300) }}" width="100%"/>
                @if(isset($item['title']) && $item['title'] != '')
                    <div class="masonry-item-description">{{ $item['title'] }}</div>
                @endif
            </div>
        @endforeach
    </div>

    <script>
        gallery{{ $rand }} = [
            @foreach($data as $item)
                {
                    image: "{{ isset($item['filename']) ? $item['filename'] : '' }}",
                    description: "{{ isset($item['title']) ? $item['title'] : '' }}"
                },
            @endforeach
        ];
    </script>
@else
    @include('modules.pictures::partials.no-pictures')
@endif
