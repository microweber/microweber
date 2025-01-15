@php
/*
type: layout
name: Default
description: Default Slider Layout
*/
@endphp

<style>
    #slider-{{ $params['id'] }} .slider-container {
        position: relative;
        overflow: hidden;
    }
    #slider-{{ $params['id'] }} .slider-item {
        display: none;
        width: 100%;
        text-align: {{ $slide['settings']['alignItems'] ?? 'center' }};
    }
    #slider-{{ $params['id'] }} .slider-item.active {
        display: block;
    }
    #slider-{{ $params['id'] }} .slider-item img {
        width: 100%;
        height: auto;
        object-fit: cover;
        @if(isset($slide['settings']['imageBackgroundFilter']))
            @switch($slide['settings']['imageBackgroundFilter'])
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
    #slider-{{ $params['id'] }} .slider-description {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: {{ isset($slide['settings']['imageBackgroundColor']) ? $slide['settings']['imageBackgroundColor'] : 'rgba(0, 0, 0, 0.5)' }};
        opacity: {{ $slide['settings']['imageBackgroundOpacity'] ?? '1' }};
        padding: 15px;
    }
    #slider-{{ $params['id'] }} .slider-description h3 {
        color: {{ $slide['settings']['titleColor'] ?? '#ffffff' }};
        font-size: {{ $slide['settings']['titleFontSize'] ?? '24' }}px;
        margin-bottom: 10px;
    }
    #slider-{{ $params['id'] }} .slider-description p {
        color: {{ $slide['settings']['descriptionColor'] ?? '#ffffff' }};
        font-size: {{ $slide['settings']['descriptionFontSize'] ?? '16' }}px;
        margin-bottom: 15px;
    }
    #slider-{{ $params['id'] }} .slider-button {
        display: inline-block;
        padding: 8px 20px;
        background-color: {{ $slide['settings']['buttonBackgroundColor'] ?? '#007bff' }};
        color: {{ $slide['settings']['buttonTextColor'] ?? '#ffffff' }};
        border: 1px solid {{ $slide['settings']['buttonBorderColor'] ?? 'transparent' }};
        font-size: {{ $slide['settings']['buttonFontSize'] ?? '16' }}px;
        text-decoration: none;
        transition: all 0.3s ease;
    }
    #slider-{{ $params['id'] }} .slider-button:hover {
        background-color: {{ $slide['settings']['buttonBackgroundHoverColor'] ?? '#0056b3' }};
        color: {{ $slide['settings']['buttonTextHoverColor'] ?? '#ffffff' }};
        text-decoration: none;
    }
</style>




<div id="slider-{{ $params['id'] }}" class="slider-container">
    @if ($slides and $slides->count() > 0)
        @foreach ($slides as $slide)
            <div class="slider-item {{ $loop->first ? 'active' : '' }}">
                @if ($slide['media'])
                    <img src="{{ thumbnail($slide['media'], 1200) }}" alt="{{ $slide['name'] }}">
                @else
                    <img src="{{ asset('modules/slider/default-content/default-slide.jpg') }}" alt="Default Slide">
                @endif

                @if ($slide['name'] || $slide['description'] || $slide['button_text'])
                    <div class="slider-description">
                        @if ($slide['name'])
                            <h3>{{ $slide['name'] }}</h3>
                        @endif
                        @if ($slide['description'])
                            <p>{{ $slide['description'] }}</p>
                        @endif
                        @if ($slide['settings']['showButton'] && $slide['link'] && $slide['button_text'])
                            <a href="{{ $slide['link'] }}" class="slider-button">{{ $slide['button_text'] }}</a>
                        @endif
                    </div>
                @endif
            </div>
        @endforeach
    @else
        @include('modules.slider::partials.slider-empty')
    @endif
</div>

@if ($slides->count() > 0)
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentSlide = 0;
    const slides = document.querySelectorAll('#slider-{{ $params['id'] }} .slider-item');

    function showSlide(index) {
        slides.forEach(slide => slide.classList.remove('active'));
        slides[index].classList.add('active');
    }

    function nextSlide() {
        currentSlide = (currentSlide + 1) % slides.length;
        showSlide(currentSlide);
    }

    // Auto advance slides every 5 seconds
    setInterval(nextSlide, 5000);
});
</script>
@endif
