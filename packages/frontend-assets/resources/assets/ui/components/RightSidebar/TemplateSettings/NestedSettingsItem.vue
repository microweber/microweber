<template>
    <div class="mt-3">
        <!-- Case 1: The setting is a field -->
        <div v-if="setting.fieldType">
            <!-- Display title/description for the field itself, if not a styleEditor button -->
            <div v-if="setting.fieldType === 'info' && setting.title">
<!--                 &lt;!&ndash; Using h5 or similar for field titles to distinguish from main group titles (h4 in parent) &ndash;&gt;-->
<!--                 <h5>{{ setting.title }}</h5>-->
<!--                 &lt;!&ndash; Description should only be shown if this is the active view in the parent component &ndash;&gt;-->
                 <p v-if="setting.description && isActive" class="text-muted small mt-0 mb-2">{{ setting.description }}</p>
            </div>
            <component
                :is="getComponentType(setting.fieldType)"
                :setting="{
                    ...setting,
                    fieldSettings: {
                        ...(setting.fieldSettings || {}),
                        value: this.currentValue // Use computed currentValue
                    }
                }"
                :selector-to-apply="selectorToApply"
                :root-selector="rootSelector"
                @update="handleUpdate"
                @batch-update="handleBatchUpdate"
                @open-style-editor="$emit('open-style-editor', $event)"
            />
        </div>

        <!-- Case 2: The setting is a navigable group (not a field, but has a URL and title) -->
        <div v-else-if="setting.url && setting.title && setting.title !== 'Main'">
            <a @click="$emit('navigate', setting.url)"
               class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
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
import FieldBackButton from './TemplateSettingsFields/FieldBackButton.vue';
import FieldStylePack from './TemplateSettingsFields/FieldStylePack.vue';

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
        FieldStyleEditor,
        FieldStylePack,
        FieldBackButton
    },
    inject: ['templateSettings'],
    props: {
        setting: {
            type: Object,
            required: true
        },
        rootSelector: { // This is the root selector for the current group/context
            type: String,
            default: ''
        },
        isActive: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        selectorToApply() {
            if (!this.setting.selectors || this.setting.selectors.length === 0) return this.rootSelector || '';

            let baseSelector = this.setting.selectors[this.setting.selectors.length - 1];
            let effectiveRootSelector = this.setting.rootSelector || this.rootSelector || ''; // Prefer setting's own rootSelector if defined

            if (effectiveRootSelector && baseSelector) {
                if (baseSelector === ':root') {
                    return effectiveRootSelector;
                } else {
                    // Avoid duplicating root selector if baseSelector already contains it (e.g. from a more specific item)
                    if (baseSelector.startsWith(effectiveRootSelector) && effectiveRootSelector !== '') {
                        return baseSelector;
                    }
                    const rs = effectiveRootSelector.trimEnd();
                    const s = baseSelector.trimStart();
                    return `${rs} ${s}`.trim();
                }
            }
            return baseSelector || effectiveRootSelector || '';
        },        currentValue() {
            if (!this.setting.fieldSettings?.property) {
                return this.setting.fieldSettings?.value || '';
            }

            const property = this.setting.fieldSettings.property;
            const selector = this.selectorToApply;

            // Access the getCssPropertyValue method from the TemplateSettings component via injection
            if (this.templateSettings && typeof this.templateSettings.getCssPropertyValue === 'function') {
                const valueFromParent = this.templateSettings.getCssPropertyValue(selector, property);
                if (valueFromParent !== undefined && valueFromParent !== null) {
                    return valueFromParent;
                }
            }

            // Return default value if no other value is found
            return this.setting.fieldSettings?.value || '';
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
                case 'stylePack': return 'field-style-pack';
                default:
                    console.warn('Unknown fieldType:', fieldType, 'for setting:', this.setting.title);
                    return null;
            }
        },
        handleUpdate(eventData) { // eventData is {selector, property, value} or just value from simple fields
            // Ensure the eventData has the selector and property if not already provided by the child field component
            const dataToEmit = {
                selector: eventData.selector || this.selectorToApply,
                property: eventData.property || this.setting.fieldSettings?.property,
                value: eventData.value !== undefined ? eventData.value : eventData, // some fields might emit value directly
                ...eventData // allow eventData to override if it has its own selector/property
            };
            this.$emit('update', dataToEmit);
        },
        handleBatchUpdate(updates) { // updates is an array of {selector, property, value}
            // This is for complex fields like colorPalette or clearAll that modify multiple properties
            // The actual updateCssProperty calls will be done in the parent (TemplateSettings)
            // We just need to ensure the selectors in `updates` are correctly contextualized if necessary.
            const processedUpdates = updates.map(update => {
                // If a child component provides a full selector, use it.
                // Otherwise, assume the update is for the current item's selectorToApply.
                return {
                    ...update,
                    selector: update.selector || this.selectorToApply,
                };
            });
            this.$emit('batch-update', processedUpdates); // Parent will handle these
        },

        onCssPropertyChanged(data) {
            // This method will handle CSS property changes from the parent TemplateSettings component
            // and will trigger a re-render of the field with the new value
            if (data.selector === this.selectorToApply && data.property === this.setting.fieldSettings?.property) {
                // Force the component to refresh since currentValue is computed and will get the new value
                this.$forceUpdate();
            }
        }
    },    mounted() {
        // Register this component to receive CSS property change notifications from TemplateSettings
        if (this.templateSettings && typeof this.templateSettings.registerPropertyChangeListener === 'function') {
            this.templateSettings.registerPropertyChangeListener(this.onCssPropertyChanged);
        }
    },
    beforeUnmount() {
        // Unregister this component from CSS property change notifications
        if (this.templateSettings && typeof this.templateSettings.unregisterPropertyChangeListener === 'function') {
            this.templateSettings.unregisterPropertyChangeListener(this.onCssPropertyChanged);
        }
    }
};
</script>
