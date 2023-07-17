<script>

export default {
    methods: {

        save: async () => {
            const btn = document.getElementById('save-button');
            btn.classList.add('btn-loading');
            btn.disabled = true;
            var saved = mw.app.canvas.getWindow().mw.drag.save()

            if (saved) {
                saved.success(function () {
                    btn.classList.remove('btn-loading');
                    btn.disabled = false;
                });

                saved.fail(function () {
                    btn.classList.remove('btn-loading');
                    btn.disabled = false;
                    mw.notification.error('Something went wrong with saving the page.',7500);
                });
            } else {
                btn.classList.remove('btn-loading');
                btn.disabled = false;
            }
        }
    },
    data() {

    }
}
</script>
<template>
    <button class="btn btn-dark live-edit-toolbar-buttons" id="save-button" @click="save()">
            <span class="font-weight-bold">SAVE</span>
     </button>
</template>
