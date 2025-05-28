<template>
    <SliderSmall
        :label="setting.title"
        :modelValue="currentSliderValue"
        :min="setting.fieldSettings.min"
        :max="setting.fieldSettings.max"
        :step="setting.fieldSettings.step"
        :unit="setting.fieldSettings.unit"
        @update:modelValue="handleSliderUpdate"
    />
</template>

<script>
import SliderSmall from "../../../../apps/ElementStyleEditor/components/SliderSmall.vue";

export default {
    components: { SliderSmall },
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
            currentSliderValue: 0
        };
    },
    watch: {
        // Watch for changes in the setting's value
        'setting.fieldSettings.value': {
            handler(newValue) {
                if (newValue !== undefined && newValue !== null) {
                    const strippedValue = this.stripUnit(newValue);
                    if (strippedValue !== this.currentSliderValue) {
                        this.currentSliderValue = strippedValue;
                    }
                }
            },
            immediate: true
        }
    },
    mounted() {
        // Initialize the slider value from parent component's cached CSS values
        if (this.templateSettings && this.selectorToApply && this.setting.fieldSettings.property) {
            const cssValue = this.templateSettings.getCssPropertyValue(this.selectorToApply, this.setting.fieldSettings.property);
            if (cssValue) {
                this.currentSliderValue = this.stripUnit(cssValue);
            } else if (this.setting.fieldSettings.value) {
                this.currentSliderValue = this.stripUnit(this.setting.fieldSettings.value);
            }
        } else if (this.setting.fieldSettings.value) {
            this.currentSliderValue = this.stripUnit(this.setting.fieldSettings.value);
        }
    },
    methods: {
        stripUnit(value) {
            if (!value) return 0;
            const unit = this.setting.fieldSettings.unit || '';
            return parseFloat(String(value).replace(unit, ''));
        },
        handleSliderUpdate(newValue) {
            this.currentSliderValue = newValue;
            const valueWithUnit = newValue + (this.setting.fieldSettings.unit || '');
            this.$emit('update', {
                selector: this.selectorToApply,
                property: this.setting.fieldSettings.property,
                value: valueWithUnit
            });
        }
    }
};
</script>
