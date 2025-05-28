<template>
    <div>

        <ColorPicker
            :color="currentColorValue"
            @change="updateValue"
            :label="setting.title"
        />
    </div>
</template>

<script>
import ColorPicker from '../../../../apps/ElementStyleEditor/components/ColorPicker.vue';

export default {
    components: {
        ColorPicker
    },
    inject: ['templateSettings'],
    props: {
        setting: {
            type: Object,
            required: true
        },
        selectorToApply: {
            type: String,
            default: ''
        }
    },
    data() {
        return {
            currentColorValue: null
        };
    },
    watch: {
        // Watch for changes in the setting's value
        'setting.fieldSettings.value': {
            handler(newValue) {
                if (newValue !== this.currentColorValue) {
                    this.currentColorValue = newValue;
                }
            },
            immediate: true
        }
    },
    mounted() {
        // Initialize the color value from parent component's cached CSS values
        if (this.templateSettings && this.selectorToApply && this.setting.fieldSettings.property) {

            const cssValue = this.templateSettings.getCssPropertyValue(this.selectorToApply, this.setting.fieldSettings.property);


            if (cssValue) {
                this.currentColorValue = cssValue;
            } else {
                this.currentColorValue = this.setting.fieldSettings.value || '';
            }
        } else {
            this.currentColorValue = this.setting.fieldSettings.value || '';
        }
    },
    methods: {
        updateValue(value) {
            this.currentColorValue = value;
            this.$emit('update', {
                selector: this.selectorToApply,
                property: this.setting.fieldSettings.property,
                value: value
            });
        }
    }
};
</script>
