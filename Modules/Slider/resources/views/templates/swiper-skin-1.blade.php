@php
/*
type: layout
name: Swiper Skin 1
description: Modern slider with rounded corners and left-aligned content
*/
@endphp

<style>
    #{{ $params['id'] }} .swiper-slide {
        text-align: {{ $slide->settings['alignItems'] ?? 'left' }};
    }
    
    #{{ $params['id'] }} .js-slide-image {
        background-size: cover;
        background-position: center;
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

    #{{ $params['id'] }} .header-section-title {
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

    #{{ $params['id'] }} .header-section-p {
        color: {{ $slide->settings['descriptionColor'] ?? '#666666' }};
        font-size: {{ $slide->settings['descriptionFontSize'] ?? '16' }}px;
    }

    #{{ $params['id'] }} .slider-button {
        display: inline-block;
        padding: 8px 20px;
        background-color: {{ $slide->settings['buttonBackgroundColor'] ?? '#007bff' }};
        color: {{ $slide->settings['buttonTextColor'] ?? '#ffffff' }};
        border: 1px solid {{ $slide->settings['buttonBorderColor'] ?? 'transparent' }};
        font-size: {{ $slide->settings['buttonFontSize'] ?? '16' }}px;
        text-decoration: none;
        transition: all 0.3s ease;
        border-radius: 15px;
    }

    #{{ $params['id'] }} .slider-button:hover {
        background-color: {{ $slide->settings['buttonBackgroundHoverColor'] ?? '#0056b3' }};
        color: {{ $slide->settings['buttonTextHoverColor'] ?? '#ffffff' }};
        text-decoration: none;
    }

    #{{ $params['id'] }} .slide-content {
        background-color: {{ isset($slide->settings['imageBackgroundColor']) ? $slide->settings['imageBackgroundColor'] : 'transparent' }};
        opacity: {{ $slide->settings['imageBackgroundOpacity'] ?? '1' }};
    }
</style>

<div id="js-slider-{{ $params['id'] ?? 'default' }}" class="slider_v2-default swiper">
    <div class="swiper-wrapper">
        @if($slides && $slides->count() > 0)
            @foreach($slides as $slide)
                <div class="swiper-slide">
                    <div class="js-slide-image-{{ $slide->id }}"
                         style="background-image: url('{{ thumbnail($slide->media, 1200) }}');
                                border-radius: 30px;
                                overflow: hidden;">
                    </div>

                    <div style="height: 650px; border-radius: 30px; padding-inline-start: 100px;"
                         class="d-flex flex-column justify-content-center align-items-start text-start gap-4 slide-content">
                        <h3 class="header-section-title js-slide-title-{{ $slide->id }}">
                            {{ $slide->name }}
                        </h3>

                        <p class="header-section-p js-slide-description-{{ $slide->id }}">
                            {{ $slide->description }}
                        </p>

                        @if($slide->settings['showButton'] && $slide->button_text && $slide->link)
                            <a href="{{ $slide->link }}" class="slider-button js-slide-button-{{ $slide->id }}">
                                {{ $slide->button_text }}
                            </a>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <div id="js-slide-pagination-{{ $params['id'] ?? 'default' }}" class="swiper-pagination"></div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Swiper('#js-slider-{{ $params['id'] ?? 'default' }}', {
        loop: true,
        pagination: {
            el: '#js-slide-pagination-{{ $params['id'] ?? 'default' }}',
            clickable: true
        }
    });
});
</script>
