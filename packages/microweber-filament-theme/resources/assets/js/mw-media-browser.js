// task-2026-09-16-dropzone — bind the media-browser drag-and-drop zone from a
// GLOBAL observer instead of an inline x-init / livewire:init script.
//
// The component renders inside dynamically-opened modals (Live Edit "Module
// Settings" among them). An inline `document.addEventListener('livewire:init')`
// fires once at boot (too early); an inline `x-init` proved unreliable in the
// Live Edit modal context. This observer runs from the theme bundle (always
// loaded where the media browser is) and binds every `[data-mw-media-dropzone]`
// the moment it appears — modal, iframe-less dialog, or a normal admin page —
// then calls the Livewire component method via Livewire.find() (no $wire scope
// needed). Idempotent via a per-element flag.
(function () {
    if (window.__mwMediaDropzoneObserver) { return; }
    window.__mwMediaDropzoneObserver = true;

    function bindOne(el) {
        if (!el || el.getAttribute('data-mw-dz-bound') === '1') { return; }
        if (typeof mw === 'undefined' || !mw.dropZone) {
            // mw / uploader not ready yet — retry shortly.
            setTimeout(function () { bindOne(el); }, 150);
            return;
        }
        el.setAttribute('data-mw-dz-bound', '1');
        var statePath = el.getAttribute('data-state-path') || '';
        mw.dropZone(el).on('fileUploaded', function (res) {
            try {
                var wireEl = el.closest('[wire\\:id]');
                var comp = (window.Livewire && wireEl) ? window.Livewire.find(wireEl.getAttribute('wire:id')) : null;
                if (comp && typeof comp.callSchemaComponentMethod === 'function') {
                    comp.callSchemaComponentMethod(statePath, 'addMediaItem', { data: { url: res.src } });
                }
            } catch (e) { /* no-op */ }
        });
    }

    function scan(root) {
        var scope = (root && root.querySelectorAll) ? root : document;
        var nodes = scope.querySelectorAll('[data-mw-media-dropzone]:not([data-mw-dz-bound])');
        for (var i = 0; i < nodes.length; i++) { bindOne(nodes[i]); }
        if (root && root.matches && root.matches('[data-mw-media-dropzone]:not([data-mw-dz-bound])')) {
            bindOne(root);
        }
    }

    function start() {
        scan(document);
        var obs = new MutationObserver(function (mutations) {
            for (var i = 0; i < mutations.length; i++) {
                var added = mutations[i].addedNodes;
                for (var j = 0; j < added.length; j++) {
                    if (added[j].nodeType === 1) { scan(added[j]); }
                }
            }
        });
        obs.observe(document.documentElement, { childList: true, subtree: true });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', start);
    } else {
        start();
    }
})();

document.addEventListener('alpine:init', () => {
    Alpine.data('mwMediaManagerComponent', ({mediaIds}) => ({
        mediaIds,
        modalImageSettingsOpen: false,
        showBulkDeleteButton: false,
        selectedImages: [],


        init() {
            this.$watch('selectedImages', (value) => {
                this.showBulkDeleteButton = value.length > 0;
            });
        },

        editMediaOptionsById(id) {
            // Get the current state path from the form event
            const statePath = this.$el.closest('[x-data-id]').getAttribute('x-data-id');
            this.$wire.mountFormComponentAction(statePath, 'edit', {id: id});
        },

        selectAllMedia() {
            const checkboxes = document.querySelectorAll('.admin-thumb-item input[type="checkbox"]');
            const allIds = Array.from(checkboxes).map(checkbox => checkbox.value);
            this.selectedImages = allIds;
        },

        deselectAllMedia() {
            this.selectedImages = [];
        },

        bulkDeleteSelectedMedia() {
            if (this.selectedImages && this.selectedImages.length > 0) {
                if (confirm('Are you sure you want to delete the selected images?')) {
                    const statePath = this.$root.querySelector('[x-data-id]').getAttribute('x-data-id');
                    this.$wire.callSchemaComponentMethod(statePath, 'deleteMediaItemsByIds', {
                        ids: this.selectedImages
                    });
                    this.selectedImages = [];
                }
            }
        },

        async deleteMediaById(id) {
            const dialogConfirm = await mw.confirm('Are you sure you want to delete this image?').promise()
            if (dialogConfirm) {
                const statePath = this.$el.closest('[x-data-id]').getAttribute('x-data-id');
                this.$wire.callSchemaComponentMethod(statePath, 'deleteMediaItemById', {
                    id: id
                });
            }
        },

        async editImageFilename(id, url) {
            const editedImage = await mw.top().app.editImageDialog.editImageUrl(url);
            const statePath = this.$el.closest('[x-data-id]').getAttribute('x-data-id');
            this.$wire.callSchemaComponentMethod(statePath, 'updateImageFilename', {
                data: { id: id, filename: editedImage }
            });
        }
    }));
});
