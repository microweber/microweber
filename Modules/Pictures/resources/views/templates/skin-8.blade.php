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
                <p class="mw-pictures-clean">No pictures added. Please add pictures to the gallery.</p>
            @else
                @foreach($data as $item)
                @php $count++; @endphp
                <div class="col-sm-6 col-md-4 col-lg-3 pb-3 px-2">
                    <a data-index="{{ $count }}"
                       href="{{ thumbnail($item['filename'] ?? '', 1080, 1080) }}">
                        <div class="img-as-background mh-400">
                            <img src="{{ thumbnail($item['filename'] ?? '', 1080, 1080, true) }}"/>
                        </div>
                    </a>
                </div>
            @endforeach
            @endif
        @endif
    </div>
@endif
