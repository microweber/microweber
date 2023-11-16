<template>

    <div class="d-flex">
        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
             style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path d="M12.2,3.9c4.5,0,8.1,3.6,8.1,8.1s-3.6,8.1-8.1,8.1S4.1,16.5,4.1,12S7.7,3.9,12.2,3.9"></path>
        </svg>
        <b class="mw-admin-action-links ms-3" :class="{'active': showShadow }" v-on:click="toggleShadow">
            Shadow
        </b>
    </div>

    <div v-if="showShadow">
        <a class="mw-admin-action-links ms-3" :class="{'active': showTextShadowOptions }"

           v-on:click="toggleTextShadow">
            Text shadow
        </a>
        <a class="mw-admin-action-links ms-3" :class="{'active': showBoxShadowOptions }"
           v-on:click="toggleBoxShadow">
            Box shadow
        </a>

        <br>


        <div :class="{'d-none': !showTextShadowOptions }">

            <ElementStyleEditorTextShadow></ElementStyleEditorTextShadow>

        </div>
        <div :class="{'d-none': !showBoxShadowOptions }">
            <ElementStyleEditorBoxShadow></ElementStyleEditorBoxShadow>

        </div>

    </div>
</template>

<script>
import ElementStyleEditorBoxShadow from './ElementStyleEditorBoxShadow.vue';
import ElementStyleEditorTextShadow from './ElementStyleEditorTextShadow.vue';

export default {
    components: {
        ElementStyleEditorBoxShadow,
        ElementStyleEditorTextShadow
    },
    data() {
        return {
            'showShadow': false,
            'showBoxShadowOptions': false,
            'showTextShadowOptions': false,
        };
    },
    mounted() {

        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {


            if
            (
                elementStyleEditorShow === 'showShadow' ||
                elementStyleEditorShow === 'showBoxShadowOptions' ||
                elementStyleEditorShow === 'showTextShadowOptions'
            ) {
                this.showShadow = true;

                if (elementStyleEditorShow === 'showTextShadowOptions') {
                    this.showTextShadowOptions = true;
                    this.showBoxShadowOptions = false;
                } else if (elementStyleEditorShow === 'showBoxShadowOptions') {
                    this.showTextShadowOptions = false;
                    this.showBoxShadowOptions = true;
                } else {
                    this.showTextShadowOptions = false;
                    this.showBoxShadowOptions = false;
                }

            } else {
                this.showShadow = false;
            }
        });
    },
    methods: {

        toggleShadow: function () {

            this.emitter.emit('element-style-editor-show', 'showShadow');
        },

        toggleTextShadow: function () {

            this.emitter.emit('element-style-editor-show', 'showTextShadowOptions');
        },
        toggleBoxShadow: function () {
             this.emitter.emit('element-style-editor-show', 'showBoxShadowOptions');
        },
    }
}
</script>
