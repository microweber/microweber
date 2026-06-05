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
                    {{-- task-2026-05-17-525769 / AI-812 — wrap admin-targeted empty-state copy in is_admin() gate;
                         adopt AI-780a typed empty-state pattern (heading + body + CTA pointing to admin_url('media')).
                         Pre-fix the bare <p> rendered "No pictures added. Please add pictures to the module." to
                         anonymous frontend visitors — admin-targeted copy leaked to public surface. --}}
                    @if (is_admin())
                        <div class="mw-canvas-empty-state" data-mw-content-type="picture">
                            <h3 class="mw-canvas-empty-state__title">{{ __('No pictures yet') }}</h3>
                            <p class="mw-canvas-empty-state__body">{{ __('Add your first picture to fill this gallery.') }}</p>
                            <a class="mw-canvas-empty-state__cta" href="{{ admin_url('media') }}" aria-label="{{ __('+ Add picture') }}">{{ __('+ Add picture') }}</a>
                        </div>
                    @endif
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
                        {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                        {!! responsive_thumbnail($item['filename'] ?? '', 800, 800, [
                            'alt' => $item['title'] ?? $item['description'] ?? __('Image'),
                            'class' => 'img-fluid',
                        ]) !!}
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
                        {{-- audit-test 2026-05-08 PM TASK-012 / TICKET-CX (cycle-55): responsive_thumbnail helper. --}}
                        {!! responsive_thumbnail($item['filename'] ?? '', 800, 800, [
                            'alt' => $item['title'] ?? $item['description'] ?? __('Image'),
                            'class' => 'img-fluid',
                        ]) !!}
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
