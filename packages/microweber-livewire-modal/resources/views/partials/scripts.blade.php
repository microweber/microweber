<script>
(function () {
    if (window.__mwLivewireModalBootstrapped) {
        return;
    }
    window.__mwLivewireModalBootstrapped = true;

    const MW_MODAL_TABBABLE_SELECTOR = [
        'a[href]',
        'button:not([disabled])',
        'input:not([disabled]):not([type="hidden"])',
        'select:not([disabled])',
        'textarea:not([disabled])',
        '[tabindex]:not([tabindex="-1"])',
    ].join(', ');

    let mwModalOpenStack = 0;
    let mwModalFocusStack = [];
    let mwModalKeydownBound = false;

    function mwLockBodyScrollForModal() {
        if (mwModalOpenStack++ > 0) return;
        document.body.dataset.mwModalScrollY = String(window.scrollY || window.pageYOffset || 0);
        document.body.style.overflow = 'hidden';
    }

    function mwUnlockBodyScrollForModal() {
        if (mwModalOpenStack > 0) mwModalOpenStack--;
        if (mwModalOpenStack > 0) return;
        document.body.style.overflow = '';
        const stored = document.body.dataset.mwModalScrollY;
        if (typeof stored === 'undefined' || stored === null || stored === '') {
            return;
        }
        const y = parseInt(stored, 10);
        if (!isNaN(y)) window.scrollTo(0, y);
        delete document.body.dataset.mwModalScrollY;
    }

    function mwGetTopmostOpenModal() {
        const all = document.querySelectorAll('.js-modal-livewire.active, .js-modal-livewire.mw-livewire-modal.active');
        let top = null;
        let topZ = -Infinity;
        for (let i = 0; i < all.length; i++) {
            const el = all[i];
            const cs = window.getComputedStyle(el);
            if (cs.display === 'none' || cs.visibility === 'hidden') continue;
            const z = parseInt(cs.zIndex, 10) || 0;
            if (z >= topZ) {
                topZ = z;
                top = el;
            }
        }
        return top;
    }

    function mwGetTabbablesInModal(modalEl) {
        if (!modalEl) return [];
        return Array.from(modalEl.querySelectorAll(MW_MODAL_TABBABLE_SELECTOR))
            .filter(function (n) {
                if (n.offsetParent === null && n !== document.activeElement) return false;
                if (n.hasAttribute('disabled')) return false;
                return true;
            });
    }

    function mwPickInitialFocus(tabbables, modalEl) {
        if (tabbables.length === 0) return modalEl;
        const nonClose = tabbables.filter(function (n) {
            return !n.hasAttribute('data-mw-modal-close');
        });
        return nonClose.length > 0 ? nonClose[0] : tabbables[0];
    }

    function mwDispatchCloseModal(force) {
        if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
            window.Livewire.dispatch('closeModal', { force: !!force });
        }
    }

    function mwModalKeydownHandler(event) {
        const topModal = mwGetTopmostOpenModal();
        if (!topModal) return;

        if (event.key === 'Escape' || event.keyCode === 27) {
            if (topModal.getAttribute('data-mw-modal-close-on-escape') === '0') {
                return;
            }
            event.preventDefault();
            mwDispatchCloseModal(false);
            return;
        }

        if (event.key !== 'Tab' && event.keyCode !== 9) return;

        const tabbables = mwGetTabbablesInModal(topModal);
        if (tabbables.length === 0) {
            event.preventDefault();
            topModal.focus();
            return;
        }

        const first = tabbables[0];
        const last = tabbables[tabbables.length - 1];
        const active = document.activeElement;

        if (event.shiftKey) {
            if (active === first || !topModal.contains(active)) {
                event.preventDefault();
                last.focus();
            }
        } else {
            if (active === last || !topModal.contains(active)) {
                event.preventDefault();
                first.focus();
            }
        }
    }

    function mwBindModalKeydown() {
        if (mwModalKeydownBound) return;
        document.addEventListener('keydown', mwModalKeydownHandler, true);
        mwModalKeydownBound = true;
    }

    function mwUnbindModalKeydown() {
        if (!mwModalKeydownBound) return;
        if (mwModalFocusStack.length > 0) return;
        document.removeEventListener('keydown', mwModalKeydownHandler, true);
        mwModalKeydownBound = false;
    }

    function mwTrapFocusForModal() {
        mwModalFocusStack.push(document.activeElement || null);
        mwBindModalKeydown();

        setTimeout(function () {
            const topModal = mwGetTopmostOpenModal();
            if (!topModal) return;
            const tabbables = mwGetTabbablesInModal(topModal);
            mwPickInitialFocus(tabbables, topModal).focus();
        }, 30);
    }

    function mwReleaseFocusForModal() {
        const trigger = mwModalFocusStack.pop();
        mwUnbindModalKeydown();
        if (trigger && typeof trigger.focus === 'function' && document.body.contains(trigger)) {
            try {
                trigger.focus();
            } catch (e) { /* no-op */ }
        }
    }

    function mwParseDialogOptions(modal, data) {
        let opts = {};
        if (data && data.modalSettings && typeof data.modalSettings === 'object') {
            opts = Object.assign({}, data.modalSettings);
        }
        const raw = modal && modal.getAttribute('data-mw-dialog-options');
        if (raw) {
            try {
                opts = Object.assign(JSON.parse(raw) || {}, opts);
            } catch (e) { /* ignore */ }
        }
        return opts;
    }

    function mwHasDialogApi() {
        return typeof window.mw !== 'undefined' && typeof window.mw.dialog === 'function';
    }

    function mwMaybeWrapWithDialog(id, data) {
        const modal = document.getElementById('js-modal-livewire-id-' + id);
        if (!modal) return;
        const skin = modal.getAttribute('data-mw-modal-skin') || '';
        if (skin !== 'mw-dialog' && skin !== 'bare') {
            return;
        }
        if (modal.dataset.mwDialogWrapped === '1') {
            return;
        }
        if (!mwHasDialogApi()) {
            modal.classList.add('mw-dialog-fallback');
            return;
        }

        const content = modal.querySelector('[data-mw-dialog-content="' + id + '"]')
            || modal.querySelector('.mw-livewire-modal-content');
        if (!content) {
            modal.classList.add('mw-dialog-fallback');
            return;
        }

        const opts = mwParseDialogOptions(modal, data);
        const width = opts.width || 800;
        const dlg = window.mw.dialog({
            content: content,
            id: 'mw-dialog-lw-' + id,
            title: opts.title || '',
            overlay: opts.overlay !== false,
            overlayClose: opts.overlayClose === true,
            closeButton: opts.closeButton !== false,
            closeOnEscape: opts.closeOnEscape !== false,
            autoHeight: opts.autoHeight !== false && opts.autosize !== false,
            draggable: opts.draggable !== false,
            scrollMode: opts.autoScroll === false ? 'window' : (opts.scrollMode || 'inside'),
            width: width,
            // Always recenter for the true content height. Core's default
            // 'intuitive' mode only moves the box down when it grows taller, so a
            // shorter viewport (or a shrinking form) leaves it overflowing the
            // bottom. 'center' recenters symmetrically on every center() call.
            centerMode: 'center',
            className: 'mw-filament-mw-dialog-window',
            closeButtonAction: 'remove',
            beforeRemove: function () {
                if (modal.dataset.mwDialogClosing === '1') {
                    return true;
                }
                modal.dataset.mwDialogClosing = '1';
                mwDispatchCloseModal(false);
                return true;
            },
        });

        if (dlg && dlg.dialogMain && dlg.dialogMain.parentElement !== modal) {
            modal.appendChild(dlg.dialogMain);
        }

        modal.dataset.mwDialogWrapped = '1';
        modal._mwDialog = dlg;
        modal.setAttribute('wire:ignore', '');

        // Core mw.dialog pins a fixed 320px holder height for DOM (non-iframe)
        // content — its autoHeight path only autosizes iframes — so the settings
        // form was frozen at 320px: taller content was clipped with no way to
        // scroll. Clear the fixed height so the holder shrink-wraps its content
        // (up to core's max-height:96% cap, past which the inner .mw-dialog-container
        // scrolls via its own overflow-y:auto), then recenter for the real height.
        if (dlg && dlg.dialogHolder) {
            const holder = dlg.dialogHolder;
            const recenter = function () {
                if (dlg && typeof dlg.center === 'function' && !dlg._dragged) {
                    dlg.center();
                }
            };
            holder.style.height = 'auto';
            recenter();
            // Keep it centered/contained when the inner form changes height
            // (Content/Design tab switches, async-loaded fields).
            if (typeof ResizeObserver !== 'undefined') {
                let raf = null;
                const ro = new ResizeObserver(function () {
                    if (raf) cancelAnimationFrame(raf);
                    raf = requestAnimationFrame(recenter);
                });
                ro.observe(holder);
                modal._mwDialogResizeObserver = ro;
            } else {
                setTimeout(recenter, 60);
                setTimeout(recenter, 200);
            }
        }
    }

    function mwUnwrapDialog(id) {
        const modal = document.getElementById('js-modal-livewire-id-' + id);
        if (!modal) return;
        if (modal._mwDialogResizeObserver) {
            try { modal._mwDialogResizeObserver.disconnect(); } catch (e) { /* no-op */ }
            modal._mwDialogResizeObserver = null;
        }
        const dlg = modal._mwDialog;
        if (dlg && modal.dataset.mwDialogClosing !== '1') {
            modal.dataset.mwDialogClosing = '1';
            try {
                dlg.remove();
            } catch (e) { /* already gone */ }
        }
        modal.dataset.mwDialogWrapped = '0';
        modal._mwDialog = null;
    }

    function mwShowModalById(id) {
        const modal = document.getElementById('js-modal-livewire-id-' + id);
        if (!modal) return;
        modal.classList.add('active');
        modal.style.display = 'block';
    }

    function mwHideModalById(id) {
        const modal = document.getElementById('js-modal-livewire-id-' + id);
        if (!modal) return;
        modal.classList.remove('active', 'is-top');
        modal.style.display = 'none';
    }

    function onLivewireReady(cb) {
        if (window.Livewire && typeof window.Livewire.on === 'function') {
            cb(window.Livewire);
            return;
        }
        document.addEventListener('livewire:init', function () {
            cb(window.Livewire);
        }, { once: true });
    }

    onLivewireReady(function (Livewire) {
        Livewire.on('activeModalComponentChanged', function (payload) {
            // Livewire 3 may pass { id, data } or nested args
            let id = null;
            let data = null;
            if (payload && typeof payload === 'object') {
                id = payload.id || (payload.data && payload.data.id) || null;
                data = payload.data || null;
                if (Array.isArray(payload) && payload[0]) {
                    id = payload[0].id || (payload[0].data && payload[0].data.id) || id;
                    data = payload[0].data || data;
                }
            }
            if (id) {
                mwShowModalById(id);
                mwMaybeWrapWithDialog(id, data);
            }
            mwLockBodyScrollForModal();
            mwTrapFocusForModal();
        });

        // Close only the topmost instance; nested parents stay open.
        Livewire.on('closeModal', function () {
            // Server component handles stack; client releases focus/scroll for one level.
            mwUnlockBodyScrollForModal();
            mwReleaseFocusForModal();
        });

        Livewire.on('modalInstanceClosed', function (payload) {
            let id = null;
            if (payload && typeof payload === 'object') {
                id = payload.id || null;
                if (Array.isArray(payload) && payload[0]) {
                    id = payload[0].id || id;
                }
            }
            if (id) {
                mwUnwrapDialog(id);
                mwHideModalById(id);
            }
        });

        Livewire.on('modalStackCleared', function () {
            const opened = document.querySelectorAll('.js-modal-livewire.mw-livewire-modal, .js-modal-livewire');
            for (let i = 0; i < opened.length; i++) {
                opened[i].classList.remove('active', 'is-top');
                opened[i].style.display = 'none';
            }
            while (mwModalOpenStack > 0) {
                mwUnlockBodyScrollForModal();
            }
            while (mwModalFocusStack.length > 0) {
                mwReleaseFocusForModal();
            }
        });

        // Backdrop click — only the topmost modal, and only if enabled.
        document.addEventListener('click', function (e) {
            const t = e.target;
            if (!t || !t.classList) return;

            // Close button (X)
            const closeBtn = t.closest && t.closest('[data-mw-modal-close="1"]');
            if (closeBtn) {
                e.preventDefault();
                mwDispatchCloseModal(false);
                return;
            }

            if (!t.classList.contains('js-modal-livewire')) return;
            if (t.getAttribute('data-mw-modal-backdrop') !== '1') return;
            if (t.hasAttribute('data-mw-modal-no-backdrop-close')) return;
            if (t.getAttribute('data-mw-modal-close-on-click-away') === '0') return;

            // Only close if this is the topmost open modal
            const top = mwGetTopmostOpenModal();
            if (top && top !== t) return;

            mwDispatchCloseModal(false);
        }, true);
    });
})();
</script>
