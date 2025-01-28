{{--
type: layout
name: Pictures skin 18 - Masonry
description: Pictures Skin 18 - Masonry
--}}

@php
    $rand = uniqid();
@endphp

<script>
    mw.lib.require('masonry');
</script>

<script>
    $(document).ready(function() {
        $('#gallery-{{ $rand }}').masonry({
            itemSelector: '.grid-item',
            percentPosition: true
        })
    });
</script>

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
    .mw-pictures-18-text {
        position: absolute;
        left: 30px;
        bottom: 30px;
        transition: .5s ease-in-out;
        cursor: pointer;

        .mw-pictures-18-title {
            font-size: 22px;
            line-height: 1.5;
            color: #fff;
            transform: translateY(25px);
            transition: .5s ease-in-out;
            margin-bottom: 0;
        }

        .mw-pictures-18-description {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 0;
            opacity: 0;
            transform: translateY(10px);
            transition: .5s ease-in-out;
            color: #fff;
        }
    }

    .mw-pictures-18-wrapper:hover {
        .mw-pictures-18-text {
            .mw-pictures-18-title {
                transform: translateY(0);
            }

            .mw-pictures-18-description {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }

    .grid-item {
        display: block;
        overflow: hidden;
        width: 33.333333%;
    }

    .grid-item img {
        width: 100%;
        display: block;
    }

    @media (max-width: 1360px) {
        .grid-item {
            width: 50% !important;
        }
    }

    @media (max-width: 600px) {
        .grid-item {
            width: 100% !important;
        }
    }
</style>

@if(isset($data))
    <div class="grid" id="gallery-{{ $rand }}">
        @if(sizeof($data) > 1)
            @php $count = -1; @endphp
            @if(empty($data))
                <p class="mw-pictures-clean">No pictures added. Please add pictures to the gallery.</p>
            @else
                @foreach($data as $item)
                @php
                    $count++;
                    $itemTitle = false;
                    $itemDescription = false;
                    $itemLink = false;
                    $itemAltText = 'Open';
                    if (isset($item['image_options']) && is_array($item['image_options'])) {
                        if (isset($item['image_options']['title'])) {
                            $itemTitle = $item['image_options']['title'];
                        }
                        if (isset($item['image_options']['caption'])) {
                            $itemDescription = $item['image_options']['caption'];
                        }
                        if (isset($item['image_options']['link'])) {
                            $itemLink = $item['image_options']['link'];
                        }
                        if (isset($item['image_options']['alt-text'])) {
                            $itemAltText = $item['image_options']['alt-text'];
                        }
                    }
                @endphp

                <a class="mw-pictures-18-wrapper grid-item"
                   data-index="{{ $count }}"
                   href="{{ $item['filename'] ?? '' }}">
                    <img src="{{ $item['filename'] ?? '' }}" alt=""/>
                    <div class="mw-pictures-18-text">
                        @if($itemTitle)
                            <h5 class="mw-pictures-18-title">{{ $itemTitle }}</h5>
                        @endif
                        <p class="mw-pictures-18-description">{{ $itemDescription }}</p>
                    </div>
                </a>
            @endforeach
            @endif
        @endif
    </div>
@endif
