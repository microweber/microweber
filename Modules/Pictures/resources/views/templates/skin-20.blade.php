{{--
type: layout
name: Pictures skin 20 - Masonry
description: Pictures Skin 20 - Masonry
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
        height: 300px;
    }

    .grid-item img {
        width: 100%;
        display: block;
        height: 100%;
        object-fit: cover;
    }

    @media (max-width: 1360px) {
        .grid-item {
            width: 50% !important;
        }
        .grid-item:first-child {
            height: 600px !important;
        }
    }

    @media (max-width: 600px) {
        .grid-item {
            width: 100% !important;
        }
        .grid-item:first-child {
            height: 500px !important;
        }
    }

    .grid-item:first-child {
        height: 900px;
    }
</style>

@if(isset($data))
    <div class="grid" id="gallery-{{ $rand }}">
        @if(sizeof($data) > 1)
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
                    {!! responsive_thumbnail($item['filename'] ?? '', 800, null, ['alt' => $item['title'] ?? $item['description'] ?? __('Image')]) !!}
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
