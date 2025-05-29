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
    }, computed: {
        isLayoutMode() {
            return this.templateSettings && this.templateSettings.applyMode === 'layout';
        },

        currentLayoutId() {
            return this.templateSettings && this.isLayoutMode ? this.templateSettings.activeLayoutId : null;
        }
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
        },

        // Also watch for changes in the layout mode or layout ID
        isLayoutMode() {
            // When layout mode changes, refresh the component
            this.updateFromCssValues();
        },

        currentLayoutId() {
            // When layout ID changes, refresh the component
            if (this.isLayoutMode) {
                this.updateFromCssValues();
            }
        }
    }, methods: {
        updateValue(value) {
            this.currentFontValue = value;

            // Get effective selector based on current mode
            let effectiveSelector = this.getEffectiveSelector();


            // Emit the update event with the new font value
            this.$emit('update', {
                selector: effectiveSelector,
                property: this.setting.fieldSettings.property,
                value: value
            });
        },        // Get the effective selector based on the current mode
        getEffectiveSelector() {
            try {
                // If we don't have templateSettings, return the default selector
                if (!this.templateSettings) {
                    return this.selectorToApply;
                }

                // Check if we have direct access to the parent component's methods
                if (typeof this.templateSettings.transformSelectorBasedOnMode === 'function') {
                    // Use the parent's transform method directly if available
                    const rootSelector = this.templateSettings.getRootSelector ?
                        this.templateSettings.getRootSelector() : '';

                    // Use the parent's transform method
                    const transformed = this.templateSettings.transformSelectorBasedOnMode(
                        this.selectorToApply,
                        rootSelector
                    );

                    console.log('Using parent transformSelectorBasedOnMode:', transformed);
                    return transformed;
                }

                // Fall back to our own implementation
                const isLayoutMode = this.templateSettings.applyMode === 'layout';

                if (isLayoutMode) {
                    const layoutId = this.templateSettings.activeLayoutId;
                    console.log('Manual transform - Layout mode detected:', isLayoutMode);
                    console.log('Manual transform - Layout ID:', layoutId);

                    if (layoutId && layoutId !== 'None') {
                        // Handle specific cases
                        if (this.selectorToApply === ':root') {
                            return '#' + layoutId;
                        }

                        if (this.selectorToApply) {
                            // Don't duplicate the ID if it's already there
                            if (this.selectorToApply.includes('#' + layoutId)) {
                                return this.selectorToApply;
                            }
                            return '#' + layoutId + ' ' + this.selectorToApply;
                        }

                        return '#' + layoutId;
                    }
                }

                return this.selectorToApply;
            } catch (err) {
                console.error('Error in getEffectiveSelector:', err);
                return this.selectorToApply;
            }
        }, onCssPropertyChanged(data) {
            // This method handles CSS property changes from the parent TemplateSettings component
            // and will update the displayed font value when the property changes or mode changes
            const effectiveSelector = this.getEffectiveSelector();

            if ((data.selector === this.selectorToApply || data.selector === effectiveSelector) &&
                data.property === this.setting.fieldSettings?.property) {
                this.currentFontValue = data.value || '';
            }
        },

        updateFromCssValues() {
            // Helper method to update the font value from CSS values based on current mode
            if (this.templateSettings && this.setting.fieldSettings?.property) {
                const effectiveSelector = this.getEffectiveSelector();

                console.log('Updating from CSS values with selector:', effectiveSelector);

                const cssValue = this.templateSettings.getCssPropertyValue(
                    effectiveSelector,
                    this.setting.fieldSettings.property
                );

                if (cssValue) {
                    this.currentFontValue = cssValue;
                    console.log('Updated font value from CSS:', cssValue);
                } else {
                    this.currentFontValue = this.setting.fieldSettings.value || '';
                    console.log('No CSS value found, using fallback:', this.currentFontValue);
                }
            }
        }
    }, mounted() {


        // Initialize the font value from parent component's cached CSS values
        this.updateFromCssValues();

        // Register for property changes to stay in sync with the current mode
        if (this.templateSettings && typeof this.templateSettings.registerPropertyChangeListener === 'function') {
            this.templateSettings.registerPropertyChangeListener(this.onCssPropertyChanged);
        }
    },
    beforeUnmount() {
        // Clean up event listener
        if (this.templateSettings && typeof this.templateSettings.unregisterPropertyChangeListener === 'function') {
            this.templateSettings.unregisterPropertyChangeListener(this.onCssPropertyChanged);
        }
    }
};
</script>
