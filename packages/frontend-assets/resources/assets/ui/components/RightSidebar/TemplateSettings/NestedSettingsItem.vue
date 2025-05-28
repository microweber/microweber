<template>
    <div class="mt-3">
        <!-- Case 1: The setting is a field -->
        <div v-if="setting.fieldType">
            <!-- Display title/description for the field itself, if not a styleEditor button -->
            <div v-if="setting.fieldType !== 'styleEditor' && setting.title">
                 <!-- Using h5 or similar for field titles to distinguish from main group titles (h4 in parent) -->
                 <h5>{{ setting.title }}</h5>
                 <!-- Description should only be shown if this is the active view in the parent component -->
                 <p v-if="setting.description && isActive" class="text-muted small mt-0 mb-2">{{ setting.description }}</p>
            </div>
            <component
                :is="getComponentType(setting.fieldType)"
                :setting="setting"
                :selector-to-apply="selectorToApply"
                :root-selector="rootSelector"
                @update="$emit('update', $event)"
                @open-style-editor="$emit('open-style-editor', $event)"
            />
        </div>

        <!-- Case 2: The setting is a navigable group (not a field, but has a URL and title) -->
        <div v-else-if="setting.url && setting.title">
            <a @click="$emit('navigate', setting.url)"
               class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group cursor-pointer mb-1 d-block">
                {{ setting.title }}
            </a>
            <!-- Only show description when this item is the current path (active) -->
            <p v-if="setting.description && isActive" class="text-muted small mt-0 mb-2">{{ setting.description }}</p>
        </div>

        <!-- Fallback: If it's not a field and not a URL-based link, but has a title (e.g. a static title/description item) -->
        <div v-else-if="setting.title">
            <h5>{{ setting.title }}</h5>
            <!-- Only show description when this item is active -->
            <p v-if="setting.description && isActive" class="text-muted small mt-0 mb-2">{{ setting.description }}</p>
        </div>
    </div>
</template>

<script>
import FieldColorPicker from './TemplateSettingsFields/FieldColorPicker.vue';
import FieldRangeSlider from './TemplateSettingsFields/FieldRangeSlider.vue';
import FieldDropdown from './TemplateSettingsFields/FieldDropdown.vue';
import FieldFontFamily from './TemplateSettingsFields/FieldFontFamily.vue';
import FieldClearAll from './TemplateSettingsFields/FieldClearAll.vue';
import FieldColorPalette from './TemplateSettingsFields/FieldColorPalette.vue';
import FieldButton from './TemplateSettingsFields/FieldButton.vue';
import FieldInfoBox from './TemplateSettingsFields/FieldInfoBox.vue';
import FieldStyleEditor from './TemplateSettingsFields/FieldStyleEditor.vue';

export default {
    name: 'NestedSettingsItem',
    components: {
        FieldColorPicker,
        FieldRangeSlider,
        FieldDropdown,
        FieldFontFamily,
        FieldClearAll,
        FieldColorPalette,
        FieldButton,
        FieldInfoBox,
        FieldStyleEditor
    },
    props: {
        setting: {
            type: Object,
            required: true
        },
        rootSelector: {
            type: String,
            default: ''
        },
        // New prop to determine if this setting is the active/current one
        isActive: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        selectorToApply() {
            if (!this.setting.selectors || this.setting.selectors.length === 0) return '';

            let selector = this.setting.selectors[this.setting.selectors.length - 1];

            if (this.rootSelector && selector) {
                if (selector === ':root') {
                    return this.rootSelector;
                } else {
                    const rs = this.rootSelector.trimEnd();
                    const s = selector.trimStart();
                    return `${rs} ${s}`.trim();
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
                default:
                    console.warn('Unknown fieldType:', fieldType, 'for setting:', this.setting.title);
                    return null;
            }
        }
    }
};
</script>
