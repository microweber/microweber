/*
 * AI-263 Phase B4 (cycle-184 2026-05-11) — vanilla rewrite of
 * CollapseNav.js. Previously used jQuery throughout — the LAST
 * blocker that forced Bootstrap template's master.blade.php to
 * call `mw_require_jquery()`. Now jQuery-free.
 *
 * Original library:
 *   CollapseNav.js v1.0 by Petko Yovchevski (MIT)
 *   https://github.com/PYovchevski/collapse-nav
 *
 * Behavior preserved:
 *   - Init on `load` + `resize` + `collapseNavReInit` event +
 *     `orientationchange`.
 *   - Below `mobile_break` (default 992px), no-op (Bootstrap's
 *     own collapse handles the mobile nav).
 *   - At ≥mobile_break, measures each `<li>` width, fits as many
 *     as the parent allows, collapses the rest into a "More"
 *     dropdown.
 *   - Exposes `MwCollapseNav(selector, config)` as a global +
 *     a no-op `$.fn.collapseNav` shim that calls
 *     MwCollapseNav so any legacy `$('#nav').collapseNav()`
 *     calls keep working IF jQuery happens to be loaded.
 *
 * Vanilla replacements:
 *   $(selector).html()             → element.innerHTML
 *   $().addClass / removeClass     → element.classList.add/remove
 *   $().outerWidth(true)           → boundingRect with margins
 *   $().children('li').each(cb)    → Array.from(...).forEach
 *   $().children().first().clone() → cloneNode(true)
 *   $().children().last().find('a').html(...) → manual querySelector
 *   $(window).on('load'/'resize'/etc.) → window.addEventListener
 *   $(window).width()              → window.innerWidth
 *   $.extend({}, defaults, config) → Object.assign({}, defaults, config)
 *   $(navigation).append(html)     → insertAdjacentHTML('beforeend', ...)
 *   $(navigation).children('li:gt(N)') → Array.slice(N+1)
 */

