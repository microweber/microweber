<template>
    <div class="s-field">
        <label>Image</label>
        <div class="s-field-content">
            <div class="mw-ui-btn-nav" id="background-image-nav">

                <span class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip mdi mdi-folder-image mdi-17px" data-tip="Select background image" id="background-select-item"><span class="background-preview" style="background-image: none;"></span></span>

                <span class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip mdi mdi-folder-image mdi-17px" data-tip="Select gradient" id="background-select-gradient" style="display: none"><span class="background-gradient"></span></span>


                <span id="background-remove" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip" data-tip="Remove background" data-tipposition="top-right"><span class="mdi mdi-delete"></span></span>
                <span id="background-reset" class="mw-ui-btn mw-ui-btn-outline mw-ui-btn-small tip" data-tip="Reset background" data-tipposition="top-right"><span class="mdi mdi-history"></span></span>
            </div>
        </div>
    </div>



    <div class="s-field">
        <label>Text align</label>
        <div class="s-field-content">
            <div class="text-align">
                <span class="ta-left active" data-value="left"><span class="mdi mdi-format-align-left"></span></span>
                <span class="ta-center" data-value="center"><span class="mdi mdi-format-align-center"></span></span>
                <span class="ta-right" data-value="right"><span class="mdi mdi-format-align-right"></span></span>
                <span class="ta-justify" data-value="justify"><span class="mdi mdi-format-align-justify"></span></span>
            </div>
        </div>
    </div>


    <div class="s-field">
        <label>Color</label>
        <div class="s-field-content">
            <div class="mw-multiple-fields">
                <div class="mw-field mw-field-flat" data-size="medium">
                    <span class="mw-field-color-indicator"><span class="mw-field-color-indicator-display" style="background-color: rgb(33, 37, 41);"></span></span>
                    <input type="text" class="colorField unit ready mw-color-picker-field" data-prop="color" autocomplete="off" placeholder="#ffffff"><span class="reset-field  tip" data-tipposition="top-right" data-tip="Restore default value"><i class="mdi mdi-history"></i></span>
                </div>
            </div>
        </div>
    </div>




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

export default {
    components: {
        ElementStyleEditorElementSelector,
        ElementStyleEditorTypography,
        ElementStyleEditorSpacing,
        ElementStyleEditorBackground,
        ElementStyleEditorBorder,
    },

    data() {
        return {
            showElementSelector: false,
            showTypography: false,
            showSpacing: false,
            showBackground: false,
            showBorder: false,
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
            }
        });

    },


}
</script>


