<template>
    <div class="mt-3">
        <div v-if="setting.title">
            <h4 v-if="setting.settings?.length">{{ setting.title }}</h4>
            <p v-if="setting.description">{{ setting.description }}</p>
            
            <div v-if="setting.fieldType" class="mt-3">
                <component 
                    :is="getComponentType(setting.fieldType)"
                    :setting="setting"
                    :selector-to-apply="selectorToApply"
                    :root-selector="rootSelector"
                    @update="$emit('update', $event)"
                    @open-style-editor="$emit('open-style-editor', $event)" 
                />
            </div>

            <div v-else-if="setting.url && !setting.settings?.length">
                <a @click="$emit('navigate', setting.url)" class="mw-admin-action-links">
                    {{ setting.title }}
                </a>
            </div>
        </div>

        <div v-if="setting.settings && setting.settings.length > 0" class="nested-settings">
            <div v-for="(nestedSetting, idx) in setting.settings" :key="idx" class="mt-3 mb-4">
                <nested-settings-item
                    :setting="nestedSetting"
                    :root-selector="rootSelector"
                    @navigate="$emit('navigate', $event)"
                    @update="$emit('update', $event)"
                    @open-style-editor="$emit('open-style-editor', $event)" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: 'NestedSettingsItem',
    props: {
        setting: {
            type: Object,
            required: true
        },
        rootSelector: {
            type: String,
            default: ''
        }
    },
    computed: {
        selectorToApply() {
            if (!this.setting.selectors) return '';
            
            let selector = this.setting.selectors[this.setting.selectors.length - 1];
            
            if (this.rootSelector && selector) {
                if (selector === ':root') {
                    return this.rootSelector;
                } else {
                    return `${this.rootSelector} ${selector}`;
                }
            }
            
            return selector || '';
        }
    },
    methods: {
        getComponentType(fieldType) {
            switch (fieldType) {
                case 'colorPicker': return 'field-color-picker';
                case 'rangeSlider': return 'field-range-slider';
                case 'dropdown': return 'field-dropdown';
                case 'fontFamily': return 'field-font-family';
                case 'clearAll': return 'field-clear-all';
                case 'colorPalette': return 'field-color-palette';
                case 'button': return 'field-button';
                case 'infoBox': return 'field-info-box';
                case 'styleEditor': return 'field-style-editor';
                default: return null;
            }
        }
    }
};
</script>
