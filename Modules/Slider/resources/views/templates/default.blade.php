@php
/*
type: layout
name: Swiper Default
description: Modern slider with Swiper.js integration
*/
@endphp



<div id="js-slider-{{ $params['id'] ?? 'default' }}" class="slider_v2-default swiper">
    <div class="swiper-wrapper">
        @if($slides && $slides->count() > 0)
            @foreach($slides as $slide)
                <style>
                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }}  {
                        text-align: {{ $slide->settings['alignItems'] ?? 'center' }};
                    }

                    /* audit-test 2026-05-07 PM TASK-001 (TICKET-AV Option B):
                       was background-size/position on a div, now object-fit/
                       object-position on a real <img>. Same visual fit
                       (cover, centered) with no CSS-injection sink. */
                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }} .js-slide-image-swiper-module {
                        object-fit: cover;
                        object-position: center center;
                        width: 100%;
                        height: 100%;
                        position: absolute;
                        z-index: -1;
                        @if(isset($slide->settings['imageBackgroundFilter']))
                            @switch($slide->settings['imageBackgroundFilter'])
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

                    #js-slider-{{ $params['id'] ?? 'default' }} .module-slider-header-section-title {
                        color: {{ $slide->settings['titleColor'] ?? '#000000' }};
                        font-size: {{ $slide->settings['titleFontSize'] ?? '52' }}px;
                        @media screen and (max-width: 991px) {
                            font-size: min(3rem, {{ $slide->settings['titleFontSize'] ?? '52' }}px);
                        }
                        @media screen and (max-width: 600px) {
                            font-size: min(2.5rem, {{ $slide->settings['titleFontSize'] ?? '36' }}px)!important;
                        }
                        @media screen and (max-width: 400px) {
                            font-size: min(2rem, {{ $slide->settings['titleFontSize'] ?? '24' }}px)!important;
                        }
                        overflow-wrap: break-word;
                    }

                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }} .module-slider-header-section-p {
                        color: {{ $slide->settings['descriptionColor'] ?? '#666666' }};
                        font-size: {{ $slide->settings['descriptionFontSize'] ?? '16' }}px;
                    }

                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }} .slider-button {
                        display: inline-block;
                        padding: 8px 20px;
                        background-color: {{ $slide->settings['buttonBackgroundColor'] ?? 'var(--mw-btn-background-color)' }};
                        color: {{ $slide->settings['buttonTextColor'] ?? '#ffffff' }};
                        border: 1px solid {{ $slide->settings['buttonBorderColor'] ?? 'transparent' }};
                        font-size: {{ $slide->settings['buttonFontSize'] ?? '16' }}px;
                        text-decoration: none;
                        transition: all 0.3s ease;
                    }

                    #js-slider-{{ $params['id'] }} .swiper-slide-{{ $slide->id }} .slider-button:hover {
                        background-color: {{ $slide->settings['buttonBackgroundHoverColor'] ?? '#0056b3' }};
                        color: {{ $slide->settings['buttonTextHoverColor'] ?? '#ffffff' }};
                        text-decoration: none;
                    }

                    .js-slide-elements-{{ $slide->id }} {
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        align-items: center;
                    }
                </style>






                <div class="swiper-slide swiper-slide-{{ $slide->id }}">
                    {{-- audit-test 2026-05-07 PM TASK-001 (TICKET-AV Option B):
                         was `<div style="background-image: url('{{thumbnail()}}')">`,
                         a CSS-injection sink (Blade HTML-escape covers HTML-attr
                         context but the browser HTML-decodes BEFORE the CSS parser
                         reads the value, so admin URLs containing `'`/`)` could break
                         out of url(...) into arbitrary CSS rules — page-defacement /
                         phishing-overlay primitive). Migrated to a real `<img>`:
                         `src=` accepts URL content with no parser-context confusion;
                         bonus alt text for SR users + lazy/eager loading for LCP +
                         srcset for responsive bandwidth. Closes Slider audit findings
                         #3 (a11y) + #6 (perf) + the TICKET-AV CSS-injection vector
                         in one move. --}}
                    <img class="js-slide-image-swiper-module js-slide-image-{{ $slide->id }}"
                         src="{{ thumbnail($slide->media, 800) }}"
                         srcset="{{ thumbnail($slide->media, 400) }} 400w, {{ thumbnail($slide->media, 800) }} 800w, {{ thumbnail($slide->media, 1200) }} 1200w"
                         sizes="100vw"
                         alt="{{ $slide->name ?? '' }}"
                         loading="{{ $loop->first ? 'eager' : 'lazy' }}"
                         decoding="async">


                    {{-- audit-test 2026-05-07 Slider audit finding #5 (TICKET-AH partial):
                         100vh on iOS Safari includes the URL bar so the slide
                         height jumps when the user scrolls. 100dvh (dynamic
                         viewport height) accounts for the URL bar and stays
                         stable. Stacked declaration: pre-dvh browsers see only
                         the first rule (100vh fallback); dvh-supporting
                         browsers (iOS 15.4+ / Chrome 108+ / Firefox 101+) see
                         both and the second wins. --}}
                    <div class="js-slide-elements-{{ $slide->id }}" style="height: calc(100vh - 100px); height: calc(100dvh - 100px);">
                        <div class="mb-2">
                            <h3 class="module-slider-header-section-title js-slide-title-{{ $slide->id }}">
                                {{ $slide->name }}
                            </h3>
                        </div>
                        <div>
                            <p class="module-slider-header-section-p js-slide-description-{{ $slide->id }}">
                                {{ $slide->description }}
                            </p>
                        </div>

                        @if($slide->button_text)
                            {{-- audit-test 2026-05-07 Slider audit finding #2 (SECURITY HIGH)
                                 + post-merge follow-up #3 (TICKET-AS):
                                 - Cycle-27: protocol allow-list blocks javascript:/data:.
                                 - Follow-up #3: leading `/` also matched `//attacker.com/path`
                                   (protocol-relative URL — browser resolves to current scheme,
                                   origin changes to attacker.com). Tightened to `/(?!/)` so
                                   only SINGLE-leading-slash root-relative paths pass. --}}
                            @php
                                $safeLink = '';
                                if (!empty($slide->link) && preg_match('#^(https?://|/(?!/)|\#|mailto:|tel:)#i', $slide->link)) {
                                    $safeLink = $slide->link;
                                }
                            @endphp
                            <div class="mt-5">
                                <a href="{{ $safeLink }}" class="slider-button btn btn-primary js-slide-button-{{ $slide->id }}">
                                    {{ $slide->button_text }}
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- audit-test 2026-05-07 Slider audit finding #1 (P0 BLOCKER):
         prev/next were <div>s with addEventListener('click') — not focusable,
         not keyboard-activatable. Converted to real <button>s with aria-label
         so keyboard / screen-reader users can advance slides. The JS-side
         Swiper a11y/keyboard module wiring (auto aria-hidden on inactive
         slides, arrow-key navigation) is deferred under TICKET-AH because
         it touches slider-v2.js + bundled artifacts. --}}
    <div id="js-slide-pagination-{{ $params['id'] ?? 'default' }}" class="swiper-pagination"></div>
    <button type="button" id="js-slide-pagination-previous-{{ $params['id'] ?? 'default' }}" class="mw-slider-v2-buttons-slide mw-slider-v2-button-prev" aria-label="{{ __('Previous slide') }}"></button>
    <button type="button" id="js-slide-pagination-next-{{ $params['id'] ?? 'default' }}" class="mw-slider-v2-buttons-slide mw-slider-v2-button-next" aria-label="{{ __('Next slide') }}"></button>
