{{--
type: layout
name: Skin for sliding Logos
description: Skin for sliding Logos
--}}

@php
    $rand = uniqid();
    $defaultImageWidth = '100'; // Default width in pixels
    $imageWidth = get_option('imageWidth', $params['id'] ?? null);
    if ($imageWidth == false) {
        $imageWidth = $defaultImageWidth;
    }

    $gallery_id = 'gallery-' . $rand;
    $selector_prefix = '#' . $gallery_id . ' ';
@endphp

<style>
    {{ $selector_prefix }} {
        --items-per-page: 4;
    }

    {{ $selector_prefix }} {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    {{ $selector_prefix }}.lg-carousel-container {
        white-space: nowrap;
    }

    {{ $selector_prefix }}.lg-carousel-item {
        display: inline-block;
        width: {{ $imageWidth }}px;
        padding: 20px;
        text-align: center;
    }
</style>

@if(isset($data))
    @php $size = sizeof($data); @endphp

    <div class="lg-carousel" id="{{ $gallery_id }}" role="region">
        <div class="lg-carousel-container" id="{{ $gallery_id }}container" role="list">
            @if($size > 1)
                @if(empty($data))
                    <p>No pictures added. Please add pictures to the gallery.</p>
                @else
                     @foreach($data as $item)
                    @php
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

                    <a class="lg-carousel-item"
                       role="listitem"
                       href="{{ $itemLink ?? '#' }}">
                        <img src="{{ thumbnail($item['filename'] ?? '', 800, 800) }}"
                             alt="{{ $item['title'] ?? '' }}"
                             title="{{ $item['title'] ?? '' }}">
                    </a>
                @endforeach
                @endif

                @foreach($data as $item)
                    @php
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

                    <a class="lg-carousel-item"
                       role="listitem"
                       href="{{ $itemLink ?? '#' }}">
                        <img src="{{ thumbnail($item['filename'] ?? '', 800, 800) }}"
                             alt="{{ $item['title'] ?? '' }}"
                             title="{{ $item['title'] ?? '' }}">
                    </a>
                @endforeach
            @endif
        </div>
        <div class="mw-new-9-gradient-scrim"></div>
    </div>

    <script>
        ;(function (containerId) {
            const carouselContainer = document.getElementById(containerId);
            let scrollLeft = 0;
            const scrollSpeed = 7;
            let lastTimestamp = null;

            function animateCarousel(timestamp) {
                if (!lastTimestamp) {
                    lastTimestamp = timestamp;
                }

                const deltaTime = timestamp - lastTimestamp;
                lastTimestamp = timestamp;

                scrollLeft += scrollSpeed * deltaTime / 60;
                if (scrollLeft >= carouselContainer.scrollWidth / 2) {
                    scrollLeft = 0;
                }
                carouselContainer.style.transform = `translateX(-${scrollLeft}px)`;

                requestAnimationFrame(animateCarousel);
            }

            requestAnimationFrame(animateCarousel);
        })('{{ $gallery_id }}container');
    </script>
@endif
