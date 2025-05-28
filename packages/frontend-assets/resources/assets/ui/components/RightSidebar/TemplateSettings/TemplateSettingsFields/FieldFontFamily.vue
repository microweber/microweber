<template>
    <div>
        <label class="live-edit-label">{{ setting.title }}</label>
        <FontPicker 
            v-model="setting.fieldSettings.value" 
            @change="updateValue"
            :label="setting.title"
        />
    </div>
</template>

<script>
import FontPicker from '../../../../apps/ElementStyleEditor/components/FontPicker.vue';

export default {
    components: {
        FontPicker
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
