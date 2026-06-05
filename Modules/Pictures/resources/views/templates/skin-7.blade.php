{{--
type: layout
name: Pictures skin 7 - Justify
description: Pictures Skin 7 - Justify
--}}

@php
    $rand = uniqid();
@endphp

<script>
    mw.lib.require('justified-gallery');
</script>

<script>
    var masonry = function (id) {
        var el = mwd.getElementById(id);
        if(el && !el.__gallery) {
            el.__gallery = [];
            var aa = $(el).justifiedGallery({
                sizeRangeSuffixes: {
                    'lt100': '',
                    'lt240': '',
                    'lt320': '',
                    'lt500': '',
                    'lt640': '',
                    'lt1024': ''
                },
                rowHeight: 350,
                lastRow: 'justify',
                margins: 7
            });
            Array.from(el.querySelectorAll('a')).forEach(function (link){
                el.__gallery.push({
                    url: link.href
                })
                link.addEventListener('click', function (e){
                    e.preventDefault()
                    mw.gallery(el.__gallery, Number(this.dataset.index || 0));
                })
            })
        }
    }

    $(window).on('load', function () {
        masonry('gallery-{{ $rand }}');
    });
    $(document).ready(function () {
        masonry('gallery-{{ $rand }}');
    });
</script>

@if(isset($data))
    <div class="module-posts-template-justified module-posts-template-justifiedfull"
         id="gallery-{{ $rand }}">
        @if(sizeof($data) > 1)
            @php $count = -1; @endphp
            @if(empty($data))
                {{-- task-2026-05-17-525769 / AI-812 — wrap admin-targeted empty-state copy in is_admin() gate;
                     adopt AI-780a typed empty-state pattern (heading + body + CTA pointing to admin_url('media')).
                     Pre-fix the bare <p> rendered "No pictures added. Please add pictures to the module." to
                     anonymous frontend visitors — admin-targeted copy leaked to public surface. --}}
                @if (is_admin())
                    <div class="mw-canvas-empty-state" data-mw-content-type="picture">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                    </div>
                @endif
            @else
                @foreach($data as $item)
                    @php $count++; @endphp
                    <a data-index="{{ $count }}"
                       href="{{ thumbnail($item['filename'] ?? '', 1080, 1080) }}">
                        {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                        {!! responsive_thumbnail($item['filename'] ?? '', 600, 600, ['class' => 'img-fluid']) !!}
                    </a>
                @endforeach
            @endif
        @endif
    </div>
@endif
