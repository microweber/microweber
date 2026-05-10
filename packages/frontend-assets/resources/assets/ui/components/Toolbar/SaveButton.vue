<script>

export default {
    methods: {

        save: () => {
            var btn = document.getElementById('save-button');
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
            // visible" rather than only saving the page DOM —
            // task-2026-04-29-dc57b7. Dispatch a window event that the
            // iframe-page Alpine listener picks up and forwards to
            // $wire.callMountedAction(); the Filament action's own
            // submit handler then runs the form's create/update logic.
            try {
                window.dispatchEvent(new Event('liveEditSaveCallMountedAction'));
            } catch (_) { /* no-op */ }

            var canvasWindow = mw.app.canvas.getWindow();
            if(canvasWindow.mw && typeof canvasWindow.mw.drag === 'undefined'){
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                mw.notification.error('Something went wrong with saving the page.',7500);
                return;
            }
            var saved = canvasWindow.mw.drag.save()

            var finishSave = function (result) {
                setTimeout(function () {
                    btn.classList.remove('btn-loading');
                    btn.disabled = false;
                    window.removeEventListener('liveEditMountedActionValidationFailed', onMountedActionValidationFailed);

                    if (mountedActionValidationFailed) {
                        mw.notification.error('Please fix the highlighted required field before saving.', 7500);
                        return;
                    }

                    if (result === 'success') {
                        // AI-174 (cycle-151 2026-05-10): include a
                        // real SVG ✓ glyph so the page-saved toast
                        // reads as a definitive confirmation, not just
                        // a status sentence the user might miss.
                        // The previous plain "Page saved successfully."
                        // was easy to dismiss as a passive status —
                        // users reported being uncertain whether their
                        // work had committed.
                        // mw.notification.append() uses template-literal
                        // interpolation (notification.js:120) so HTML
                        // renders inline — the SVG sits inside the
                        // existing .mw-success-bg toast chrome.
                        var savedToastHtml = '<span class="mw-notification-saved-toast"><svg xmlns="http://www.w3.org/2000/svg" class="mw-notification-saved-toast__icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="20 6 9 17 4 12"></polyline></svg> <span class="mw-notification-saved-toast__label">Page saved</span></span>';
                        mw.notification.success(savedToastHtml, 7500);
                        return;
                    }

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


        }
    },
    data() {

    },
    mounted() {
        //save on ctrl + s

        var saveButtonInstance = this;
        mw.app.canvas.on('liveEditCanvasLoaded', () => {
            mw.app.editor.on('Ctrl+S', function (event) {
                event.preventDefault();
                saveButtonInstance.save();
            });
        });
        document.addEventListener('keydown', function (event) {
            if (event.ctrlKey && event.keyCode === 83) {
                event.preventDefault();
                saveButtonInstance.save();
            }
        });
    }
}
</script>
<template>
    <button class="btn btn-dark live-edit-toolbar-buttons" id="save-button" aria-label="Save page (Ctrl+S)" @click="save()">
            <span class="font-weight-bold">SAVE</span>
     </button>
</template>
