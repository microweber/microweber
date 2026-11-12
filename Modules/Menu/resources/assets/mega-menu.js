/* Mega menu — keep the dropdown panel inside the viewport.

   The panel is absolutely positioned under its parent <li>. For menus that sit
   on the right side of the header, a left-aligned panel overflows the right
   edge, so on open we measure and flip it to right-aligned (and, if that would
   clip the left edge, clamp it into the viewport). Pure CSS can't know which
   side the parent is on, so this runs on hover/focus. */
(function () {
    'use strict';

    var GUTTER = 12;

    function reposition(panel) {
        if (!panel) {
            return;
        }
        // Reset to the CSS default before measuring.
        panel.style.left = '';
        panel.style.right = '';

        var vw = document.documentElement.clientWidth;
        var rect = panel.getBoundingClientRect();

        if (rect.right > vw - GUTTER) {
            // Flip to right-aligned under the parent.
            panel.style.left = 'auto';
            panel.style.right = '0';

            var flipped = panel.getBoundingClientRect();
            if (flipped.left < GUTTER) {
                // Still clipping the left edge — pin to the viewport gutter.
                var li = panel.offsetParent || panel.parentElement;
                var liLeft = li ? li.getBoundingClientRect().left : 0;
                panel.style.right = 'auto';
                panel.style.left = (GUTTER - liLeft) + 'px';
            }
        }
    }

    function bind(root) {
        var panels = (root || document).querySelectorAll('.mega-menu-content');
        Array.prototype.forEach.call(panels, function (panel) {
            var li = panel.parentElement;
            if (!li || li.__mwMegaBound) {
                return;
            }
            li.__mwMegaBound = true;
            li.addEventListener('mouseenter', function () { reposition(panel); });
            li.addEventListener('focusin', function () { reposition(panel); });
        });
    }

    function init() { bind(document); }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
    window.addEventListener('load', init);
    window.addEventListener('resize', function () {
        document.querySelectorAll('.mega-menu-content').forEach(function (p) {
            if (getComputedStyle(p).display !== 'none') { reposition(p); }
        });
    });
})();
