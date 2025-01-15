@php
/*
type: layout
name: Default
description: Default Slider Layout
*/
@endphp

<style>
    .slider-container {
        position: relative;
        overflow: hidden;
    }
    .slider-item {
        display: none;
        width: 100%;
    }
    .slider-item.active {
        display: block;
    }
    .slider-item img {
        width: 100%;
        height: auto;
        object-fit: cover;
    }
    .slider-description {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.5);
        color: white;
        padding: 15px;
    }
</style>

<div class="slider-container">
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
                        @if ($slide['link'] && $slide['button_text'])
                            <a href="{{ $slide['link'] }}" class="btn btn-primary">{{ $slide['button_text'] }}</a>
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
    const slides = document.querySelectorAll('.slider-item');

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
