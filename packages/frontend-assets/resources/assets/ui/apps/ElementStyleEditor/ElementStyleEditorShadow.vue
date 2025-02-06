<template>
    <div class="d-flex">
        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
             style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path d="M12.2,3.9c4.5,0,8.1,3.6,8.1,8.1s-3.6,8.1-8.1,8.1S4.1,16.5,4.1,12S7.7,3.9,12.2,3.9"></path>
        </svg>
        <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showShadow }" v-on:click="toggleShadow">
            Shadow
        </span>
    </div>

    <div v-if="showShadow">
        <div>
            <ElementStyleEditorBoxShadow></ElementStyleEditorBoxShadow>
        </div>
    </div>
</template>

<script>
import ElementStyleEditorBoxShadow from './ElementStyleEditorBoxShadow.vue';

export default {
    components: {
        ElementStyleEditorBoxShadow
    },
    data() {
        return {
            'showShadow': false,
        };
    },
    mounted() {
        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow === 'showShadow') {
                this.showShadow = true;
            } else {
                this.showShadow = false;
            }
        });
    },
    methods: {
        toggleShadow: function () {
            this.showShadow = !this.showShadow;
        },
    }
}
</script>
