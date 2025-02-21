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
</style>

@php
    $moduleId = $params['id'];
    $size = $size ?? 'auto';
    $sizeAttr = $size != 'auto' ? 'width="' . $size . '"' : '';
@endphp

<div>
    <div class="mw-rollover">
        <img src="{{ $default_image }}" class="mw-rollover-default_image" {!! $sizeAttr !!} alt="{{ $text ?? '' }}"/>

        @if($rollover_image)
            <div class="mw-rollover-overlay">
                <img src="{{ $rollover_image }}" {!! $sizeAttr !!} alt="{{ $text ?? '' }}"/>
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
        // Initialize any JavaScript functionality here
        const rolloverImages = document.querySelectorAll('.mw-rollover');

        rolloverImages.forEach(container => {
            const overlay = container.querySelector('.mw-rollover-overlay');
            if (overlay) {
                // Ensure images are loaded
                const defaultImage = container.querySelector('.mw-rollover-default_image');
                const rolloverImage = overlay.querySelector('img');

                if (defaultImage && rolloverImage) {
                    // Ensure same dimensions
                    rolloverImage.style.width = defaultImage.offsetWidth + 'px';
                    rolloverImage.style.height = defaultImage.offsetHeight + 'px';
                }
            }
        });
    });
</script>
