document.addEventListener('alpine:init', () => {
    Alpine.data('mwMediaManagerComponent', ({mediaIds}) => ({
        mediaIds,
        modalImageSettingsOpen: false,
        showBulkDeleteButton: false,
        selectedImages: [],

        // ── detail panel (right pane) ──────────────────────────────────────
        detailId: null,
        detail: { filename: '', caption: '', alt: '', link: '', crop: 'center', cropPosition: '', w: null, h: null, size: null },
        cropX: 50,
        cropY: 50,
        writingAlt: false,
        // +Generate
        showGenerate: false,
        generatePrompt: '',
        generating: false,
        generateError: '',
        // Link → Page picker
        showPagePicker: false,
        pages: [],
        pagesLoaded: false,
        pageFilter: '',


        // Absolute schema key (e.g. "form.mediaIds") for
        // $wire.callSchemaComponentMethod — a bare state path ("mediaIds") has no
        // dot and silently resolves to null on the server (nothing happens).
        mwComponentKey() {
            const dz = this.$el.closest('[data-mw-media-dropzone]');
            return (dz && dz.getAttribute('data-component-key')) || 'form.mediaIds';
        },

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
            const componentKey = this.mwComponentKey();
            const self = this;
            const bind = () => {
                if (typeof mw === 'undefined' || !mw.dropZone) {
                    setTimeout(bind, 150);
                    return;
                }
                dz._mwDzBound = true;
                mw.dropZone(dz).on('fileUploaded', (res) => {
                    self.$wire.callSchemaComponentMethod(componentKey, 'addMediaItem', { data: { url: res.src } });
                });
            };
            bind();
        },

        // Make the thumbnails reliably draggable AND persist the new order.
        //
        // Filament's x-sortable uses SortableJS in NATIVE HTML5 DnD mode. Inside
        // the Live Edit "Module Settings" dialog that never let the thumbnails be
        // picked up (the items are only draggable at mousedown, and the drag
        // collides with the surrounding dropzone's own native drag handlers).
        // Re-create the Sortable in forceFallback (pointer-based) mode — it does
        // not depend on the draggable attribute and does not fight the dropzone —
        // and persist the settled order from its onEnd via $wire → mediaItemsSort.
        // Re-applied whenever Livewire re-renders a fresh holder.
        initMediaSortPersistence() {
            const root = this.$root;
            const componentKey = this.mwComponentKey();
            const self = this;

            const persist = (holder) => {
                const ids = Array.prototype.map.call(
                    holder.querySelectorAll(':scope > [x-sortable-item]'),
                    (n) => n.getAttribute('x-sortable-item')
                );
                if (ids.length) {
                    self.$wire.callSchemaComponentMethod(componentKey, 'mediaItemsSort', { itemsSortedIds: ids });
                }
            };

            const applyFallbackSortable = (holder) => {
                if (!holder || holder._mwFallbackSortable) { return; }
                if (typeof window.Sortable === 'undefined') { return; }
                // Filament may not have created its Sortable yet — wait for it, then
                // replace it with a forceFallback instance.
                if (!holder.sortable) {
                    setTimeout(() => applyFallbackSortable(holder), 150);
                    return;
                }
                holder._mwFallbackSortable = true;
                try { holder.sortable.destroy(); } catch (e) { /* no-op */ }
                holder.sortable = window.Sortable.create(holder, {
                    draggable: '[x-sortable-item]',
                    handle: '[x-sortable-handle]',
                    dataIdAttr: 'x-sortable-item',
                    animation: 150,
                    forceFallback: true,
                    fallbackTolerance: 3,
                    ghostClass: 'fi-sortable-ghost',
                    onEnd: () => { setTimeout(() => persist(holder), 0); },
                });
            };

            const scan = () => {
                const holder = root.querySelector('.admin-thumbs-holder[x-sortable]');
                if (holder) { applyFallbackSortable(holder); }
            };

            scan();
            // Re-apply after Livewire re-renders swap in a fresh holder.
            new MutationObserver(scan).observe(root, { childList: true, subtree: true });
        },

        editMediaOptionsById(id) {
            this.$wire.mountFormComponentAction(this.mwComponentKey(), 'edit', {id: id});
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
                    this.$wire.callSchemaComponentMethod(this.mwComponentKey(), 'deleteMediaItemsByIds', {
                        ids: this.selectedImages
                    });
                    this.selectedImages = [];
                }
            }
        },

        async deleteMediaById(id) {
            const dialogConfirm = await mw.confirm('Are you sure you want to delete this image?').promise()
            if (dialogConfirm) {
                this.$wire.callSchemaComponentMethod(this.mwComponentKey(), 'deleteMediaItemById', {
                    id: id
                });
            }
        },

        async editImageFilename(id, url) {
            const editedImage = await mw.top().app.editImageDialog.editImageUrl(url);
            this.$wire.callSchemaComponentMethod(this.mwComponentKey(), 'updateImageFilename', {
                data: { id: id, filename: editedImage }
            });
        },

        // ── detail panel ───────────────────────────────────────────────────
        // A tile carries its per-image data as JSON on data-mb-item; clicking it
        // populates the right panel and marks it selected (blue ring / checkbox).
        selectImage(el) {
            if (!el) { return; }
            let data = {};
            try { data = JSON.parse(el.getAttribute('data-mb-item') || '{}'); } catch (e) { data = {}; }
            this.detailId = data.id;
            this.detail = {
                filename: data.filename || '',
                caption: data.caption || '',
                alt: data.alt || '',
                link: data.link || '',
                crop: data.crop || 'center',
                cropPosition: data.cropPosition || '',
                w: data.w || null,
                h: data.h || null,
                size: data.size || null,
            };
            // seed the custom-crop sliders from a stored "x% y%"
            const m = /(-?\d+(?:\.\d+)?)%\s+(-?\d+(?:\.\d+)?)%/.exec(this.detail.cropPosition || '');
            this.cropX = m ? parseFloat(m[1]) : 50;
            this.cropY = m ? parseFloat(m[2]) : 50;
            // clicking selects (union), matching the mockup's multi-select
            const idStr = String(data.id);
            if (!this.selectedImages.includes(idStr)) {
                this.selectedImages = [...this.selectedImages, idStr];
            }
        },

        detailName() {
            const f = (this.detail.filename || '').split('?')[0].split('#')[0];
            return f.substring(f.lastIndexOf('/') + 1) || 'Image';
        },

        humanSize(bytes) {
            if (!bytes && bytes !== 0) { return ''; }
            if (bytes < 1024) { return bytes + ' B'; }
            const kb = bytes / 1024;
            if (kb < 1024) { return Math.round(kb) + ' KB'; }
            return (kb / 1024).toFixed(1) + ' MB';
        },

        // Persist one panel field into image_options (server merges by key).
        saveMeta(field) {
            if (!this.detailId) { return; }
            const payload = { id: this.detailId };
            if (field === 'caption') { payload.caption = this.detail.caption; }
            else if (field === 'altText') { payload.altText = this.detail.alt; }
            else if (field === 'link') { payload.link = this.detail.link; }
            else if (field === 'crop') {
                payload.crop = this.detail.crop;
                payload.cropPosition = this.detail.crop === 'custom' ? this.detail.cropPosition : '';
            }
            this.$wire.callSchemaComponentMethod(this.mwComponentKey(), 'updateMediaItemMeta', { data: payload });
        },

        setCrop(value) {
            this.detail.crop = value;
            if (value === 'custom') {
                this.onCropRange();
            }
            this.saveMeta('crop');
        },

        onCropRange() {
            this.detail.cropPosition = this.cropX + '% ' + this.cropY + '%';
        },

        async writeAltForMe() {
            if (!this.detailId || this.writingAlt) { return; }
            this.writingAlt = true;
            try {
                const res = await this.$wire.callSchemaComponentMethod(this.mwComponentKey(), 'generateAltText', { data: { id: this.detailId } });
                if (res && res.success && res.altText) {
                    this.detail.alt = res.altText;
                } else if (res && res.message) {
                    mw.notification ? mw.notification.error(res.message) : alert(res.message);
                }
            } catch (e) {
                alert('Could not generate alt text.');
            } finally {
                this.writingAlt = false;
            }
        },

        // ── +Generate ───────────────────────────────────────────────────────
        toggleGenerate() {
            this.showGenerate = !this.showGenerate;
            this.generateError = '';
        },

        async runGenerate() {
            const prompt = (this.generatePrompt || '').trim();
            if (!prompt || this.generating) { return; }
            this.generating = true;
            this.generateError = '';
            try {
                const res = await this.$wire.callSchemaComponentMethod(this.mwComponentKey(), 'generateMediaItem', { data: { prompt: prompt } });
                if (res && res.success) {
                    this.generatePrompt = '';
                    this.showGenerate = false;
                } else {
                    this.generateError = (res && res.message) ? res.message : 'Image generation failed.';
                }
            } catch (e) {
                this.generateError = 'Image generation failed.';
            } finally {
                this.generating = false;
            }
        },

        // ── Link → Page picker ────────────────────────────────────────────────
        togglePagePicker() {
            this.showPagePicker = !this.showPagePicker;
            if (this.showPagePicker && !this.pagesLoaded) {
                this.loadPages();
            }
        },

        async loadPages() {
            try {
                const res = await this.$wire.callSchemaComponentMethod(this.mwComponentKey(), 'getSitePages');
                this.pages = Array.isArray(res) ? res : [];
                this.pagesLoaded = true;
            } catch (e) {
                this.pages = [];
            }
        },

        filteredPages() {
            const q = (this.pageFilter || '').toLowerCase().trim();
            if (!q) { return this.pages; }
            return this.pages.filter((p) => (p.title || '').toLowerCase().includes(q));
        },

        pickPage(p) {
            this.detail.link = p.url;
            this.showPagePicker = false;
            this.pageFilter = '';
            this.saveMeta('link');
        },

        async removeDetail() {
            if (!this.detailId) { return; }
            const id = this.detailId;
            const dialogConfirm = await mw.confirm('Are you sure you want to remove this image?').promise();
            if (dialogConfirm) {
                this.$wire.callSchemaComponentMethod(this.mwComponentKey(), 'deleteMediaItemById', { id: id });
                this.detailId = null;
            }
        }
    }));
});
