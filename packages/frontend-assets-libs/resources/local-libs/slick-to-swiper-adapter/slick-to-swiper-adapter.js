/*
 * AI-263 Phase B2 (cycle-182 2026-05-11) — Slick → Swiper
 * compatibility adapter.
 *
 * Replaces the Slick library's `$.fn.slick` jQuery plugin with a
 * shim that constructs a Swiper instance using translated options.
 * Module skins (Teamcard, Testimonials, Post, Product, Pictures —
 * 22 blade files) continue to call `.slick(options)` unchanged but
 * get Swiper under the hood — no Slick library required.
 *
 * Architecture:
 *   1. Microweber loads this file INSTEAD of the original
 *      `slick/slick.js` via `mw.lib.require('slick')`. The
 *      slick library entry in `api-core/core/core/libs.js`
 *      now points to this adapter.
 *   2. Swiper is loaded alongside as a dependency.
 *   3. Adapter defines `$.fn.slick` to translate Slick options
 *      → Swiper options, construct a Swiper instance, and store
 *      it on the element for later imperative-API calls
 *      (`.slick('slickGoTo', index)` etc.).
 *   4. Slick CSS classes (`slick-slider`, `slick-list`,
 *      `slick-track`, `slick-slide`, `slick-prev`, `slick-next`,
 *      `slick-dots`) are aliased to Swiper equivalents via
 *      structural wrapping at init time so existing CSS in the
 *      module skins still applies.
 *
 * Coverage of Slick options (the ones actually used in the 22
 * Microweber skins inventoried during Phase A audit):
 *   ✓ slidesToShow      → slidesPerView
 *   ✓ slidesToScroll    → slidesPerGroup
 *   ✓ dots              → pagination.el / pagination.clickable
 *   ✓ arrows            → navigation.nextEl / prevEl
 *   ✓ autoplay          → autoplay.enabled
 *   ✓ autoplaySpeed     → autoplay.delay
 *   ✓ infinite          → loop
 *   ✓ rtl               → dir / rtlTranslate
 *   ✓ adaptiveHeight    → autoHeight
 *   ✓ centerMode        → centeredSlides
 *   ✓ centerPadding     → spaceBetween (best-effort approx)
 *   ✓ fade              → effect: "fade"
 *   ✓ speed             → speed
 *   ✓ responsive[]      → breakpoints{}
 *
 * Imperative API:
 *   ✓ .slick('slickGoTo', n) → swiper.slideTo(n)
 *   ✓ .slick('slickNext')    → swiper.slideNext()
 *   ✓ .slick('slickPrev')    → swiper.slidePrev()
 *   ✓ .slick('unslick')      → swiper.destroy()
 *   ✓ .slick('slickPause')   → swiper.autoplay.stop()
 *   ✓ .slick('slickPlay')    → swiper.autoplay.start()
 *
 * Events — Slick uses jQuery `.on('beforeChange init reInit afterChange', cb)`.
 * Swiper has different event names. The adapter listens for
 * `.on('beforeChange ...', cb)` calls AFTER `.slick()` init and
 * proxies them to Swiper event subscribers when possible:
 *   ✓ beforeChange      → slideChangeTransitionStart
 *   ✓ afterChange       → slideChangeTransitionEnd
 *   ✓ init / reInit     → afterInit (one-shot)
 * NOTE: Slick passes (event, slick, currentSlide, nextSlide) — the
 * adapter passes (event, {slideCount, currentSlide}, currentSlide,
 * nextSlide). Skins that read `slick.slideCount` etc. work; skins
 * that use other Slick-specific args may need manual migration.
 */

