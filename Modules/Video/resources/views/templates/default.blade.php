{{--
type: layout
name: Default
description: Default
--}}

<style>
    #{{ $params['id'] ?? '' }} .mwembed-video {
        max-width: 100%;
        margin: 0 auto;
        padding: 0;
        height: auto!important;
        position: relative;

    }

    #{{ $params['id'] ?? '' }} .mwembed-video video {
         aspect-ratio: 16 / 9;
         height: auto!important;
     }


    #{{ $params['id'] ?? '' }} .mwembed-video:after {
         position: absolute;
         top: 50%;
         left: 50%;
         transform: translate(-50%, -50%);
         background-color: rgba(255, 255, 255, 0.80);
         color: #fff;
         border-radius: 50%;
         cursor: pointer;
         width: 80px;
         height: 80px;
         border: none;
         pointer-events: none;
         background-image: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" height="30" viewBox="0 0 48 48" width="30"><path d="M-838-2232H562v3600H-838z" fill="none"/><path d="M16 10v28l22-14z"/><path d="M0 0h48v48H0z" fill="none"/></svg>');
         background-repeat: no-repeat;
         background-position: center;
         content: '';
         z-index: 3;

     }

    #{{ $params['id'] ?? '' }} .mwembed-video:before {
         position: absolute;
         top: 0;
         left: 0;
         width: 100%;
         height: 100%;
         pointer-events: none;
         background-color: rgba(0, 0, 0, 0.4);
         z-index: 2;
         content: '';
     }


    #{{ $params['id'] ?? '' }} .playButton-d-none:before,  #{{ $params['id'] ?? '' }} .playButton-d-none:after {
        display: none;
    }
</style>

@if(isset($lazyload) && $lazyload)
{{-- AI-1010 / task-2026-05-22 — lazy-load click handler disabled in live-edit mode to
     prevent play-button click from conflicting with canvas element selection. --}}
@if(!is_live_edit())
<script>
    $(document).ready(function () {
        $('.js-mw-embed-wrapper-{{ $params['id'] ?? '' }}').click(function () {

            var frame = $('.js-mw-embed-iframe-{{ $params['id'] ?? '' }}');
            var htmlVideo = $('.js-mw-embed-htmlvideo-{{ $params['id'] ?? '' }}');

            if (frame.length > 0) {
                frame.attr('src', frame.attr('data-src'));
                frame.fadeIn();
            }

            if (htmlVideo.length > 0) {
                htmlVideo.attr('src', htmlVideo.attr('data-src'));
                htmlVideo.fadeIn();
            }

            $(this).css('background-image', 'none');
        });
    });
</script>
@endif
@endif

{{-- AI-1009 / task-2026-05-22 — when no video source is configured the module falls
     back to a demo video. Show an overlay badge so editors know it is a placeholder. --}}
<div class="video-player-container" style="position:relative;">
    {!! $code ?? '' !!}
    @if(!empty($isDemoVideo))
    <div style="position:absolute;bottom:8px;right:8px;background:rgba(0,0,0,0.7);color:#fff;
         font-size:11px;padding:4px 8px;border-radius:4px;pointer-events:none;z-index:10;">
        Demo video — configure source in Settings →
    </div>
    @endif
</div>

{{-- AI-1010 / task-2026-05-22 — pause/play state handler skipped in live-edit mode. --}}
@if(!is_live_edit())
<script>
    $(document).ready(function () {
        $('#{{ $params['id'] ?? '' }} video').on('pause', function () {
            $(this).parent().removeClass('playButton-d-none');
        }).on('play', function () {
            $(this).parent().addClass('playButton-d-none');
        });
    });
</script>
@endif

{{-- AI-966 / task-2026-05-22-424a58 — Video-aware floating toolbar controls in Live Edit.
     Registers mw.quickSettings.video (the dynamic-menu slot for module type "video")
     with Play-Pause and Mute-Toggle buttons. The onTarget callback hides both buttons
     when the module embeds an iframe (YouTube/Vimeo) instead of a native video element,
     because cross-origin iframes do not expose a controllable video API.
     A global guard (_mwVideoQSRegistered) prevents duplicate registration when multiple
     video modules exist on the same canvas page. --}}
@if(is_live_edit())
<script>
(function() {
    'use strict';
    if (window._mwVideoQSRegistered) { return; }
    window._mwVideoQSRegistered = true;

    var PLAY_ICON  = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" /></svg>';
    var PAUSE_ICON = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" /></svg>';
    var MUTE_ICON  = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M17.25 9.75 19.5 12m0 0 2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25m-10.5-6 4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" /></svg>';
    var UNMUTE_ICON= '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="16" height="16"><path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" /></svg>';

    function registerQuickSettings() {
        if (!window.mw) { return; }
        window.mw.quickSettings = window.mw.quickSettings || {};

        window.mw.quickSettings.video = [
            {
                // Play-Pause toggle: only for native video elements (not iframes).
                title: 'Play / Pause preview',
                icon: PLAY_ICON,
                onTarget: function(target, selfNode) {
                    var vid = target.querySelector('video');
                    selfNode.style.display = vid ? '' : 'none';
                    // Swap icon to reflect current state.
                    if (vid) {
                        selfNode.innerHTML = vid.paused ? PLAY_ICON : PAUSE_ICON;
                        selfNode.title = vid.paused ? 'Play preview' : 'Pause preview';
                    }
                },
                action: function(target) {
                    var vid = target.querySelector('video');
                    if (!vid) { return; }
                    if (vid.paused) { vid.play(); } else { vid.pause(); }
                }
            },
            {
                // Mute-Unmute toggle: only for native video elements.
                title: 'Mute / Unmute',
                icon: UNMUTE_ICON,
                onTarget: function(target, selfNode) {
                    var vid = target.querySelector('video');
                    selfNode.style.display = vid ? '' : 'none';
                    if (vid) {
                        selfNode.innerHTML = vid.muted ? UNMUTE_ICON : MUTE_ICON;
                        selfNode.title = vid.muted ? 'Unmute' : 'Mute';
                    }
                },
                action: function(target) {
                    var vid = target.querySelector('video');
                    if (!vid) { return; }
                    vid.muted = !vid.muted;
                }
            }
        ];
    }

    // mw is already initialised in the live-edit canvas before module templates render.
    if (window.mw) {
        registerQuickSettings();
    } else {
        // Safety fallback for edge-cases where the API loads asynchronously.
        document.addEventListener('DOMContentLoaded', registerQuickSettings);
    }
})();
</script>
@endif
