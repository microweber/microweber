<script>

export default {
    methods: {

        save: () => {
            var btn = document.getElementById('save-button');
            btn.classList.add('btn-loading');
            btn.disabled = true;
            var canvasWindow = mw.app.canvas.getWindow();
            if(canvasWindow.mw && typeof canvasWindow.mw.drag === 'undefined'){
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                mw.notification.error('Something went wrong with saving the page.',7500);
                return;
            }
            var saved = canvasWindow.mw.drag.save()

            if (saved) {
                saved.success(function () {
                    btn.classList.remove('btn-loading');
                    btn.disabled = false;
                    mw.notification.success('Page saved successfully.',7500);
                });

                saved.fail(function () {
                    btn.classList.remove('btn-loading');
                    btn.disabled = false;
                    mw.notification.error('Something went wrong with saving the page.',7500);
                });
            } else {
                btn.classList.remove('btn-loading');
                btn.disabled = false;
                mw.notification.success('Page saved successfully.',7500);
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
            if (event.ctrlKey && event.key === 's') {
                event.preventDefault();
                saveButtonInstance.save();
            }
        });
    }
}
</script>
<template>
    <button class="btn btn-dark live-edit-toolbar-buttons" id="save-button" @click="save()">
            <span class="font-weight-bold">SAVE</span>
     </button>
</template>
