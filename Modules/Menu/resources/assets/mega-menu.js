/* Mega menu — single-open + keep the dropdown panel inside the viewport.

   Two jobs:
   1) Only ONE mega panel may be open at a time. The CSS reveals a panel on
      li:hover / li:focus-within, but a wide panel can overlap a neighbouring
      item, and a clicked trigger keeps its panel open via :focus-within — so
      hovering a second item showed BOTH panels, overlapping. On enter/focus we
      therefore force every OTHER panel closed (inline display:none beats the CSS
      :hover reveal) and release a sticky focus on the one we hide.
   2) The panel is absolutely positioned under its parent <li>; for items on the
      right of the header a left-aligned panel overflows, so on open we measure
      and flip it right-aligned (clamping into the viewport if needed). Pure CSS
      can't know which side the parent is on, so this runs on open. */
(function () {
    'use strict';

    var GUTTER = 12;
    var CLOSE_DELAY = 160; // ms — buffers the gap between the item and its panel

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

    function panelOf(li) {
        return li ? li.querySelector(':scope > .mega-menu-content') : null;
    }

    function megaLis() {
        var out = [];
        document.querySelectorAll('.mega-menu-content').forEach(function (p) {
            if (p.parentElement && out.indexOf(p.parentElement) === -1) {
                out.push(p.parentElement);
            }
        });
        return out;
    }

    function closeLi(li) {
        var p = panelOf(li);
        if (p) { p.style.display = 'none'; }
    }

    // Open this item's panel and force every OTHER mega panel closed, so only
    // one is ever visible. Inline display overrides the CSS :hover/:focus-within
    // reveal; blurring a focused control inside a hidden panel clears its
    // :focus-within so it cannot pop back open.
    function openOnly(li) {
        if (li.__mmTimer) { clearTimeout(li.__mmTimer); li.__mmTimer = null; }
        megaLis().forEach(function (other) {
            var p = panelOf(other);
            if (!p) { return; }
            if (other === li) {
                p.style.display = 'block';
                reposition(p);
            } else {
                p.style.display = 'none';
                if (other.contains(document.activeElement) && document.activeElement !== document.body) {
                    try { document.activeElement.blur(); } catch (e) {}
                }
            }
        });
    }

    function scheduleClose(li) {
        if (li.__mmTimer) { clearTimeout(li.__mmTimer); }
        li.__mmTimer = setTimeout(function () { closeLi(li); li.__mmTimer = null; }, CLOSE_DELAY);
    }

    function bind(root) {
        var panels = (root || document).querySelectorAll('.mega-menu-content');
        Array.prototype.forEach.call(panels, function (panel) {
            var li = panel.parentElement;
            if (!li || li.__mwMegaBound) {
                return;
            }
            li.__mwMegaBound = true;
            li.addEventListener('mouseenter', function () { openOnly(li); });
            li.addEventListener('focusin', function () { openOnly(li); });
            li.addEventListener('mouseleave', function () { scheduleClose(li); });
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
