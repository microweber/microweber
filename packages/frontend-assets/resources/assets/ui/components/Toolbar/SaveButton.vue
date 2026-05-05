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
                        mw.notification.success('Page saved successfully.', 7500);
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
