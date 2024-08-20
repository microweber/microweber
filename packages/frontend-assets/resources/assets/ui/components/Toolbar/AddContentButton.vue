<template>
    <button v-on:click="openContentAddModal()" class=" btn btn-icon me-2 px-3 live-edit-toolbar-buttons live-edit-toolbar-buttons-undo-redo tblr-body-color">
        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M434.5-434.5H191.869v-91H434.5v-242.631h91V-525.5h242.631v91H525.5v242.631h-91V-434.5Z"/></svg>

        <span class="  ms-2" style="font-size: 12px; font-weight: bold;">ADD</span>
    </button>

</template>

<script>
export default {
    methods: {
        show: function (name) {
            this.emitter.emit('live-edit-ui-show', name);
        },
        openContentAddModal: function () {
            var event = new Event('openAddContentAction');
            window.dispatchEvent(event);
        },
    },
    data() {
        return {
            contentAddModalInstance: null
        };
    },
    mounted() {


    },

}
</script>
