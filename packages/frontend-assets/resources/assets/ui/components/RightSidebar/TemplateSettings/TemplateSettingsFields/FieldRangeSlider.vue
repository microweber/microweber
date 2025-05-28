<template>
    <SliderSmall
        :label="setting.title"
        :modelValue="stripUnit(currentValue)"
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
            currentValue: '0'
        };
    },
    mounted() {
        if (window.mw?.top()?.app?.cssEditor) {
            let val = window.mw.top().app.cssEditor.getPropertyForSelector(
                this.selectorToApply,
                this.setting.fieldSettings.property
            ) || '0' + this.setting.fieldSettings.unit;

            this.currentValue = this.stripUnit(val);
        }

        if (window.mw?.top()?.app) {
            window.mw.top().app.on('setPropertyForSelector', this.onPropertyChange);
        }
    },
    methods: {
        stripUnit(value) {
            if (!value) return '0';
            return String(value).replace(this.setting.fieldSettings.unit, '');
        },
        handleSliderUpdate(newValue) {
            this.currentValue = newValue;
            const valueWithUnit = newValue + this.setting.fieldSettings.unit;

            this.$emit('update', {
                selector: this.selectorToApply,
                property: this.setting.fieldSettings.property,
                value: valueWithUnit
            });
        },
        onPropertyChange(event) {
            if (event.selector === this.selectorToApply &&
                event.property === this.setting.fieldSettings.property) {
                this.currentValue = this.stripUnit(event.value);
            }
        }
    },
    beforeUnmount() {
        if (window.mw?.top()?.app) {
            window.mw.top().app.off('setPropertyForSelector', this.onPropertyChange);
        }
    }
};
</script>
