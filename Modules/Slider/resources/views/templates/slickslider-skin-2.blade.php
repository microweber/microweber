@php
/*
type: layout
name: Slick Slider Skin 2
description: Simple centered slider with optional links and improved conditional rendering
*/
@endphp

<style>
    #slider-{{ $params['id'] }} .slick-slide-item-2 {
        text-align: {{ $slide['settings']['alignItems'] ?? 'center' }};
    }
    #slider-{{ $params['id'] }} .slick-slide-item-2 img {
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
    #slider-{{ $params['id'] }} .slick-slide-link {
        display: inline-block;
        background-color: {{ $slide['settings']['imageBackgroundColor'] ?? 'transparent' }};
        opacity: {{ $slide['settings']['imageBackgroundOpacity'] ?? '1' }};
    }
</style>

<div id="slider-{{ $params['id'] }}" class="slick-slide-item-2">
    @if(isset($slide['media']))
        @if($slide['settings']['showButton'] && $slide['link'])
            <a href="{{ $slide['link'] }}" class="slick-slide-link">
                <img src="{{ thumbnail($slide['media'], 1200) }}" alt="{{ $slide['name'] ?? '' }}" style="display: block;"/>
            </a>
        @else
            <img src="{{ thumbnail($slide['media'], 1200) }}" alt="{{ $slide['name'] ?? '' }}"/>
        @endif
    @endif
</div>
