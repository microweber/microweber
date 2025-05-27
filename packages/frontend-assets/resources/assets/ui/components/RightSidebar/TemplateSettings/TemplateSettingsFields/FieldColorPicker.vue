<template>
    <div>
        <ColorPicker 
            :color="currentValue"
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
            currentValue: ''
        };
    },
    mounted() {
        if (window.mw?.top()?.app?.cssEditor) {
            this.currentValue = window.mw.top().app.cssEditor.getPropertyForSelector(
                this.selectorToApply, 
                this.setting.fieldSettings.property
            ) || '';
        }
        
        if (window.mw?.top()?.app) {
            window.mw.top().app.on('setPropertyForSelector', this.onPropertyChange);
        }
    },
    methods: {
        updateValue(value) {
            this.currentValue = value;
            this.$emit('update', {
                selector: this.selectorToApply,
                property: this.setting.fieldSettings.property,
                value: value
            });
        },
        onPropertyChange(event) {
            if (event.selector === this.selectorToApply && 
                event.property === this.setting.fieldSettings.property) {
                this.currentValue = event.value || '';
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