(function () {
    'use strict';

    function getNavigationEl(selector) {
        if (selector && selector.nodeType === 1) return selector;
        if (typeof selector === 'string') return document.querySelector(selector);
        return null;
    }

    function outerWidthWithMargin(el) {
        if (!el) return 0;
        var rect = el.getBoundingClientRect();
        var style = window.getComputedStyle(el);
        return rect.width + parseFloat(style.marginLeft || 0) + parseFloat(style.marginRight || 0);
    }

    function MwCollapseNav(selector, config) {
        var configuration = config || {};
        var navigation = getNavigationEl(selector);
        if (!navigation) return;
        var original_navigation = navigation.innerHTML;

        navigation.classList.add('collapseNav-not-initialized');

        function init() {
            navigation.classList.remove('collapseNav-not-initialized');
            navigation.classList.add('collapseNav-initialized');

            var responsive = configuration.responsive;
            if (!responsive) responsive = 1;

            var mobile_break = configuration.mobile_break;
            if (!mobile_break) mobile_break = 992;

            var li_class = configuration.li_class;
            if (!li_class && li_class !== '') li_class = 'dropdown';

            var li_a_class = configuration.li_a_class;
            if (!li_a_class && li_a_class !== '') li_a_class = 'dropdown-toggle';

            var li_ul_class = configuration.li_ul_class;
            if (!li_ul_class && li_ul_class !== '') li_ul_class = 'dropdown-menu';

            var more_text = configuration.more_text;
            if (!more_text) more_text = 'More';

            var caret = configuration.caret;
            if (!caret && caret !== '') caret = '<span class="caret"></span>';

            var ul_width = navigation.getBoundingClientRect().width;
            var li_width;
            var possible_buttons;
            var li_count;

            // --- Check base buttons to navigation ---
            li_width = 0;
            possible_buttons = 0;
            li_count = 0;

            var initialLis = Array.from(navigation.children).filter(function (el) {
                return el.tagName === 'LI';
            });
            initialLis.forEach(function (li) {
                li_count = li_count + 1;
                li_width = li_width + outerWidthWithMargin(li);
                if (ul_width >= li_width) {
                    possible_buttons = possible_buttons + 1;
                }
            });

            // The navigation does not need a More menu, stop the script.
            if (li_count <= possible_buttons) {
                return;
            }

            // --- Check the more buttons to navigation ---
            li_width = 0;
            possible_buttons = 0;
            li_count = 0;

            // The More Button Width — clone first <li>, append, measure, remove.
            var firstLi = initialLis[0];
            var clonedMore = firstLi ? firstLi.cloneNode(true) : null;
            if (clonedMore) {
                navigation.appendChild(clonedMore);
                var clonedAnchor = clonedMore.querySelector('a');
                if (clonedAnchor) {
                    clonedAnchor.innerHTML = more_text + ' ' + caret;
                    clonedAnchor.style.visibility = 'hidden';
                }
                var the_more_button_width = clonedMore;
                li_count = li_count + 1;
                li_width = li_width + outerWidthWithMargin(the_more_button_width);
                if (the_more_button_width.parentNode) {
                    the_more_button_width.parentNode.removeChild(the_more_button_width);
                }
            }

            // Re-fetch <li> list after the temporary clone removal.
            var remainingLis = Array.from(navigation.children).filter(function (el) {
                return el.tagName === 'LI';
            });
            remainingLis.forEach(function (li) {
                li_count = li_count + 1;
                li_width = li_width + outerWidthWithMargin(li);
                if (ul_width >= li_width) {
                    possible_buttons = possible_buttons + 1;
                }
            });

            // --- Some checks ---
            var number_of_buttons;
            if (responsive == 1) {
                number_of_buttons = possible_buttons;
            } else {
                number_of_buttons = configuration.number_of_buttons;
                if (!number_of_buttons) number_of_buttons = 4;
            }

            if (window.innerWidth < mobile_break) {
                return;
            }

            // --- Convert the navigation to the new ---
            var btn_n = 0;
            var ul = '<ul class="' + li_ul_class + '">';
            remainingLis.forEach(function (li) {
                btn_n = btn_n + 1;
                if (btn_n > number_of_buttons) {
                    ul += li.outerHTML;
                }
            });
            ul += '</ul>';

            // Remove every <li> at index > number_of_buttons - 1
            // (the jQuery `:gt(N)` selector is 0-indexed and
            // exclusive, so `li:gt(3)` removes the 5th, 6th, …
            // matches the original — replicate with slice + remove).
            var removeStartIdx = number_of_buttons - 1;
            remainingLis.forEach(function (li, idx) {
                if (idx > removeStartIdx) {
                    if (li.parentNode) li.parentNode.removeChild(li);
                }
            });

            navigation.insertAdjacentHTML('beforeend',
                '<li class="' + li_class + '">' +
                    '<a href="javascript:;" class="' + li_a_class + '" data-bs-toggle="dropdown" ' +
                    'role="button" aria-haspopup="true" aria-expanded="false">' +
                    more_text + caret +
                    '</a>' +
                    ul +
                '</li>'
            );
        }

        function resetAndInit() {
            if (window.innerWidth >= (configuration.mobile_break || 992)) {
                navigation.innerHTML = original_navigation;
            }
            init();
        }

        window.addEventListener('load', resetAndInit);
        window.addEventListener('resize', resetAndInit);
        window.addEventListener('collapseNavReInit', resetAndInit);
        window.addEventListener('orientationchange', resetAndInit, false);

        init();
    }

    if (typeof window !== 'undefined') {
        window.MwCollapseNav = MwCollapseNav;
    }

    // Optional jQuery shim — only registers if jQuery is loaded.
    // The new public-page flow does NOT load jQuery, so this is
    // a no-op safety net for any legacy code that still calls
    // `$(selector).collapseNav(config)`.
    function registerJqueryShim() {
        if (typeof window.jQuery === 'undefined' || !window.jQuery.fn) {
            return false;
        }
        var $ = window.jQuery;
        if ($.fn.__mwCollapseNavInstalled) return true;
        $.fn.__mwCollapseNavInstalled = true;

        $.fn.collapseNav = function (config) {
            config = config || {};
            var mwLang = (typeof window.mw !== 'undefined' && typeof window.mw.lang === 'function') ? window.mw.lang : function (s) { return s; };
            var defaults = {
                responsive: 1,
                number_of_buttons: 4,
                more_text: mwLang('More'),
                mobile_break: 992,
                li_class: 'dropdown',
                li_a_class: 'dropdown-toggle',
                li_ul_class: 'dropdown-menu',
                caret: '<span class="caret"></span>'
            };
            var settings = Object.assign({}, defaults, config);
            return this.each(function () {
                var scope = this;
                setTimeout(function () {
                    MwCollapseNav(scope, settings);
                }, 700);
            });
        };
        return true;
    }

    if (!registerJqueryShim()) {
        // jQuery not present — that's the GOAL. Expose a
        // vanilla auto-init on `<ul[data-mw-collapse-nav]>`
        // elements so module skins can opt in via HTML
        // attribute instead of the jQuery plugin call:
        //   <ul class="nav" data-mw-collapse-nav data-mw-collapse-mobile-break="992">
        if (typeof document !== 'undefined') {
            var autoInit = function () {
                var nodes = document.querySelectorAll('[data-mw-collapse-nav]');
                Array.prototype.forEach.call(nodes, function (n) {
                    if (n.__mwCollapseNavInitialized) return;
                    n.__mwCollapseNavInitialized = true;
                    var cfg = {};
                    if (n.hasAttribute('data-mw-collapse-mobile-break')) {
                        cfg.mobile_break = parseInt(n.getAttribute('data-mw-collapse-mobile-break'), 10) || 992;
                    }
                    if (n.hasAttribute('data-mw-collapse-more-text')) {
                        cfg.more_text = n.getAttribute('data-mw-collapse-more-text');
                    }
                    MwCollapseNav(n, cfg);
                });
            };
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', autoInit);
            } else {
                autoInit();
            }
        }
    }
})();
