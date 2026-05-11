/*
 * AI-263 Phase B3 (cycle-183 2026-05-11) — Masonry → CSS Grid /
 * column-layout vanilla adapter.
 *
 * Replaces the jQuery `masonry.pkgd.js` plugin (used by Pictures
 * module skin-18 / skin-20 / masonry.blade.php and Content module
 * masonry.blade.php) with a CSS-only layout. Module skins continue
 * to call `$(el).masonry({itemSelector, gutter})` unchanged but
 * get a CSS-grid-or-column based masonry layout — no jQuery
 * plugin required.
 *
 * Strategy:
 *   1. Detect support for native `grid-template-rows: masonry`
 *      (Firefox 77+ with flag; Safari 17+; future Chrome). If
 *      supported, apply CSS Grid masonry — perfect rendering.
 *   2. Fall back to CSS multi-column layout (`column-count` +
 *      `column-gap`). Items use `break-inside: avoid` so each
 *      figure stays whole. Works in every browser since 2014.
 *   3. Adapter just sets the styles on the container + items
 *      and stores a marker on the element. No periodic
 *      re-layout needed (CSS handles reflow on resize).
 *
 * The Microweber Pictures `masonry.blade.php` previously ran a
 * `setInterval` every 500ms to re-call `.masonry()` when new
 * items appeared. The adapter doesn't need this — CSS auto-
 * recomputes on DOM mutations. The .masonry() call is now an
 * idempotent style application.
 */

(function () {
    if (typeof window === 'undefined') return;
    if (window.__mwMasonryAdapterLoaded) return;
    window.__mwMasonryAdapterLoaded = true;

    function registerAdapter() {
        if (typeof window.jQuery === 'undefined' || !window.jQuery.fn) {
            return false;
        }
        var $ = window.jQuery;
        if ($.fn.__mwMasonryAdapterInstalled) return true;
        $.fn.__mwMasonryAdapterInstalled = true;

        // Detect native CSS Grid masonry support.
        var supportsGridMasonry = (function () {
            try {
                return CSS && typeof CSS.supports === 'function' &&
                    CSS.supports('grid-template-rows', 'masonry');
            } catch (e) {
                return false;
            }
        })();

        $.fn.masonry = function (options, command) {
            // Handle imperative API: `.masonry('reloadItems')`,
            // `.masonry('layout')`, etc. — CSS-driven layout is
            // automatic so these are no-ops, but we DO need to
            // accept them silently so legacy code doesn't break.
            if (typeof options === 'string') {
                return this; // jQuery chainable, no-op
            }
            var opts = options || {};
            var itemSelector = opts.itemSelector || '.masonry-item';
            var gutter = parseGutter(opts.gutter);
            var columns = parseInt(opts.columnWidth, 10) ?
                Math.max(1, Math.floor(800 / parseInt(opts.columnWidth, 10))) :
                getResponsiveColumns();

            return this.each(function () {
                applyMasonry(this, itemSelector, gutter, columns, supportsGridMasonry);
            });
        };

        return true;
    }

    function parseGutter(g) {
        if (g == null) return 0;
        if (typeof g === 'number') return g;
        var m = String(g).match(/^(\d+)/);
        return m ? parseInt(m[1], 10) : 0;
    }

    function getResponsiveColumns() {
        var w = (typeof window !== 'undefined') ? window.innerWidth : 1024;
        if (w < 480) return 1;
        if (w < 768) return 2;
        if (w < 1200) return 3;
        return 4;
    }

    function applyMasonry(container, itemSelector, gutter, columns, useGrid) {
        if (!container || container.__mwMasonryApplied) return;
        container.__mwMasonryApplied = true;
        container.classList.add('mw-masonry-vanilla');

        if (useGrid) {
            container.style.display = 'grid';
            container.style.gridTemplateColumns = 'repeat(' + columns + ', 1fr)';
            container.style.gridTemplateRows = 'masonry';
            container.style.gap = gutter + 'px';
            // Ensure items don't carry .slick-* width hardcodes.
            var gridItems = container.querySelectorAll(itemSelector);
            gridItems.forEach(function (el) {
                el.style.width = '';
                el.style.float = '';
                el.style.position = '';
            });
            return;
        }

        // CSS multi-column fallback.
        container.style.columnCount = columns;
        container.style.columnGap = gutter + 'px';
        container.style.display = '';
        container.style.position = '';

        var items = container.querySelectorAll(itemSelector);
        items.forEach(function (el) {
            el.style.breakInside = 'avoid';
            el.style.webkitColumnBreakInside = 'avoid';
            el.style.pageBreakInside = 'avoid';
            el.style.marginBottom = gutter + 'px';
            el.style.width = '100%';
            // Override .masonry-item floats from the original
            // jQuery plugin output.
            el.style.float = '';
            el.style.position = '';
        });
    }

    // Listen for viewport resize to reflow column count on
    // multi-column fallback path.
    if (typeof window !== 'undefined') {
        var resizeTimer = null;
        window.addEventListener('resize', function () {
            if (resizeTimer) clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function () {
                var containers = document.querySelectorAll('.mw-masonry-vanilla');
                if (containers.length === 0) return;
                var columns = getResponsiveColumns();
                containers.forEach(function (c) {
                    if (c.style.columnCount !== '') {
                        c.style.columnCount = columns;
                    } else if (c.style.gridTemplateColumns) {
                        c.style.gridTemplateColumns = 'repeat(' + columns + ', 1fr)';
                    }
                });
            }, 200);
        });
    }

    if (!registerAdapter()) {
        window.__mwRegisterJqueryExtensions = window.__mwRegisterJqueryExtensions || [];
        window.__mwRegisterJqueryExtensions.push(registerAdapter);
    }
})();
