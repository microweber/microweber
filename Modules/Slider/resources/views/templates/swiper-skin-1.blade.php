@php
    /*
    type: layout
    name: Swiper Skin 1
    description: Modern slider with rounded corners and left-aligned content
    */
@endphp





<div id="js-slider-{{ $params['id'] ?? 'default' }}" class="slider_v2-default swiper">
    <div class="swiper-wrapper">
        @if($slides && $slides->count() > 0)
            @foreach($slides as $slide)

                <style>
                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }} {
                        text-align: {{ $slide->settings['alignItems'] ?? 'center' }};
                    }

                    /* audit-test 2026-05-07 PM TICKET-AV bundle: was background-size/position
                       on a div, now object-fit/object-position on a real <img> — same visual
                       fit (cover, centered) with no CSS-injection sink. */
                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }} .js-slide-image-swiper-module {
                        object-fit: cover;
                        object-position: center center;
                        border-radius: 30px;
                        width: 100%;
                        height: 100%;
                        position: absolute;
                        z-index: -1;

                        @if(isset($slide->settings['imageBackgroundFilter']))
                            @switch($slide->settings['imageBackgroundFilter'])
                                @case('blur')
                                filter: blur(5px);
                        @break
                    @case('mediumBlur')
filter: blur(10px);
                        @break
                    @case('maxBlur')
filter: blur(20px);
                        @break
                    @case('grayscale')
filter: grayscale(100%);
                        @break
                    @case('hue-rotate')
filter: hue-rotate(180deg);
                        @break
                    @case('invert')
filter: invert(100%);
                        @break
                    @case('sepia')
filter: sepia(100%);
                    @break
                @endswitch
                @endif
}

                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }} .module-slider-header-section-title {
                        color: {{ $slide->settings['titleColor'] ?? '#000000' }};
                        font-size: {{ $slide->settings['titleFontSize'] ?? '52' }}px;
                        @media screen and (max-width: 991px) {
                            font-size: min(3rem, {{ $slide->settings['titleFontSize'] ?? '52' }}px);
                        }
                        @media screen and (max-width: 600px) {
                            font-size: min(2.5rem, {{ $slide->settings['titleFontSize'] ?? '36' }}px)!important;
                        }
                        @media screen and (max-width: 400px) {
                            font-size: min(2rem, {{ $slide->settings['titleFontSize'] ?? '24' }}px)!important;
                        }
                        overflow-wrap: break-word;
                    }

                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }} .module-slider-header-section-p {
                        color: {{ $slide->settings['descriptionColor'] ?? '#666666' }};
                        font-size: {{ $slide->settings['descriptionFontSize'] ?? '16' }}px;
                    }

                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }} .slider-button {
                        display: inline-block;
                        padding: 8px 20px;
                        background-color: {{ $slide->settings['buttonBackgroundColor'] ?? 'var(--mw-btn-background-color)' }};
                        color: {{ $slide->settings['buttonTextColor'] ?? '#ffffff' }};
                        border: 1px solid {{ $slide->settings['buttonBorderColor'] ?? 'transparent' }};
                        font-size: {{ $slide->settings['buttonFontSize'] ?? '16' }}px;
                        text-decoration: none;
                        transition: all 0.3s ease;
                    }

                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }} .slider-button:hover {
                        background-color: {{ $slide->settings['buttonBackgroundHoverColor'] ?? '#0056b3' }};
                        color: {{ $slide->settings['buttonTextHoverColor'] ?? '#ffffff' }};
                        text-decoration: none;
                    }
                </style>

                <div class="swiper-slide swiper-slide-{{ $slide->id }}">

                    {{-- audit-test 2026-05-07 PM TICKET-AV bundle: migrated `<div bg-image>` to
                         real `<img>` (closes CSS-injection vector + adds alt + srcset + lazy). --}}
                    <img class="js-slide-image-swiper-module js-slide-image-{{ $slide->id }}"
                         src="{{ thumbnail($slide->media, 800) }}"
                         srcset="{{ thumbnail($slide->media, 400) }} 400w, {{ thumbnail($slide->media, 800) }} 800w, {{ thumbnail($slide->media, 1200) }} 1200w"
                         sizes="100vw"
                         alt="{{ $slide->name ?? '' }}"
                         loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                         decoding="async"
                         style="overflow: hidden;">

                    <div style="height: 650px; border-radius: 30px; padding-inline-start: 100px;"
                         class="d-flex flex-column justify-content-center align-items-start text-start gap-4 slide-content">
                        <h3 class="module-slider-header-section-title js-slide-title-{{ $slide->id }}">
                            {{ $slide->name }}
                        </h3>

                        <p class="module-slider-header-section-p js-slide-description-{{ $slide->id }}">
                            {{ $slide->description }}
                        </p>

                        @if($slide->button_text)
                            <a href="{{ $slide->link }}" class="slider-button btn btn-primary js-slide-button-{{ $slide->id }}">
                                {{ $slide->button_text }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div id="js-slide-pagination-{{ $params['id'] ?? 'default' }}" class="swiper-pagination"></div>
    <div id="js-slide-pagination-previous-{{ $params['id'] ?? 'default' }}" class="mw-slider-v2-buttons-slide mw-slider-v2-button-prev"></div>
    <div id="js-slide-pagination-next-{{ $params['id'] ?? 'default' }}" class="mw-slider-v2-buttons-slide mw-slider-v2-button-next"></div>
</div>

<style>
    .swiper-pagination {
        position: absolute !important;
        bottom: 20px !important;
        left: 50% !important;
        transform: translateX(-50%) !important;
        z-index: 10 !important;
        display: flex !important;
        justify-content: center !important;
        align-items: center !important;
        gap: 10px !important;
    }

    .swiper-pagination-bullet {
        width: 12px !important;
        height: 12px !important;
        background: rgba(255, 255, 255, 0.5) !important;
        border-radius: 50% !important;
        opacity: 1 !important;
        margin: 0 !important;
        cursor: pointer !important;
        transition: all 0.3s ease !important;
        border: 2px solid rgba(255, 255, 255, 0.8) !important;
    }

    .swiper-pagination-bullet:hover {
        background: rgba(255, 255, 255, 0.8) !important;
        transform: scale(1.2) !important;
    }

    .swiper-pagination-bullet-active {
        background: var(--mw-btn-background-color) !important;
        border-color: #ffffff !important;
        transform: scale(1.3) !important;
    }
</style>

<script>
    if (!window.SliderV2) {
        mw.require('{{ asset('modules/slider/js/slider-v2.js') }}',true)
    }
</script>

<script>
    ;(function(){
         const slider = new SliderV2('#js-slider-{{ $params['id'] ?? 'default' }}', {
            loop: true,
            pagination: {
                el: '#js-slide-pagination-{{ $params['id'] ?? 'default' }}',
                clickable: true
            },
        });

        document.querySelector('#js-slide-pagination-next-{{ $params['id'] ?? 'default' }}').addEventListener('click', () => {
            slider.driverInstance.slideNext();
        });
        document.querySelector('#js-slide-pagination-previous-{{ $params['id'] ?? 'default' }}').addEventListener('click', () => {
            slider.driverInstance.slidePrev();
        });
    })();
</script>
