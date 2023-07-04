<script>

export default {
    methods: {

        save: async () => {
            const btn = document.getElementById('save-button');
            btn.classList.add('btn-loading');
            btn.disabled = true;
            await mw.app.save();
            btn.classList.remove('btn-loading');
            btn.disabled = false;
        }
    },
    data() {

    }
}
</script>
<template>
    <button class="btn btn-primary" id="save-button" @click="save()">
            Save
     </button>
</template>
