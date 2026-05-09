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
                <p class="mw-pictures-clean">No pictures added. Please add pictures to the module.</p>
            @else
                @foreach($data as $item)
                    @php $count++; @endphp
                    {{-- AI-113 / TICKET-CP (cycle-103 2026-05-09): inline
                         `style="background-image: url({{ thumbnail(...) }})"`
                         lifted to a real `<img>` (CSP + apostrophe-injection
                         fix; same shape AI-89 / BIG2-C handled for posts). --}}
                    <div class="selector col-sm-6 col-lg-4 p-3">
                        <a class="picture-thumbnail-link"
                           data-index="{{ $count }}"
                           href="{{ thumbnail($item['filename'] ?? '', 1080, 1080) }}">
                            <img src="{{ thumbnail($item['filename'] ?? '', 1080, 1080, true) }}"
                                 alt="{{ $item['title'] ?? '' }}"
                                 loading="lazy" decoding="async"
                                 class="img-fluid w-100 h-auto">
                        </a>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
@endif
