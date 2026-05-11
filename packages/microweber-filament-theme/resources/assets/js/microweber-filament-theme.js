


// JavaScript to toggle dropdown visibility
document.addEventListener('DOMContentLoaded', function () {


    const dropdown = document.querySelector('.dropdown');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    if (!dropdown || !dropdownMenu) {
        return;
    }

    dropdown.addEventListener('click', function () {
        dropdownMenu.classList.toggle('show');
    });

    // Close the dropdown if the user clicks outside of it
    document.addEventListener('click', function (e) {
        if (!dropdown.contains(e.target) && !dropdownMenu.contains(e.target)) {
            dropdownMenu.classList.remove('show');
        }
    });


});

document.addEventListener('DOMContentLoaded', function () {


    // Function to add bottom effect spans
    function addBottomEffect() {
        // Select all inputs within .form-control-live-edit-label-wrapper and .fi-input-wrp
        const inputs = document.querySelectorAll('.form-control-live-edit-label-wrapper .form-control-live-edit-input:not(.form-select, .form-control-input-range-slider), .fi-input-wrp .fi-input, .fi-input-wpr .fi-select-input');

        // Loop through each input element
        inputs.forEach(input => {
            // Check if a span with the class already exists
            if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('form-control-live-edit-bottom-effect')) {
                // Create the span element
                const span = document.createElement('span');
                span.className = 'form-control-live-edit-bottom-effect';

                // Insert the span element after the input elementЕ
                input.insertAdjacentElement('afterend', span);
            }
        });
    }

// Run the function to add the bottom effect
    addBottomEffect();

// Create a MutationObserver instance
    const observer = new MutationObserver(addBottomEffect);

// Start observing the document with the configured parameters
    observer.observe(document.body, { childList: true, subtree: true });

});

window.addEventListener('livewire:init', function () {

    Livewire.on('mw-redirect-to-url', function (data) {

        if (typeof data['data']['url'] === 'undefined') {
            return;
        }

        if (mw.top().app && mw.top().app.canvas) {
            mw.top().app.canvas.setUrl(data['data']['url']);

        }


    });


});


document.addEventListener("DOMContentLoaded", function() {
    var footer = document.querySelector('.mw-dialog-footer');

    if (footer && footer.innerHTML.trim() === '') {
        footer.style.display = 'none';
    }
});

/*
 * Defensive modal a11y shim: body scroll lock + focus trap +
 * Escape close + focus return.
 *
 * Filament v5 modals use Alpine's `x-trap.noscroll` for both
 * focus trap and body scroll lock when @alpinejs/focus is loaded
 * (vendor/filament/support/resources/views/components/modal/
 * index.blade.php). This shim hooks Filament's own
 * `x-modal-opened` / `modal-closed` events and composes
 * additively: when x-trap is working it's a no-op (body already
 * locked, focus already inside), when not (some iframe contexts,
 * touch edge cases) it fills the gap.
 *
 *   - Body scroll lock when modal opens. Stack-aware: locks only
 *     on first open, unlocks only when the last modal closes.
 *     Preserves scroll position via data-mw-modal-scroll-y.
 *
 *   - Focus management:
 *     - Capture trigger (document.activeElement) at open time
 *     - If no element inside the modal is focused 100ms after
 *       open, focus first tabbable
 *     - Tab cycles within modal (no leak out) — only when
 *       Alpine's x-trap didn't already trap
 *     - Escape dispatches Filament's `close-modal` event with id
 *     - On modal-closed: restore focus to trigger
 *
 * WCAG: 2.4.3 Focus Order, 2.1.1 Keyboard, 2.1.2 No Keyboard Trap.
 */
