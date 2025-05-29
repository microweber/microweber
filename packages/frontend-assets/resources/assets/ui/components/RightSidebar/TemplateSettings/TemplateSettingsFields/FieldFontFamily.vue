<template>
    <div>
        <label class="live-edit-label">{{ setting.title }}</label>
        <FontPicker
            v-model="currentFontValue"
            v-bind:value="currentFontValue"
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
    data() {
        return {
            currentFontValue: null
        };
    },
    watch: {
        // Watch for changes in the setting's value
        'setting.fieldSettings.value': {
            handler(newValue) {
                if (newValue !== this.currentFontValue) {
                    this.currentFontValue = newValue;
                }
            },
            immediate: true
        }
    },
    methods: {
        updateValue(value) {


            console.log('Updating font value:', value);
            console.log('Updating font selectorToApply:', this.selectorToApply);

            this.currentFontValue = value;
            this.$emit('update', {
                selector: this.selectorToApply,
                property: this.setting.fieldSettings.property,
                value: value
            });
        }
    },
    mounted() {
        // Initialize the font value from parent component's cached CSS values
        if (this.templateSettings && this.selectorToApply && this.setting.fieldSettings?.property) {
            const cssValue = this.templateSettings.getCssPropertyValue(this.selectorToApply, this.setting.fieldSettings.property);
            if (cssValue) {
                this.currentFontValue = cssValue;
            } else {
                this.currentFontValue = this.setting.fieldSettings.value || '';
            }
        } else {
            this.currentFontValue = this.setting.fieldSettings.value || '';
        }
    }
};
</script>
