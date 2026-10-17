{{--
type: layout
name: Masonry
description: Masonry
--}}

@if(isset($data))
    @php
        $rand = uniqid();
        // ONCE outside any @foreach loop. Pre-fix the same
        // base64+json_encode ran N times for an N-image gallery
        // because the encode call lived inside data-mw-gallery=
        // attribute on the looped element. Move out for perf +
        // hoist for clarity.
        $mwGalleryGalleryJson = base64_encode(json_encode(array_map(function ($item) {
            return ['image' => $item['filename'] ?? '', 'description' => $item['title'] ?? ''];
        }, $data ?? [])));
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
        @if(empty($data))

            @if (is_admin())
                <div class="mw-canvas-empty-state" data-mw-content-type="picture">
                    <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                    <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                    <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                </div>
            @endif
        @else
            @foreach($data as $item)
                @php $count++; $cropPos = $item['crop_position'] ?? ''; @endphp
                <div class="masonry-item" data-mw-gallery="{{ $mwGalleryGalleryJson }}" data-mw-gallery-index="{{ $count }}">

                    {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                    {!! responsive_thumbnail($item['filename'] ?? '', 300, null, array_filter(['alt' => $item['title'] ?? $item['description'] ?? __('Image'), 'class' => 'img-fluid', 'style' => $cropPos !== '' ? 'object-position: ' . $cropPos : ''])) !!}
                    @if(isset($item['title']) && $item['title'] != '')
                        <div class="masonry-item-description">{{ $item['title'] }}</div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>

    <script>
        (function () {
            window.gallery{{ $rand }} = @php echo json_encode(array_map(function ($item) { return ['image' => $item['filename'] ?? '', 'description' => $item['title'] ?? '']; }, $data ?? []), JSON_HEX_QUOT | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS); @endphp;
        })();
    </script>
@else
    @include('modules.pictures::partials.no-pictures')
@endif
