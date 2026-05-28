<style>
    .mw-rollover {
        position: relative;
        text-align: center;
        margin: 0 auto;
        max-width: 100%;
    }

    .mw-rollover-default_image {
        max-width: 100%;
    }

    .mw-rollover-overlay {
        position: absolute;
        top: 0;
        bottom: 0;
        left: 0;
        right: 0;
        height: 100%;
        width: 100%;
        opacity: 0;
        transition: .3s ease;
        text-align: center;
    }

    .mw-rollover-overlay:hover {
        cursor: pointer;
    }

    .mw-rollover:hover .mw-rollover-overlay {
        opacity: 1;
    }

    .mw-rollover-overlay img {
        max-width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* task-2026-05-28-acfec1 / AI-885: touch-device fallback */
    @media (hover: none) {
        .mw-rollover {
            cursor: pointer;
            -webkit-tap-highlight-color: transparent;
        }
        .mw-rollover-overlay {
            opacity: 0.12;
        }
        .mw-rollover.mw-rollover-active .mw-rollover-overlay {
            opacity: 1;
        }
    }

    @media (prefers-reduced-motion: reduce) {
        .mw-rollover-overlay {
            transition: none;
        }
    }
</style>

@php
    $moduleId = $params['id'];
    $size = $size ?? 'auto';
    $sizeAttr = $size != 'auto' ? 'width="' . $size . '"' : '';
@endphp

<div>
    <div class="mw-rollover">
        <img src="{{ $default_image }}" class="mw-rollover-default_image" {!! $sizeAttr !!} alt="{{ isset($text) && $text !== '' ? $text : (get_option('website_title', 'website') ?: 'Image') }}"/>

        @if($rollover_image)
            <div class="mw-rollover-overlay">
                <img src="{{ $rollover_image }}" {!! $sizeAttr !!} alt="{{ isset($text) && $text !== '' ? $text . ' (rollover)' : (get_option('website_title', 'website') ?: 'Image') . ' (rollover)' }}"/>
            </div>
        @endif
    </div>

    @if($text)
        <div class="element">
            @if($href_url)
                <a href="{{ $href_url }}">{{ $text }}</a>
            @else
                {{ $text }}
            @endif
        </div>
    @endif
</div>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        var rolloverImages = document.querySelectorAll('.mw-rollover');

        rolloverImages.forEach(function (container) {
            var overlay = container.querySelector('.mw-rollover-overlay');
            if (overlay) {
                var defaultImage = container.querySelector('.mw-rollover-default_image');
                var rolloverImage = overlay.querySelector('img');

                if (defaultImage && rolloverImage) {
                    rolloverImage.style.width = defaultImage.offsetWidth + 'px';
                    rolloverImage.style.height = defaultImage.offsetHeight + 'px';
                }
            }
        });

        /* task-2026-05-28-acfec1 / AI-885: tap-to-toggle on touch devices */
        if (window.matchMedia('(hover: none)').matches) {
            rolloverImages.forEach(function (container) {
                var overlay = container.querySelector('.mw-rollover-overlay');
                if (overlay) {
                    container.setAttribute('role', 'button');
                    container.setAttribute('aria-label', 'Tap to toggle image');
                    container.setAttribute('tabindex', '0');
                    container.addEventListener('click', function () {
                        container.classList.toggle('mw-rollover-active');
                    });
                    container.addEventListener('keydown', function (e) {
                        if (e.key === 'Enter' || e.key === ' ') {
                            e.preventDefault();
                            container.classList.toggle('mw-rollover-active');
                        }
                    });
                }
            });
        }
    });
</script>
