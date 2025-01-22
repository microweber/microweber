@php
/*
type: layout
name: Swiper Default
description: Modern slider with Swiper.js integration
*/
@endphp



<div id="js-slider-{{ $params['id'] ?? 'default' }}" class="slider_v2-default swiper">
    <div class="swiper-wrapper">
        @if($slides && $slides->count() > 0)
            @foreach($slides as $slide)

                <style>
                    .swiper-slide {
                        text-align: {{ $slide->settings['alignItems'] ?? 'center' }};
                    }

                    .js-slide-image-swiper-module {
                        background-size: cover;
                        background-repeat: no-repeat;
                        background-position: center center;
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

                    .module-slider-header-section-title {
                        color: {{ $slide->settings['titleColor'] ?? '#000000' }};
                        font-size: {{ $slide->settings['titleFontSize'] ?? '24' }}px;
                        @media screen and (max-width: 991px) {
                            font-size: min(3rem, {{ $slide->settings['titleFontSize'] ?? '24' }}px);
                        }
                        @media screen and (max-width: 600px) {
                            font-size: min(2.5rem, {{ $slide->settings['titleFontSize'] ?? '24' }}px)!important;
                        }
                        @media screen and (max-width: 400px) {
                            font-size: min(2rem, {{ $slide->settings['titleFontSize'] ?? '24' }}px)!important;
                        }
                        overflow-wrap: break-word;
                    }

                    .module-slider-header-section-p {
                        color: {{ $slide->settings['descriptionColor'] ?? '#666666' }};
                        font-size: {{ $slide->settings['descriptionFontSize'] ?? '16' }}px;
                    }

                    .slider-button {
                        display: inline-block;
                        padding: 8px 20px;
                        background-color: {{ $slide->settings['buttonBackgroundColor'] ?? '#007bff' }};
                        color: {{ $slide->settings['buttonTextColor'] ?? '#ffffff' }};
                        border: 1px solid {{ $slide->settings['buttonBorderColor'] ?? 'transparent' }};
                        font-size: {{ $slide->settings['buttonFontSize'] ?? '16' }}px;
                        text-decoration: none;
                        transition: all 0.3s ease;
                    }

                    .slider-button:hover {
                        background-color: {{ $slide->settings['buttonBackgroundHoverColor'] ?? '#0056b3' }};
                        color: {{ $slide->settings['buttonTextHoverColor'] ?? '#ffffff' }};
                        text-decoration: none;
                    }

                    .js-slide-elements-{{ $slide->id }} {
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                    }
                </style>
                <div class="swiper-slide">
                    <div class="js-slide-image-swiper-module js-slide-image-{{ $slide->id }}"
                         style="background-image: url('{{ thumbnail($slide->media, 1200) }}');">
                    </div>

                    <div class="js-slide-elements-{{ $slide->id }}" style="height: calc(100vh - 100px);">
                        <div class="mb-2">
                            <h3 class="module-slider-header-section-title js-slide-title-{{ $slide->id }}">
                                {{ $slide->name }}
                            </h3>
                        </div>
                        <div>
                            <p class="module-slider-header-section-p js-slide-description-{{ $slide->id }}">
                                {{ $slide->description }}
                            </p>
                        </div>

                        @if($slide->button_text)
                            <div class="mt-5">
                                <a href="{{ $slide->link }}" class="slider-button js-slide-button-{{ $slide->id }}">
                                    {{ $slide->button_text }}
                                </a>
                            </div>
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

<script>
<?php  print get_asset('/Modules/Slider/resources/assets/js/slider-v2.js'); ?>






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

</script>

