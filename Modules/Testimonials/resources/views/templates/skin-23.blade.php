@php
/*

type: layout

name: Skin-23

description: Skin-23

*/
@endphp

@php
$rand = uniqid();
$limit = 40;
@endphp

<style>
    .mw-testimonials-23-large-text {
        font-size: 23px;
        letter-spacing: -.01em;
        line-height: 1.3em;
    }

    .mw-testimonials-23-quote-pill, .mw-testimonials-23-quote-pill-row {
        align-items: center;
        column-gap: 18px;
        display: flex;
        flex: 0 0 auto;
        row-gap: 18px;
    }

    .mw-testimonials-23-quote-pill-row {
        flex-wrap: nowrap;
    }

    .mw-testimonials-23-quote-pill {
        background-color: #f4f3f1;
        border-radius: 200px;
        padding: 9px 36px 9px 9px;
    }

    .mw-testimonials-23-quote-pill-avatar {
        max-height: 54px;
        border-radius: 200px;
    }

    @media screen and (max-width: 991px) {
        .mw-testimonials-23-quote-pill-avatar {
            max-height: 48px;
        }
    }

    @media screen and (max-width: 767px) {
        .mw-testimonials-23-large-text {
            font-size: 20px;
            line-height: 1.35em;
        }

        .mw-testimonials-23-quote-pill {
            column-gap: 12px;
            row-gap: 12px;
        }

        .mw-testimonials-23-quote-pill-avatar {
            max-height: 36px;
        }
    }

    @media screen and (max-width: 479px) {
        .mw-testimonials-23-large-text {
            font-size: 17px;
            line-height: 1.35em;
        }

        .mw-testimonials-23-quote-pill {
            padding: 6px 24px 6px 6px;
        }
    }

    @media screen and (max-width: 991px) {
        .mw-testimonials-23-large-text.mw-testimonials-23-quote-pill-text {
            font-size: 18px;
        }
    }

    @media screen and (max-width: 767px) {
        .mw-testimonials-23-large-text.mw-testimonials-23-quote-pill-text {
            font-size: 16px;
        }
    }

    @media screen and (max-width: 479px) {
        .mw-testimonials-23-large-text.mw-testimonials-23-quote-pill-text {
            font-size: 14px;
        }

        .mw-testimonials-23-quote-pill-row.scroll-left {
            column-gap: 12px;
            row-gap: 12px;
        }

        .mw-testimonials-23-quote-pill-rows {
            column-gap: 12px;
            row-gap: 12px;
        }

        .mw-testimonials-23-quote-pill-row-wrapper {
            column-gap: 12px;
            row-gap: 12px;
        }
    }

    .mw-testimonials-23-quote-pill-rows {
        column-gap: 18px;
        display: flex;
        flex-direction: column;
        row-gap: 18px;
    }

    .mw-testimonials-23-gradient-scrim {
        background-image: linear-gradient(90deg, #fff, rgba(255, 255, 255, 0) 15%, rgba(255, 255, 255, 0) 85%, #fff);
        bottom: 0;
        left: 0;
        pointer-events: none;
        position: absolute;
        right: 0;
        top: 0;
        z-index: 2;
    }

    .mw-testimonials-23-quote-pill-row-wrapper {
        column-gap: 18px;
        display: flex;
        overflow: hidden;
        position: relative;
        row-gap: 18px;
    }
</style>

@php
$testimonials_id = 'testimonials-' . $rand;
$selector_prefix = '#' . $testimonials_id . ' ';
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
</style>

@php
$size = sizeof($testimonials);
@endphp

<div class="mw-testimonials-23-quote-pill-rows lg-carousel" id="{{ $testimonials_id }}" role="region">
    <div class="mw-testimonials-23-quote-pill-row lg-carousel-container" id="{{ $testimonials_id }}container" role="list">
        @if($testimonials->isEmpty())
            <p class="mw-pictures-clean">No testimonials added to the module. Please add your testimonials to see the content..</p>
        @else
            @if ($size > 1)
                @foreach ($testimonials as $item)
                    <div class="mw-testimonials-23-quote-pill lg-carousel-item" role="listitem">
                        @if (isset($item['client_image']))
                            <img class="mw-testimonials-23-quote-pill-avatar" loading="lazy" src="{{ thumbnail($item['client_image'], 800) }}"/>
                        @endif

                        <div class="mw-testimonials-23-large-text mw-testimonials-23-quote-pill-text"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</div>
                    </div>
                @endforeach
            @else

                @foreach ($testimonials as $item)
                    <div class="mw-testimonials-23-quote-pill lg-carousel-item" role="listitem">
                        @if (isset($item['client_image']))
                            <img class="mw-testimonials-23-quote-pill-avatar" loading="lazy" src="{{ thumbnail($item['client_image'], 800) }}"/>
                        @endif

                        <div class="mw-testimonials-23-large-text mw-testimonials-23-quote-pill-text"> {{ \Illuminate\Support\Str::limit($item['content'], 250) }}</div>
                    </div>
                @endforeach
            @endif
        @endif

    </div>
    <div class="mw-testimonials-23-gradient-scrim"></div>
</div>

<script>
    ;(function(containerId){
        const carouselContainer = document.getElementById(containerId);
        const carouselItems = carouselContainer.innerHTML;
        carouselContainer.innerHTML += carouselItems;

        let scrollLeft = 0;
        const scrollSpeed = 7;

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

        let lastTimestamp = null;
        requestAnimationFrame(animateCarousel);
    })('{{ $testimonials_id }}container');
</script>