</div>

<script>
if (!window.SliderV2) {
    mw.require('{{ asset('modules/slider/js/slider-v2.js') }}',true)
}
</script>


<script>



{{-- task-2026-05-22-ce249e / AI-918 — build SliderV2 config from module settings
     (autoplay, autoplay_speed, show_arrows, show_dots) instead of hardcoded values. --}}
;(function(){
     const slider = new SliderV2('#js-slider-{{ $params['id'] ?? 'default' }}', {
        loop: true,
        @if(!empty($sliderAutoplay))
        autoplay: true,
        delay: {{ (int)($sliderAutoplaySpeed ?? 3000) }},
        @endif
        @if(!empty($sliderShowDots))
        pagination: {
            el: '#js-slide-pagination-{{ $params['id'] ?? 'default' }}',
            clickable: true
        },
        @endif
        @if(!empty($sliderShowArrows))
        navigation: {
            nextEl: '#js-slide-pagination-next-{{ $params['id'] ?? 'default' }}',
            prevEl: '#js-slide-pagination-previous-{{ $params['id'] ?? 'default' }}'
        },
        @endif
    });


    document.querySelector('#js-slide-pagination-next-{{ $params['id'] ?? 'default' }}').addEventListener('click', () => {
        slider.driverInstance.slideNext();
    });
   document.querySelector('#js-slide-pagination-previous-{{ $params['id'] ?? 'default' }}').addEventListener('click', () => {
        slider.driverInstance.slidePrev();
    });

})();




</script>


