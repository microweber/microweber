{{--
type: layout
name: Skin-16 for Logos
description: Skin-16 for Logos
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
    .pictures-16 .background-image-holder {
        width: 100%;
        border-radius: 5px;
        transition: .5s;
        background-color: #fff;
    }
    .pictures-16 .background-image-holder:hover {
        transform: perspective(200px) translateZ(20px);
    }
</style>

@if(isset($data))
    <div class="row text-center text-sm-start col-xl-10 mx-auto d-flex justify-content-center justify-content-lg-center pictures-16"
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
                           href="{{ $item['filename'] ?? '' }}">
                            <div class="background-image-holder mh-200"
                                 style="background-image: url('{{ thumbnail($item['filename'] ?? '', 800, 800) }}'); background-size: contain;">
                            </div>
                        </a>
                    </div>
                @endforeach
            @endif
        @endif
    </div>
@endif
