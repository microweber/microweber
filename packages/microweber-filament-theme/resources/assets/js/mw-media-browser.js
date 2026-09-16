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

            // task-2026-09-16 — wire drag-and-drop upload + reorder persistence
            // HERE (in the Alpine component) rather than an inline x-init /
            // livewire:init script or a plain-JS global observer:
            //  - x-init / livewire:init never fired reliably inside the Live Edit
            //    Module Settings modal.
            //  - a global observer had no working way to invoke the Livewire
            //    method — only this.$wire.callSchemaComponentMethod (the same
            //    call the delete/bulk-delete actions use) actually hits the
            //    server; Livewire.find(id).callSchemaComponentMethod does not.
            // This component's init() runs wherever the browser renders (its
            // delete/bulk-delete already work), so $wire is available here.
            this.$nextTick(() => {
                this.initMediaDropzone();
                this.initMediaSortPersistence();
            });
        },

        // Bind the drag-and-drop upload zone (an ancestor of this component) and
        // add each uploaded file to the gallery via addMediaItem.
        initMediaDropzone() {
            const dz = this.$el.closest('[data-mw-media-dropzone]');
            if (!dz || dz._mwDzBound) {
                return;
            }
            const statePath = dz.getAttribute('data-state-path') || 'mediaIds';
            const self = this;
            const bind = () => {
                if (typeof mw === 'undefined' || !mw.dropZone) {
                    setTimeout(bind, 150);
                    return;
                }
                dz._mwDzBound = true;
                mw.dropZone(dz).on('fileUploaded', (res) => {
                    self.$wire.callSchemaComponentMethod(statePath, 'addMediaItem', { data: { url: res.src } });
                });
            };
            bind();
        },

        // Persist thumbnail reorder. Listen for SortableJS's bubbling `end`
        // CustomEvent on this component's root (survives Livewire re-renders /
        // Sortable re-inits), then read the SETTLED order — deferred so Filament's
        // own SortableJS onEnd correction runs first (the old inline x-on:end read
        // too early and saved a stale/off-by-one order) — and persist it.
        initMediaSortPersistence() {
            const root = this.$root;
            const dz = this.$el.closest('[data-mw-media-dropzone]');
            const statePath = dz ? (dz.getAttribute('data-state-path') || 'mediaIds') : 'mediaIds';
            const self = this;
            root.addEventListener('end', () => {
                setTimeout(() => {
                    const holder = root.querySelector('.admin-thumbs-holder[x-sortable]');
                    if (!holder) {
                        return;
                    }
                    const ids = Array.prototype.map.call(
                        holder.querySelectorAll(':scope > [x-sortable-item]'),
                        (n) => n.getAttribute('x-sortable-item')
                    );
                    if (!ids.length) {
                        return;
                    }
                    self.$wire.callSchemaComponentMethod(statePath, 'mediaItemsSort', { itemsSortedIds: ids });
                }, 0);
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
