<script>
/*
 * "Save & Publish" button + 30s draft auto-save.
 *
 * The visible label is "Save & Publish" so the user knows the
 * action publishes changes to the live site immediately rather
 * than expecting a separate publish step. The aria-label still
 * references the Ctrl+S shortcut.
 *
 * The 30-second draft auto-save:
 *   - Only fires AFTER the canvas is loaded (no auto-save on a
 *     blank/loading editor)
 *   - Only fires if `_dirty` is set (user has edited since the
 *     last save)
 *   - Calls save() in SILENT mode so the success toast doesn't
 *     spam every 30 seconds
 *   - Failure toast still fires so the user is alerted to lost
 *     work
 *
 * The dirty flag is tracked via canvas activity events: editor
 * input, paste, and Mutation events all set _dirty = true. Each
 * successful save resets _dirty = false.
 */

const AUTO_SAVE_INTERVAL_MS = 30000;

export default {
    data() {
        return {
            _dirty: false,
            _autoSaveTimer: null,
            _autoSaveActive: false,
        };
    },
    methods: {

        save(options) {
            options = options || {};
            var silent = options.silent === true;
            var self = this;

            var btn = document.getElementById('save-button');
            if (!btn) return;
            btn.classList.add('btn-loading');
            btn.disabled = true;
            var mountedActionValidationFailed = false;
            var onMountedActionValidationFailed = function () {
                mountedActionValidationFailed = true;
            };
            window.addEventListener('liveEditMountedActionValidationFailed', onMountedActionValidationFailed, { once: true });

            // If a Filament module-settings slideOver is open (e.g.
            // "Add New Post" / "Add Page" / module settings form),
            // submit it as part of the page save. The user expects the
            // live-edit SAVE button to be a one-click "save everything
            // visible" rather than only saving the page DOM. Dispatch
            // a window event that the iframe-page Alpine listener
            // picks up and forwards to $wire.callMountedAction(); the
            // Filament action's own submit handler then runs the
            // form's create/update logic.
            try {
                window.dispatchEvent(new Event('liveEditSaveCallMountedAction'));
            } catch (_) { /* no-op */ }

            var canvasWindow = mw.app.canvas.getWindow();
            if(canvasWindow.mw && typeof canvasWindow.mw.drag === 'undefined'){
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                if (!silent) {
                    mw.notification.error('Something went wrong with saving the page.',7500);
                }
                return;
            }
            var saved = canvasWindow.mw.drag.save();

            var finishSave = function (result) {
                setTimeout(function () {
                    btn.classList.remove('btn-loading');
                    btn.disabled = false;
                    window.removeEventListener('liveEditMountedActionValidationFailed', onMountedActionValidationFailed);

                    if (mountedActionValidationFailed) {
                        if (!silent) {
                            mw.notification.error('Please fix the highlighted required field before saving.', 7500);
                        }
                        return;
                    }

                    if (result === 'success') {
                        self.$data._dirty = false;
                        if (silent) {
                            // No success toast on silent draft saves
                            // — the user is editing, they don't want
                            // a status toast every 30s.
                            return;
                        }
                        // Include a real SVG check glyph so the
                        // page-saved toast reads as a definitive
                        // confirmation, not just a status sentence
                        // the user might miss.
                        var savedToastHtml = '<span class="mw-notification-saved-toast"><svg xmlns="http://www.w3.org/2000/svg" class="mw-notification-saved-toast__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg> <span class="mw-notification-saved-toast__label">Saved &amp; published</span></span>';
                        mw.notification.success(savedToastHtml, 7500);
                        return;
                    }

                    // Failure path — toast EVEN on silent auto-save
                    // so the user is alerted to lost work.
                    mw.notification.error('Something went wrong with saving the page.', 7500);
                }, 500);
            };

            if (saved) {
                saved.done(function () {
                    finishSave('success');
                });

                saved.fail(function () {
                    finishSave('error');
                });
            } else {
                finishSave('success');
            }
        },

        startAutoSave() {
            if (this.$data._autoSaveActive) return;
            this.$data._autoSaveActive = true;
            var self = this;
            this.$data._autoSaveTimer = setInterval(function () {
                if (!self.$data._dirty) return;
                self.save({ silent: true });
            }, AUTO_SAVE_INTERVAL_MS);
        },

        stopAutoSave() {
            if (this.$data._autoSaveTimer) {
                clearInterval(this.$data._autoSaveTimer);
                this.$data._autoSaveTimer = null;
            }
            this.$data._autoSaveActive = false;
        },

        markDirty() {
            this.$data._dirty = true;
        }
    },
    mounted() {
        //save on ctrl + s
        var saveButtonInstance = this;
        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            mw.app.editor.on('Ctrl+S', function (event) {
                event.preventDefault();
                saveButtonInstance.save();
            });
            // Start 30s draft auto-save once the canvas is ready.
            // Mark dirty on any DOM-level edit signal (Editor
            // change event + click-to-edit dblclick).
            try {
                saveButtonInstance.startAutoSave();
                var canvasWin = mw.app.canvas.getWindow();
                if (canvasWin && canvasWin.document) {
                    var markDirty = function () { saveButtonInstance.markDirty(); };
                    canvasWin.document.addEventListener('input', markDirty, true);
                    canvasWin.document.addEventListener('dblclick', markDirty, true);
                    canvasWin.document.addEventListener('drop', markDirty, true);
                }
                if (mw.app.editor && typeof mw.app.editor.on === 'function') {
                    mw.app.editor.on('change', function () { saveButtonInstance.markDirty(); });
                }
            } catch (_) { /* no-op */ }
        });
        document.addEventListener('keydown', function (event) {
            if (event.ctrlKey && event.keyCode === 83) {
                event.preventDefault();
                saveButtonInstance.save();
            }
        });
    },
    beforeUnmount() {
        this.stopAutoSave();
    },
    beforeDestroy() {
        // Vue 2 fallback name
        this.stopAutoSave();
    }
}
</script>
<template>
    <button class="btn btn-dark live-edit-toolbar-buttons" id="save-button" aria-label="Save and publish page (Ctrl+S)" @click="save()">
            <span class="font-weight-bold">Save &amp; Publish</span>
     </button>
</template>
