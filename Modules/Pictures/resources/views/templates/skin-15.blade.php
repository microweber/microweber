{{--
type: layout
name: Skin-15
description: Skin-15
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

<style>
    #gallery-{{ $rand }} .background-image-holder {
        min-height: 500px;
        display: block;
    }

    #gallery-{{ $rand }} .selector:nth-child(odd) .background-image-holder {
        min-height: 400px;
        display: block;
    }
</style>

@if(isset($data))
    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center align-items-center"
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
                    @php $cropPos = $item['crop_position'] ?? ''; @endphp

                    <div class="selector col-sm-6 col-lg-4 p-3">
                        <a class="picture-thumbnail-link"
                           data-index="{{ $count }}"
                           href="{{ thumbnail($item['filename'] ?? '', 1080, 1080) }}">
                            <img src="{{ thumbnail($item['filename'] ?? '', 1080, 1080, true) }}"
                                 alt="{{ $item['title'] ?? '' }}"
                                 loading="lazy" decoding="async"
                                 @if($cropPos) style="object-position: {{ $cropPos }}" @endif
                                 class="img-fluid w-100 h-auto">
                        </a>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
@endif
