/*
 * Shared init for module skins whose behaviour used to live in a per-instance
 * inline <script> in the Blade template (re-emitted on every module instance).
 *
 * Why here instead of inline: an inline module <script> re-runs when the module
 * is dropped/reloaded in Live Edit (jQuery executes scripts in the injected
 * fragment), but it is duplicated per instance and — for DOMContentLoaded-based
 * ones — never fires for a module dropped AFTER initial load. This file holds a
 * single idempotent copy, run once on ready and again on `moduleLoaded`
 * (fired by mw._ after every reload), so a freshly dropped module initialises too.
 *
 * Pattern:
 *   - per-element setup (sizing, a11y attrs, menu overflow) runs idempotently on
 *     a pass over the document, guarded with a data-attr so re-runs are cheap/safe;
 *   - interaction (the touch tap-toggle) uses ONE delegated document listener, so
 *     it needs no re-binding and covers dynamically added instances for free.
 */
(function () {
    'use strict';

    if (window.__mwModuleSkinsInitAttached) {
        return;
    }
    window.__mwModuleSkinsInitAttached = true;

    // ── ImageRollover (Modules/ImageRollover default skin) ────────────────────
    // Was: DOMContentLoaded pass that sized the overlay image to the default
    // image and, on touch (hover:none), added role/aria/tabindex + a tap/keyboard
    // toggle of `.mw-rollover-active`.
    function initImageRollovers(root) {
        var scope = root && root.querySelectorAll ? root : document;
        var isTouch = window.matchMedia && window.matchMedia('(hover: none)').matches;
        var containers = scope.querySelectorAll('.mw-rollover');
        Array.prototype.forEach.call(containers, function (container) {
            var overlay = container.querySelector('.mw-rollover-overlay');
            if (!overlay) {
                return;
            }
            // Re-apply sizing every pass (idempotent): overlay image matches the
            // default image box so the swap doesn't reflow.
            var defaultImage = container.querySelector('.mw-rollover-default_image');
            var rolloverImage = overlay.querySelector('img');
            if (defaultImage && rolloverImage && defaultImage.offsetWidth) {
                rolloverImage.style.width = defaultImage.offsetWidth + 'px';
                rolloverImage.style.height = defaultImage.offsetHeight + 'px';
            }
            // Touch a11y affordance — set once.
            if (isTouch && !container.hasAttribute('data-mw-rollover-touch')) {
                container.setAttribute('data-mw-rollover-touch', '1');
                container.setAttribute('role', 'button');
                container.setAttribute('aria-label', 'Tap to toggle image');
                container.setAttribute('tabindex', '0');
            }
        });
    }

    // Delegated tap/keyboard toggle for touch devices — one listener, covers all
    // current and future `.mw-rollover` instances (no per-element binding).
    function rolloverTarget(el) {
        return el && el.closest ? el.closest('.mw-rollover') : null;
    }
    document.addEventListener('click', function (e) {
        if (!(window.matchMedia && window.matchMedia('(hover: none)').matches)) {
            return;
        }
        var container = rolloverTarget(e.target);
        if (container && container.querySelector('.mw-rollover-overlay')) {
            container.classList.toggle('mw-rollover-active');
        }
    });
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Enter' || e.key === ' ') {
            if (!(window.matchMedia && window.matchMedia('(hover: none)').matches)) {
                return;
            }
            var container = rolloverTarget(e.target);
            if (container && container.querySelector('.mw-rollover-overlay')) {
                e.preventDefault();
                container.classList.toggle('mw-rollover-active');
            }
        }
    });

    // ── Menu navbar submenu overflow (Modules/Menu navbar skin) ───────────────
    // Was: inline pass that flipped a deep submenu to open leftwards when it would
    // overflow the viewport. Idempotent via the `.navbar-submenu-overflow` marker
    // class, which is removed once a node is processed.
    function initNavbarOverflow(root) {
        var scope = root && root.querySelectorAll ? root : document;
        Array.prototype.forEach.call(scope.querySelectorAll('.navbar-submenu-overflow'), function (node) {
            node.classList.remove('navbar-submenu-overflow');
            Array.prototype.forEach.call(node.querySelectorAll('li'), function (li) {
                li.addEventListener('mouseenter', function () {
                    var ul = this.querySelector('ul');
                    if (ul && !ul._ready && ul !== node) {
                        if (ul.offsetWidth + mw.element(ul).offset().offsetLeft > window.innerWidth) {
                            ul.style.left = 'auto';
                            ul.style.right = '100%';
                        }
                        setTimeout(function () {
                            ul._ready = true;
                            if (ul.offsetWidth + mw.element(ul).offset().offsetLeft > window.innerWidth) {
                                ul.style.left = 'auto';
                                ul.style.right = '100%';
                            }
                        }, 10);
                    }
                });
            });
        });
    }

    function runAll(root) {
        try { initImageRollovers(root); } catch (e) {}
        try { initNavbarOverflow(root); } catch (e) {}
    }

    // Initial pass.
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { runAll(document); });
    } else {
        runAll(document);
    }
    // Re-run after any Live-Edit module drop/reload (mw._ fires this once the
    // reloaded fragment is in the DOM).
    if (window.mw && typeof mw.on === 'function') {
        mw.on('moduleLoaded', function () { runAll(document); });
    }
})();
