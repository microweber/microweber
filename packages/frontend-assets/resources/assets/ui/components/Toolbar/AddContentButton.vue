<template>
    <!--
      NOVICE #1 (task-2026-05-13-899d57) — the "ADD" word used to carry
      the `hidden` Tailwind class so the button rendered icon-only on
      every breakpoint. Novice persona reported: "There's a tiny plus
      sign in the top-left corner. I have no idea what it does." The
      most important call-to-action on the entire surface was invisible.
      Removed the `hidden` class so the label is always visible; kept
      the `sm:ms-2` margin so spacing remains correct on small screens.
      Also added a `title` so hovering surfaces the same affordance.
    -->
    <button v-on:click="openContentAddModal()" aria-label="Add content" title="Add new page, post, image, or product" class="btn me-2 live-edit-toolbar-buttons live-edit-toolbar-buttons-undo-redo">
        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="20" viewBox="0 -960 960 960" width="20"><path d="M434.5-434.5H191.869v-91H434.5v-242.631h91V-525.5h242.631v91H525.5v242.631h-91V-434.5Z"/></svg>

        <span class="ms-1 add-content-button-svg">ADD</span>
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