(function () {
    if (typeof document === 'undefined') return;

    var TABBABLE = 'a[href], button:not([disabled]), input:not([disabled]):not([type="hidden"]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"]), [contenteditable="true"]';

    var openStack = [];
    var triggerMap = new Map();
    var savedScrollY = 0;
    var keydownHandler = null;

    function findModalEl(id) {
        if (!id) return null;
        return document.getElementById(id) ||
               document.querySelector('[data-fi-modal-id="' + id + '"]');
    }

    function tabbableInModal(modalEl) {
        if (!modalEl) return [];
        return Array.prototype.slice.call(modalEl.querySelectorAll(TABBABLE))
            .filter(function (el) {
                return el.offsetWidth > 0 || el.offsetHeight > 0 ||
                       el === document.activeElement;
            });
    }

    function lockBodyScroll() {
        if (openStack.length !== 1) return;
        if (document.body.style.overflow === 'hidden') return;
        savedScrollY = window.scrollY || window.pageYOffset || 0;
        document.body.dataset.mwModalScrollY = String(savedScrollY);
        document.body.style.overflow = 'hidden';
    }

    function unlockBodyScroll() {
        if (openStack.length !== 0) return;
        if (!('mwModalScrollY' in document.body.dataset)) return;
        document.body.style.overflow = '';
        var y = parseInt(document.body.dataset.mwModalScrollY || '0', 10);
        if (!isNaN(y)) window.scrollTo(0, y);
        delete document.body.dataset.mwModalScrollY;
    }

    function installKeyHandler() {
        if (keydownHandler) return;
        keydownHandler = function (event) {
            var topId = openStack[openStack.length - 1];
            if (!topId) return;
            var modalEl = findModalEl(topId);
            if (!modalEl) return;

            if (event.key === 'Escape' || event.keyCode === 27) {
                event.stopPropagation();
                window.dispatchEvent(new CustomEvent('close-modal', {
                    bubbles: true,
                    detail: { id: topId },
                }));
                return;
            }

            if (event.key === 'Tab' || event.keyCode === 9) {
                if (!modalEl.contains(document.activeElement)) {
                    var tabbables0 = tabbableInModal(modalEl);
                    if (tabbables0.length > 0) {
                        event.preventDefault();
                        tabbables0[0].focus();
                    }
                    return;
                }
                var tabbables = tabbableInModal(modalEl);
                if (tabbables.length === 0) {
                    event.preventDefault();
                    return;
                }
                var first = tabbables[0];
                var last = tabbables[tabbables.length - 1];
                if (event.shiftKey) {
                    if (document.activeElement === first) {
                        event.preventDefault();
                        last.focus();
                    }
                } else {
                    if (document.activeElement === last) {
                        event.preventDefault();
                        first.focus();
                    }
                }
            }
        };
        document.addEventListener('keydown', keydownHandler, true);
    }

    function removeKeyHandler() {
        if (!keydownHandler) return;
        if (openStack.length > 0) return;
        document.removeEventListener('keydown', keydownHandler, true);
        keydownHandler = null;
    }

    function focusFirstTabbableIn(modalEl) {
        if (modalEl.contains(document.activeElement) &&
            document.activeElement !== modalEl) {
            return;
        }
        var tabbables = tabbableInModal(modalEl);
        if (tabbables.length > 0) {
            try { tabbables[0].focus(); } catch (e) {}
        } else {
            if (!modalEl.hasAttribute('tabindex')) {
                modalEl.setAttribute('tabindex', '-1');
            }
            try { modalEl.focus(); } catch (e) {}
        }
    }

    document.addEventListener('x-modal-opened', function (event) {
        var id = event.detail && event.detail.id;
        if (!id) return;

        var trigger = document.activeElement;
        if (trigger && trigger !== document.body && trigger.tagName !== 'BODY') {
            triggerMap.set(id, trigger);
        }

        openStack.push(id);
        lockBodyScroll();
        installKeyHandler();

        setTimeout(function () {
            var modalEl = findModalEl(id);
            if (modalEl) {
                focusFirstTabbableIn(modalEl);
            }
        }, 100);
    });

    document.addEventListener('modal-closed', function (event) {
        var id = event.detail && event.detail.id;
        if (!id) return;

        var idx = openStack.lastIndexOf(id);
        if (idx >= 0) openStack.splice(idx, 1);

        unlockBodyScroll();
        removeKeyHandler();

        var trigger = triggerMap.get(id);
        triggerMap.delete(id);
        if (trigger && typeof trigger.focus === 'function' &&
            document.body.contains(trigger)) {
            try { trigger.focus(); } catch (e) {}
        }
    });
})();
