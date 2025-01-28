{{--
type: layout
name: Pictures skin 7 - Justify
description: Pictures Skin 7 - Justify
--}}

@php
    $rand = uniqid();
@endphp

<script>
    mw.require('{{ template_url() }}js/justified/justifiedGallery.js');
    mw.require('{{ template_url() }}js/justified/justifiedGallery.min.css');
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
                <p>No pictures added. Please add pictures to the gallery.</p>
            @else
                @foreach($data as $item)
                    @php $count++; @endphp
                    <a data-index="{{ $count }}"
                       href="{{ thumbnail($item['filename'] ?? '', 1080, 1080) }}">
                        <img src="{{ thumbnail($item['filename'] ?? '', 600, 600) }}" alt=""/>
                    </a>
                @endforeach
            @endif
        @endif
    </div>
@endif
