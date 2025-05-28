<template>
    <DropdownSmall 
        v-model="setting.fieldSettings.value"
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
    computed: {
        formattedOptions() {
            return Object.entries(this.setting.fieldSettings.options).map(([key, value]) => ({
                key,
                value
            }));
        }
    },
    methods: {
        updateValue(value) {
            this.$emit('update', {
                selector: this.selectorToApply,
                property: this.setting.fieldSettings.property,
                value: value
            });
        }
    },
    mounted() {
        // Get initial value from parent's CSS property cache
        if (this.setting.fieldSettings?.property && this.templateSettings) {
            const currentValue = this.templateSettings.getCssPropertyValue(this.selectorToApply, this.setting.fieldSettings.property);
            if (currentValue !== undefined && currentValue !== null) {
                this.setting.fieldSettings.value = currentValue;
            }
        }
    }
};
</script>
