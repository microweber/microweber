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

{{--
    The rollover init JS (overlay sizing + touch tap/keyboard toggle + a11y attrs)
    moved to the shared frontend bundle: packages/frontend-assets/resources/assets/
    widgets/module-skins-init.js (imported by frontend.js). It runs idempotently on
    ready and re-runs on `moduleLoaded`, so a rollover dropped in Live Edit now
    initialises too — the old inline DOMContentLoaded handler never fired for
    modules added after initial page load. The tap-toggle is a single delegated
    listener, so it covers every instance without per-element binding.
--}}
