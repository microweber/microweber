<template>
    <div>
        <ColorPicker
            :color="effectiveColorValue"
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
            localColorValue: null
        };
    },
    computed: {
        isLayoutMode() {
            return this.templateSettings && this.templateSettings.applyMode === 'layout';
        },
        currentLayoutId() {
            return this.templateSettings && this.isLayoutMode ? this.templateSettings.activeLayoutId : null;
        },
        effectiveSelector() {
            return this.getEffectiveSelector();
        },
        effectiveColorValue() {


            // Otherwise, fetch the current value based on effective selector
            if (this.templateSettings && this.effectiveSelector && this.setting.fieldSettings.property) {
                const cssValue = this.templateSettings.getCssPropertyValue(
                    this.effectiveSelector,
                    this.setting.fieldSettings.property
                );


                console.log('Effective CSS Value:', cssValue, 'for selector:', this.effectiveSelector);

                if (cssValue) {
                    return cssValue;
                }
            }

            // Fallback to setting's value if no CSS value found
            return this.setting.fieldSettings.value || '';
        }
    },
    watch: {
        // Watch for changes in the setting's base value
        'setting.fieldSettings.value': {
            handler(newValue) {
                this.updateFromCssValues();
            }
        },

        // Watch for changes in layout mode or ID
        isLayoutMode() {
            this.updateFromCssValues();
        },
        currentLayoutId() {
            if (this.isLayoutMode) {
                this.updateFromCssValues();
            }
        },

        // Watch for changes in the applied selector
        selectorToApply: {
            handler() {
                this.updateFromCssValues();
            }
        }
    },
    mounted() {
        this.updateFromCssValues();

        // Register for property changes
        if (this.templateSettings && typeof this.templateSettings.registerPropertyChangeListener === 'function') {
            this.templateSettings.registerPropertyChangeListener(this.onPropertyChange);
        }
    },
    beforeUnmount() {
        // Clean up listener
        if (this.templateSettings && typeof this.templateSettings.unregisterPropertyChangeListener === 'function') {
            this.templateSettings.unregisterPropertyChangeListener(this.onPropertyChange);
        }
    },
    methods: {
        getEffectiveSelector() {
            try {
                // Use templateSettings' transform method if available
                if (this.templateSettings && typeof this.templateSettings.transformSelectorBasedOnMode === 'function') {
                    const rootSelector = this.templateSettings.getRootSelector ?
                        this.templateSettings.getRootSelector() : '';

                    return this.templateSettings.transformSelectorBasedOnMode(
                        this.selectorToApply,
                        rootSelector
                    );
                }

                // Fall back to our own implementation
                if (this.isLayoutMode) {
                    const layoutId = this.currentLayoutId;

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
        },
        updateValue(value) {
            // Cache the value locally first
            this.localColorValue = value;

            // Emit event with the correct selector based on current mode
            this.$emit('update', {
                selector: this.selectorToApply, // Parent will transform this
                property: this.setting.fieldSettings.property,
                value: value
            });
        },
        onPropertyChange(data) {
            // Only update if this change is relevant to our component
            if ((data.selector === this.selectorToApply || data.selector === this.effectiveSelector) &&
                data.property === this.setting.fieldSettings.property) {
                this.localColorValue = data.value;
            }
        },
        updateFromCssValues() {
            // Reset local cache and force re-fetch from CSS
            this.localColorValue = null;

            // Force re-evaluation of computed properties
            this.$forceUpdate();
        }
    }
};
</script>
