{{--
type: layout
name: Skin-17
description: Skin-17
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
    .mw-pictures-17-text {
        position: absolute;
        left: 30px;
        bottom: 30px;
        transition: .5s ease-in-out;
        cursor: pointer;

        .mw-pictures-17-title {
            font-size: 22px;
            line-height: 1.5;
            color: #fff;
            transform: translateY(25px);
            transition: .5s ease-in-out;
            margin-bottom: 0;
        }

        .mw-pictures-17-description {
            font-size: 14px;
            line-height: 1.5;
            margin-bottom: 0;
            opacity: 0;
            transform: translateY(10px);
            transition: .5s ease-in-out;
            color: #fff;
        }
    }

    .mw-pictures-17-wrapper:hover {
        .mw-pictures-17-text {
            .mw-pictures-17-title {
                transform: translateY(0);
            }

            .mw-pictures-17-description {
                opacity: 1;
                transform: translateY(0);
            }
        }
    }
</style>

@if(isset($data))
    <div class="row text-center text-sm-start d-flex justify-content-center justify-content-lg-center"
         id="gallery-{{ $rand }}">
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

                <div class="col-sm-6 col-md-4 p-0 mw-pictures-17-wrapper position-relative">
                    <a data-index="{{ $count }}"
                       href="{{ $item['filename'] ?? '' }}">
                        <img style="object-fit: cover; max-height: 500px; width: 100%; height: 100%;"
                             src="{{ $item['filename'] ?? '' }}"
                             alt=""/>

                        <div class="mw-pictures-17-text">
                            @if($itemTitle)
                                <h5 class="mw-pictures-17-title">{{ $itemTitle }}</h5>
                            @endif
                            <p class="mw-pictures-17-description">{{ $itemDescription }}</p>
                        </div>
                    </a>
                </div>
            @endforeach
            @endif
        @endif
    </div>
@endif
