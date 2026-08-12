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
            if (payload && typeof payload === 'object') {
                id = payload.id || (payload.data && payload.data.id) || null;
                if (Array.isArray(payload) && payload[0]) {
                    id = payload[0].id || (payload[0].data && payload[0].data.id) || id;
                }
            }
            if (id) {
                mwShowModalById(id);
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
