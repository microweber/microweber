@php
/*
type: layout
name: Slick Slider Skin 1
description: Simple centered slider with optional links
*/
@endphp

<style>
    #{{ $params['id'] }} .slick-slide-item {
        text-align: {{ $slide['settings']['alignItems'] ?? 'center' }};
    }
    #{{ $params['id'] }} .slick-slide-item img {
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
</style>

<div id="{{ $params['id'] }}" class="slick-slide-item">
    @if(isset($slide['media']))
        @if($slide['settings']['showButton'] && $slide['link'])
            <a href="{{ $slide['link'] }}" style="
                display: inline-block;
                text-decoration: none;
                background-color: {{ $slide['settings']['buttonBackgroundColor'] ?? '#007bff' }};
                color: {{ $slide['settings']['buttonTextColor'] ?? '#ffffff' }};
                border: 1px solid {{ $slide['settings']['buttonBorderColor'] ?? 'transparent' }};
                font-size: {{ $slide['settings']['buttonFontSize'] ?? '16' }}px;
                transition: all 0.3s ease;
            ">
                <img src="{{ thumbnail($slide['media'], 1200) }}" alt="{{ $slide['name'] ?? '' }}" style="display: block;"/>
            </a>
        @else
            <img src="{{ thumbnail($slide['media'], 1200) }}" alt="{{ $slide['name'] ?? '' }}"/>
        @endif
    @endif
</div>