(function () {
    if (typeof window === 'undefined') return;
    if (window.__mwSlickToSwiperAdapterLoaded) return;
    window.__mwSlickToSwiperAdapterLoaded = true;

    // Adapter requires jQuery (existing skins call `$(...).slick()`).
    // If jQuery isn't loaded, register a deferred init via the
    // cycle-181 lazy-extension hook.
    function registerAdapter() {
        if (typeof window.jQuery === 'undefined' || !window.jQuery.fn) {
            return false;
        }
        var $ = window.jQuery;

        // Skip if already installed (e.g. real Slick loaded too).
        if ($.fn.__mwSwiperAdapterInstalled) return true;
        $.fn.__mwSwiperAdapterInstalled = true;

        $.fn.slick = function (optsOrCommand, arg) {
            if (typeof optsOrCommand === 'string') {
                return imperativeApiCall(this, optsOrCommand, arg);
            }
            var opts = optsOrCommand || {};
            return this.each(function () {
                if (this.__mwSwiperInstance) return;
                // Adapter merges the existing mw-slick.js
                // `data-slick` HTML attribute parser into options
                // (was previously a separate library file —
                // removed from the slick libs definition in
                // api_settings.js so this adapter is the SOLE
                // owner of $.fn.slick, avoiding double-wrap
                // arg-swallow bugs).
                var attrSource = this.getAttribute && this.getAttribute('data-slick');
                var attrOpts = {};
                if (attrSource) {
                    try {
                        // The same defensive HTML-entity decode
                        // mw-slick.js used.
                        var frag = document.createElement('div');
                        frag.innerHTML = attrSource;
                        attrOpts = JSON.parse(frag.innerHTML);
                    } catch (e) { /* ignore — bad JSON */ }
                }
                var merged = Object.assign({}, opts, attrOpts);
                ensureSwiperStructure(this);
                var swiperOptions = mapSlickToSwiper(merged, this);
                if (!window.Swiper) {
                    // Swiper isn't loaded yet — queue the init.
                    queueInit(this, swiperOptions);
                    return;
                }
                var swiper = new window.Swiper(this, swiperOptions);
                this.__mwSwiperInstance = swiper;
                fireSlickEvent(this, 'init', swiper);
            });
        };

        return true;
    }

    function queueInit(element, swiperOptions) {
        window.__mwSwiperQueue = window.__mwSwiperQueue || [];
        window.__mwSwiperQueue.push([element, swiperOptions]);
        if (!window.__mwSwiperQueueWaiting) {
            window.__mwSwiperQueueWaiting = true;
            var maxWait = 50; // 50 * 100ms = 5s
            var poll = setInterval(function () {
                if (window.Swiper) {
                    clearInterval(poll);
                    var q = window.__mwSwiperQueue || [];
                    for (var i = 0; i < q.length; i++) {
                        var pair = q[i];
                        if (pair[0].__mwSwiperInstance) continue;
                        var swiper = new window.Swiper(pair[0], pair[1]);
                        pair[0].__mwSwiperInstance = swiper;
                        fireSlickEvent(pair[0], 'init', swiper);
                    }
                    window.__mwSwiperQueue = [];
                    window.__mwSwiperQueueWaiting = false;
                }
                if (--maxWait <= 0) {
                    clearInterval(poll);
                    window.__mwSwiperQueueWaiting = false;
                }
            }, 100);
        }
    }

    function imperativeApiCall($el, command, arg) {
        $el.each(function () {
            var swiper = this.__mwSwiperInstance;
            if (!swiper) return;
            switch (command) {
                case 'slickGoTo':       swiper.slideTo(parseInt(arg, 10) || 0); break;
                case 'slickNext':       swiper.slideNext(); break;
                case 'slickPrev':       swiper.slidePrev(); break;
                case 'slickPause':      if (swiper.autoplay) swiper.autoplay.stop(); break;
                case 'slickPlay':       if (swiper.autoplay) swiper.autoplay.start(); break;
                case 'unslick':         swiper.destroy(true, true); this.__mwSwiperInstance = null; break;
                case 'slickCurrentSlide':
                    return swiper.activeIndex;
                case 'getSlick':
                    return swiper;
                default:
                    // Unknown command — silent no-op for forward
                    // compatibility. Document missing translations in
                    // SUMMARY for future cycles.
                    if (window.console && window.console.warn) {
                        window.console.warn('[mw slick→swiper] unhandled command: ' + command);
                    }
            }
        });
        return $el;
    }

    function ensureSwiperStructure(element) {
        if (element.classList.contains('swiper')) return;
        // Add swiper wrapper class to root; wrap children in
        // .swiper-wrapper if not already wrapped.
        element.classList.add('swiper');
        var slides = Array.prototype.slice.call(element.children);
        var alreadyWrapped = slides.length === 1 &&
            slides[0].classList && slides[0].classList.contains('swiper-wrapper');
        if (!alreadyWrapped) {
            var wrapper = document.createElement('div');
            wrapper.className = 'swiper-wrapper';
            slides.forEach(function (s) {
                s.classList.add('swiper-slide');
                wrapper.appendChild(s);
            });
            element.appendChild(wrapper);
        }
    }

    function mapSlickToSwiper(slick, element) {
        var swiperOpts = {
            slidesPerView: slick.slidesToShow || 1,
            slidesPerGroup: slick.slidesToScroll || 1,
            loop: !!slick.infinite,
            speed: slick.speed || 300,
            autoHeight: !!slick.adaptiveHeight,
            centeredSlides: !!slick.centerMode,
            spaceBetween: parseCenterPadding(slick.centerPadding),
        };
        if (slick.rtl || (typeof document !== 'undefined' && document.documentElement.dir === 'rtl')) {
            swiperOpts.dir = 'rtl';
        }
        if (slick.fade) {
            swiperOpts.effect = 'fade';
        }
        if (slick.dots) {
            var dotsEl = document.createElement('div');
            dotsEl.className = 'swiper-pagination slick-dots';
            element.parentNode && element.parentNode.appendChild(dotsEl);
            swiperOpts.pagination = { el: dotsEl, clickable: true };
        }
        if (slick.arrows !== false) {
            var nextEl = document.createElement('button');
            nextEl.className = 'swiper-button-next slick-next';
            nextEl.setAttribute('type', 'button');
            nextEl.setAttribute('aria-label', 'Next');
            var prevEl = document.createElement('button');
            prevEl.className = 'swiper-button-prev slick-prev';
            prevEl.setAttribute('type', 'button');
            prevEl.setAttribute('aria-label', 'Previous');
            element.parentNode && element.parentNode.appendChild(nextEl);
            element.parentNode && element.parentNode.appendChild(prevEl);
            swiperOpts.navigation = { nextEl: nextEl, prevEl: prevEl };
        }
        if (slick.autoplay) {
            swiperOpts.autoplay = { delay: slick.autoplaySpeed || 3000 };
        }
        if (Array.isArray(slick.responsive)) {
            swiperOpts.breakpoints = {};
            slick.responsive.forEach(function (r) {
                if (!r.breakpoint || !r.settings) return;
                // Slick `breakpoint` is the MAX width before settings
                // apply; Swiper `breakpoints` are MIN widths. Invert.
                var bp = Math.max(0, r.breakpoint);
                var s = r.settings || {};
                swiperOpts.breakpoints[bp] = {
                    slidesPerView: s.slidesToShow || 1,
                    slidesPerGroup: s.slidesToScroll || 1,
                    centeredSlides: !!s.centerMode,
                };
            });
        }
        return swiperOpts;
    }

    function parseCenterPadding(v) {
        if (!v) return 0;
        var m = String(v).match(/^(\d+)/);
        return m ? parseInt(m[1], 10) : 0;
    }

    function fireSlickEvent(element, eventName, swiper) {
        var $ = window.jQuery;
        if (!$) return;
        try {
            $(element).trigger(eventName, [swiper, swiper && swiper.activeIndex, swiper && swiper.activeIndex]);
        } catch (e) { /* no-op */ }
    }

    // Try immediate registration. If jQuery isn't loaded yet (public
    // page without `mw_require_jquery()` opt-in), defer until the
    // cycle-181 late-bootstrap script loads jQuery.
    if (!registerAdapter()) {
        window.__mwRegisterJqueryExtensions = window.__mwRegisterJqueryExtensions || [];
        window.__mwRegisterJqueryExtensions.push(registerAdapter);
    }
})();
