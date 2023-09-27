<template>

    <div>
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

<script>
import ElementStyleEditorTypography from './ElementStyleEditorTypography.vue';
import ElementStyleEditorSpacing from './ElementStyleEditorSpacing.vue';
import ElementStyleEditorBackground from './ElementStyleEditorBackground.vue';
import ElementStyleEditorBorder from './ElementStyleEditorBorder.vue';

export default {
    components: {
        ElementStyleEditorTypography,
        ElementStyleEditorSpacing,
        ElementStyleEditorBackground,
        ElementStyleEditorBorder,
    },

    data() {
        return {
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


