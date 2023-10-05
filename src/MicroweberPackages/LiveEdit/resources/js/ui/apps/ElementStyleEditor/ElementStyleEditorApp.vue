<template>




    <div>
        <div v-show="showElementSelector">
            <ElementStyleEditorElementSelector></ElementStyleEditorElementSelector>
        </div>

        <div v-show="showTypography">
            <ElementStyleEditorTypography></ElementStyleEditorTypography>
        </div>
        <div v-show="showSpacing">
            <ElementStyleEditorSpacing></ElementStyleEditorSpacing>
        </div>

        <div v-show="showContainer">
            <ElementStyleEditorContainer></ElementStyleEditorContainer>
        </div>
        <div v-show="showBackground">
            <ElementStyleEditorBackground></ElementStyleEditorBackground>
        </div>
        <div v-show="showBorder">
            <ElementStyleEditorBorder></ElementStyleEditorBorder>
        </div>
    </div>

</template>
<style src="./ElementStyleEditor.css"></style>

<script>
import ElementStyleEditorTypography from './ElementStyleEditorTypography.vue';
import ElementStyleEditorSpacing from './ElementStyleEditorSpacing.vue';
import ElementStyleEditorBackground from './ElementStyleEditorBackground.vue';
import ElementStyleEditorBorder from './ElementStyleEditorBorder.vue';
import ElementStyleEditorElementSelector from './ElementStyleEditorElementSelector.vue';
import ElementStyleEditorContainer from './ElementStyleEditorContainer.vue';
import ElementStyleEditorAnimations from './ElementStyleEditorAnimations.vue';

export default {
    components: {
        ElementStyleEditorElementSelector,
        ElementStyleEditorTypography,
        ElementStyleEditorSpacing,
        ElementStyleEditorBackground,
        ElementStyleEditorBorder,
        ElementStyleEditorContainer,
        ElementStyleEditorAnimations,
    },

    data() {
        return {
            showElementSelector: false,
            showTypography: false,
            showSpacing: false,
            showBackground: false,
            showBorder: false,
            showContainer: false,
            showAnimations: false,
        }
    },

    methods: {},
    mounted() {

        mw.top().app.on('cssEditorSettings', (settings) => {

            if (settings.fieldSettings.components) {

                this.showTypography = false;
                this.showSpacing = false;
                this.showBackground = false;
                this.showBorder = false;
                this.showElementSelector = false;
                this.showContainer = false;

                if (settings.fieldSettings.components.includes('elementSelector')) {
                    this.showElementSelector = true;
                }
                if (settings.fieldSettings.components.includes('typography')) {
                    this.showTypography = true;
                }
                if (settings.fieldSettings.components.includes('spacing')) {
                    this.showSpacing = true;
                }
                if (settings.fieldSettings.components.includes('background')) {
                    this.showBackground = true;
                }
                if (settings.fieldSettings.components.includes('border')) {
                    this.showBorder = true;
                }
                if (settings.fieldSettings.components.includes('container')) {
                    this.showContainer = true;
                }
            }
        });

    },


}
</script>


