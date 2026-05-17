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
        @else
            @foreach($data as $item)
                @php $count++; @endphp
                <div class="masonry-item" data-mw-gallery="@php echo base64_encode(json_encode(array_map(function ($it) { return ['image' => $it['filename'] ?? '', 'description' => $it['title'] ?? '']; }, $data ?? []))); @endphp" data-mw-gallery-index="{{ $count }}">
                    {{-- task-2026-05-05-90021f — lazy-load gallery images. --}}
                    {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                    {!! responsive_thumbnail($item['filename'] ?? '', 300, null, ['alt' => $item['title'] ?? $item['description'] ?? __('Image'), 'class' => 'img-fluid']) !!}
                    @if(isset($item['title']) && $item['title'] != '')
                        <div class="masonry-item-description">{{ $item['title'] }}</div>
                    @endif
                </div>
            @endforeach
        @endif
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
