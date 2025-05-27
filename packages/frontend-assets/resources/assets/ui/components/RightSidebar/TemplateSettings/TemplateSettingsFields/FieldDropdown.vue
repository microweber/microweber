<template>
    <DropdownSmall 
        v-model="currentValue"
        :label="setting.title"
        :options="formattedOptions"
        @update:modelValue="updateValue"
    />
</template>

<script>
import DropdownSmall from '../../../../apps/ElementStyleEditor/components/DropdownSmall.vue';

export default {
    components: {
        DropdownSmall
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
    computed: {
        formattedOptions() {
            return Object.entries(this.setting.fieldSettings.options).map(([key, value]) => ({
                key,
                value
            }));
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
