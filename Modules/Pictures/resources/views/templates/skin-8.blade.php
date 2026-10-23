{{--
type: layout
name: Skin-8
description: Skin-8
--}}

@php
    $rand = uniqid();
@endphp

<script>
    var gallery{{ $rand }} = function (id) {
        var el = mwd.getElementById(id);
        if(el && !el.__gallery) {
            el.__gallery = [];
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
        gallery{{ $rand }}('gallery-{{ $rand }}');
    });
    $(document).ready(function () {
        gallery{{ $rand }}('gallery-{{ $rand }}');
    });
</script>

@if(isset($data))
    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center"
         id="gallery-{{ $rand }}">
        @if(sizeof($data) > 1)
            @php $count = -1; @endphp
            @if(empty($data))

                @if (is_live_edit())
                    <div class="mw-canvas-empty-state" data-mw-content-type="picture">
                        <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                        <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                        <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                    </div>
                @endif
            @else
                @foreach($data as $item)
                @php $count++; @endphp
                <div class="col-sm-6 col-md-4 col-lg-3 pb-3 px-2">
                    <a data-index="{{ $count }}"
                       href="{{ thumbnail($item['filename'] ?? '', 1080, 1080) }}">
                        <div class="img-as-background mh-400">
                            @php $cropPos = $item['crop_position'] ?? ''; @endphp
                            {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                            {!! responsive_thumbnail($item['filename'] ?? '', 1080, 1080, array_merge(['alt' => $item['title'] ?? $item['description'] ?? __('Image'), 'class' => 'img-fluid', 'crop' => true], $cropPos !== '' ? ['style' => 'object-position: ' . $cropPos] : [])) !!}
                        </div>
                    </a>
                </div>
            @endforeach
            @endif
        @endif
    </div>
@endif
