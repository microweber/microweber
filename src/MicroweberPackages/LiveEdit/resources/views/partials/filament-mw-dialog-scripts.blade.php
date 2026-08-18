@php
    $mwDialogModuleSettings = \MicroweberPackages\Filament\Support\MwDialogRegistry::moduleSettingsClasses();
@endphp
<script>
(function () {
    if (window.__mwFilamentDialogBootstrapped) {
        return;
    }
    window.__mwFilamentDialogBootstrapped = true;

    window.__mwDialogModuleSettings = @json($mwDialogModuleSettings);

    function hasDialogApi() {
        return typeof window.mw !== 'undefined' && typeof window.mw.dialog === 'function';
    }

    function livewireDispatch(name, payload) {
        if (window.Livewire && typeof window.Livewire.dispatch === 'function') {
            window.Livewire.dispatch(name, payload || {});
        }
    }

    function moduleUsesMwDialog(className) {
        if (!className) return false;
        var list = window.__mwDialogModuleSettings || [];
        return list.indexOf(className) !== -1;
    }

    // Keys of module-settings dialogs currently open, so a repeated open request
    // (double-click on the gear, a duplicate CustomEvent, an event that both
    // bubbles and is re-dispatched) does not stack a second identical dialog.
    window.__mwModuleDialogOpenKeys = window.__mwModuleDialogOpenKeys || {};

    function moduleDialogKey(detail) {
        var id = detail.moduleId || (detail.params && detail.params.id) || '';
        return (detail.moduleSettingsComponent || '') + '::' + id;
    }

    function openFromModuleSettings(detail) {
        detail = detail || {};
        var cls = detail.moduleSettingsComponent || '';
        var params = detail.params || {};
        var liveEditIframeData = detail.liveEditIframeData || {};
        var title = detail.title || 'Module Settings';

        // Dedupe: bail if a settings dialog for this exact module is already open.
        var dialogKey = moduleDialogKey(detail);
        if (window.__mwModuleDialogOpenKeys[dialogKey]) {
            return;
        }
        window.__mwModuleDialogOpenKeys[dialogKey] = true;

        livewireDispatch('openModal', {
            component: 'filament-mw-dialog',
            arguments: {
                livewireComponent: cls,
                livewireParams: params,
                liveEditIframeData: liveEditIframeData,
                title: title,
                modalSettings: {
                    title: title,
                    closeButton: true,
                    overlay: true,
                    overlayClose: false,
                    autoHeight: true,
                    autosize: true,
                    autoScroll: true,
                    draggable: true,
                    closeOnEscape: true,
                    width: 800
                }
            },
            modalAttributes: {
                skin: 'mw-dialog',
                showCloseButton: false,
                showBackdrop: false,
                title: title,
                width: 800
            }
        });
    }

    window.mwFilamentDialog = {
        openFromModuleSettings: openFromModuleSettings,
        supports: moduleUsesMwDialog,
        decorateFilamentWindow: decorateFilamentWindow
    };

    window.addEventListener('openModuleSettingsAction', function (e) {
        var detail = (e && e.detail) ? e.detail : {};
        var cls = detail.moduleSettingsComponent || '';
        if (cls && moduleUsesMwDialog(cls)) {
            if (typeof e.stopImmediatePropagation === 'function') {
                e.stopImmediatePropagation();
            }
            openFromModuleSettings(detail);
        }
    }, true);

    window.addEventListener('closeFilamentSlideOver', function () {
        livewireDispatch('closeModal', { force: true });
    });

    // Release the dedupe keys once dialogs close, so the same module can be
    // opened again later. closeModal pops the top instance; modalStackCleared
    // wipes everything — both free the open-keys registry.
    function bindModuleDialogCleanup() {
        if (!window.Livewire || typeof window.Livewire.on !== 'function') {
            return;
        }
        window.Livewire.on('closeModal', function () {
            window.__mwModuleDialogOpenKeys = {};
        });
        window.Livewire.on('modalStackCleared', function () {
            window.__mwModuleDialogOpenKeys = {};
        });
    }
    if (window.Livewire && typeof window.Livewire.on === 'function') {
        bindModuleDialogCleanup();
    } else {
        document.addEventListener('livewire:init', bindModuleDialogCleanup, { once: true });
    }

    function parseOptions(el) {
        var raw = el.getAttribute('data-mw-dialog-options') || '{}';
        try {
            return JSON.parse(raw) || {};
        } catch (err) {
            return {};
        }
    }

    function decorateFilamentWindow(windowEl) {
        if (!windowEl || windowEl.dataset.mwDialogDecorated === '1') {
            return;
        }
        // Skip windows already presented through the livewire-modal mw-dialog
        // skin — that host renders its own mw.dialog chrome, so decorating here
        // too would duplicate the header / drag handle on the same dialog.
        if (windowEl.closest && windowEl.closest('.mw-livewire-modal-mw-dialog, .mw-filament-mw-dialog-window')) {
            return;
        }
        windowEl.dataset.mwDialogDecorated = '1';
        windowEl.classList.add('mw-filament-mw-dialog');

        var modalRoot = windowEl.closest('.fi-modal');
        if (modalRoot) {
            modalRoot.classList.add('mw-filament-mw-dialog-host');
        }

        var opts = parseOptions(windowEl);

        if (!windowEl.querySelector('[data-mw-dialog-drag-handle]')) {
            var header = document.createElement('div');
            header.className = 'mw-dialog-header mw-filament-mw-dialog-header';
            header.setAttribute('data-mw-dialog-drag-handle', '1');
            var title = document.createElement('div');
            title.className = 'modal-title settings-title-inside';
            title.textContent = opts.title || '';
            header.appendChild(title);
            windowEl.insertBefore(header, windowEl.firstChild);
        }

        if (opts.draggable !== false) {
            if (window.jQuery && window.jQuery.fn && window.jQuery.fn.draggable) {
                try {
                    window.jQuery(windowEl).draggable({
                        handle: '[data-mw-dialog-drag-handle]',
                        containment: 'window',
                        scroll: false,
                        iframeFix: true
                    });
                    windowEl.classList.add('mw-dialog-header--is-draggable');
                } catch (err) {
                    makeWindowDraggableNative(windowEl);
                }
            } else {
                // No jQuery UI on the page — fall back to a native
                // pointer-events drag so the dialog stays movable.
                makeWindowDraggableNative(windowEl);
            }
        }

        centerFilamentWindow(windowEl);
    }

    // Native (no-dependency) drag: move the fixed-position window by its
    // header handle using Pointer Events. Used only when jQuery UI's
    // draggable is unavailable.
    function makeWindowDraggableNative(windowEl) {
        if (!windowEl || windowEl.getAttribute('data-mw-native-drag') === '1') {
            return;
        }
        var handle = windowEl.querySelector('[data-mw-dialog-drag-handle]') || windowEl;
        handle.style.cursor = 'move';
        windowEl.setAttribute('data-mw-native-drag', '1');
        windowEl.classList.add('mw-dialog-header--is-draggable');

        var startX = 0, startY = 0, origLeft = 0, origTop = 0, dragging = false;

        handle.addEventListener('pointerdown', function (e) {
            if (e.button !== 0) return;
            if (e.target && e.target.closest && e.target.closest('.mw-dialog-close, [data-mw-dialog-close]')) {
                return;
            }
            dragging = true;
            var rect = windowEl.getBoundingClientRect();
            startX = e.clientX;
            startY = e.clientY;
            origLeft = rect.left;
            origTop = rect.top;
            windowEl.style.position = 'fixed';
            windowEl.style.margin = '0';
            try { handle.setPointerCapture(e.pointerId); } catch (err) { /* no-op */ }
            e.preventDefault();
        });
        handle.addEventListener('pointermove', function (e) {
            if (!dragging) return;
            windowEl.style.left = (origLeft + e.clientX - startX) + 'px';
            windowEl.style.top = (origTop + e.clientY - startY) + 'px';
        });
        handle.addEventListener('pointerup', function () { dragging = false; });
        handle.addEventListener('pointercancel', function () { dragging = false; });
    }

    function centerFilamentWindow(windowEl) {
        if (!windowEl) return;
        var rect = windowEl.getBoundingClientRect();
        var top = Math.max(24, (window.innerHeight - rect.height) / 2);
        var left = Math.max(24, (window.innerWidth - rect.width) / 2);
        windowEl.style.position = 'fixed';
        windowEl.style.top = top + 'px';
        windowEl.style.left = left + 'px';
        windowEl.style.margin = '0';
        windowEl.style.transform = 'none';
        windowEl.style.maxHeight = 'calc(100vh - 48px)';
        windowEl.style.overflow = 'auto';
    }

    function scanFilamentMwDialogs(root) {
        var scope = root && root.querySelectorAll ? root : document;
        var nodes = scope.querySelectorAll
            ? scope.querySelectorAll('.fi-modal-window[data-mw-dialog="1"], .fi-modal-window.mw-filament-mw-dialog')
            : [];
        for (var i = 0; i < nodes.length; i++) {
            decorateFilamentWindow(nodes[i]);
        }
        if (root && root.matches && (
            root.matches('.fi-modal-window[data-mw-dialog="1"]')
            || root.matches('.fi-modal-window.mw-filament-mw-dialog')
        )) {
            decorateFilamentWindow(root);
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () {
            scanFilamentMwDialogs(document);
        });
    } else {
        scanFilamentMwDialogs(document);
    }

    var observer = new MutationObserver(function (mutations) {
        for (var i = 0; i < mutations.length; i++) {
            var added = mutations[i].addedNodes;
            for (var j = 0; j < added.length; j++) {
                if (added[j].nodeType !== 1) continue;
                scanFilamentMwDialogs(added[j]);
            }
        }
    });
    observer.observe(document.documentElement, { childList: true, subtree: true });
})();
</script>
<style>
    .fi-modal.mw-filament-mw-dialog-host,
    .fi-modal:has(.mw-filament-mw-dialog) {
        background: transparent !important;
        pointer-events: none;
    }
    .fi-modal.mw-filament-mw-dialog-host .fi-modal-close-overlay,
    .fi-modal:has(.mw-filament-mw-dialog) .fi-modal-close-overlay {
        display: none !important;
    }
    .fi-modal.mw-filament-mw-dialog-host .fi-modal-window-ctn,
    .fi-modal.mw-filament-mw-dialog-host .mw-filament-mw-dialog,
    .fi-modal:has(.mw-filament-mw-dialog) .fi-modal-window-ctn,
    .fi-modal:has(.mw-filament-mw-dialog) .mw-filament-mw-dialog {
        pointer-events: auto;
    }
    .fi-modal-window.mw-filament-mw-dialog {
        position: fixed !important;
        inset: auto !important;
        width: min(900px, calc(100vw - 48px)) !important;
        max-width: min(900px, calc(100vw - 48px)) !important;
        max-height: calc(100vh - 48px) !important;
        overflow: auto;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.18);
    }
    .mw-filament-mw-dialog-header {
        cursor: move;
        padding: 10px 16px;
        min-height: 36px;
    }
    .mw-filament-dialog-inner {
        min-width: 320px;
        min-height: 160px;
    }
    .mw-dialog.mw-filament-mw-dialog-window .mw-dialog-container {
        padding: 12px 16px;
    }
</style>
