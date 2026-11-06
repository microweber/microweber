@if (mw_asset_once('imagerollover-default'))
    {{-- Skin CSS moved to Modules/ImageRollover/resources/assets/css/default.css (served from
         public/modules/image_rollover/css/default.css) and emitted once per page via mw_asset_once
         instead of being re-inlined for every instance of this skin. --}}
    <link rel="stylesheet" href="{{ asset('modules/image_rollover/css/default.css') }}">
@endif

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
