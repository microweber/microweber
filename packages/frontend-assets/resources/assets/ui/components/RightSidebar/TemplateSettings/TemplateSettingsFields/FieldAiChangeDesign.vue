<template>
    <!-- AI Design Button -->
    <div class="ai-settings-wrapper">
        <label class="live-edit-label mb-2">MAKE YOUR WEBSITE FASTER WITH AI</label>
        <div :class="{'d-none': !isAIAvailable}" class="ai-change-template-design-button"></div>


        <div v-if="!showAIChatForm">
            <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Change Design with AI" class="btn btn-link p-0"
                    @click="toggleAIChatForm" :disabled="!isAIAvailable">
                Go with AI
            </button>
        </div>

        <div v-show="showAIChatForm" class="mt-2">

            <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Close Change Design with AI" class="btn btn-link p-0"
                    @click="toggleAIChatForm">
                Close
            </button>


            <!-- Buttons to switch between form types -->
            <div ref="aiChatFormBox"></div>
            <!-- Shared UI elements -->
            <div v-if="loading" class="text-center mt-2">AI is thinking...</div>
            <div v-if="error" class="text-danger mt-2">{{ error }}</div>
        </div>
    </div>
</template>

<script>

import {AIChatForm} from '../../../../../components/ai-chat';


export default {
    inject: ['templateSettings'],
    data() {
        return {
            supportedFonts: [],
            showAIChatForm: false,
            aiFormType: 'simple', // Default to simple form
            aiChatFormInstance: null,
            loading: false,
            error: null,
            aiMessage: ''
        }
    },
    computed: {
        isAIAvailable() {
            // Check if parent has isAIAvailable property
            if (this.templateSettings && this.templateSettings.isAIAvailable !== undefined) {
                return this.templateSettings.isAIAvailable;
            }

            // If not accessible from parent, check it directly
            return typeof window.mw?.top()?.win?.MwAi === 'function';
        }
    },
    methods: {
        prepareAndCleanTemplateStylesAndSelectorsData(items) {
            if (!Array.isArray(items)) return items;

            return items.filter(item => {
                // Remove items with fieldType clearAll
                if (item.fieldType === 'clearAll') return false;

                // Clean unwanted properties
                ['readSettingsFromFiles', 'parent', 'backUrl', 'url'].forEach(prop => {
                    if (item.hasOwnProperty(prop)) delete item[prop];
                });

                // Clean nested settings
                if (item.settings && Array.isArray(item.settings)) {
                    item.settings = this.prepareAndCleanTemplateStylesAndSelectorsData(item.settings);
                }

                // Clean nested fieldSettings if it's an array
                if (item.fieldSettings && Array.isArray(item.fieldSettings)) {
                    item.fieldSettings = this.prepareAndCleanTemplateStylesAndSelectorsData(item.fieldSettings);
                }

                return item.settings?.length > 0 || item.fieldSettings || item.selectors;
            });
        },

        prepareTemplateValuesForEdit(designSelectors) {
            // Filter out items without settings after cleaning
            designSelectors = designSelectors.filter(item => {
                return item.settings && Array.isArray(item.settings) && item.settings.length > 0;
            });

            // Array to collect all selector-property combinations
            let allSelectorPropertyPairs = [];

            // Collect all selector-property pairs
            for (let i = 0; i < designSelectors.length; i++) {
                let item = designSelectors[i];

                // Process nested settings
                for (let k = 0; k < item.settings.length; k++) {
                    let setting = item.settings[k];

                    if (setting.selectors && setting.selectors.length > 0 && setting.fieldSettings) {
                        const nestedSelector = setting.selectors[0];

                        // Handle nested fieldSettings as an object
                        if (!Array.isArray(setting.fieldSettings) && typeof setting.fieldSettings === 'object') {
                            const property = setting.fieldSettings.property;
                            if (property) {
                                allSelectorPropertyPairs.push({
                                    selector: nestedSelector,
                                    property: property,
                                    target: {
                                        object: setting.fieldSettings,
                                        key: 'value'
                                    },
                                    layout: setting.layout || item.layout // collect layout info if present
                                });
                            }
                        }
                        // Handle nested fieldSettings as an array
                        else if (Array.isArray(setting.fieldSettings) && setting.fieldSettings.length > 0) {
                            for (let m = 0; m < setting.fieldSettings.length; m++) {
                                const property = setting.fieldSettings[m].property;
                                if (property) {
                                    allSelectorPropertyPairs.push({
                                        selector: nestedSelector,
                                        property: property,
                                        target: {
                                            object: setting.fieldSettings[m],
                                            key: 'value'
                                        },
                                        layout: setting.layout || item.layout // collect layout info if present
                                    });
                                }
                            }
                        }
                    }
                }
            }

            // Filter unique selector-property combinations
            let uniquePairs = [];
            let uniqueKeys = new Set();

            for (const pair of allSelectorPropertyPairs) {
                const key = `${pair.selector}|${pair.property}`;
                if (!uniqueKeys.has(key)) {
                    uniqueKeys.add(key);
                    uniquePairs.push(pair);
                }
            }            // Apply layout-specific filtering
            if (this.templateSettings.applyMode === 'layout' && this.templateSettings.activeLayoutId && this.templateSettings.activeLayoutId !== 'None') {
                const layoutId = this.templateSettings.activeLayoutId;

                if (layoutId) {
                    const layoutSelectorTarget = '#' + layoutId;
                    const processedPairs = [];

                    for (const pair of uniquePairs) {
                        let currentSelector = pair.selector;
                        let finalSelector = pair.selector;

                        if (currentSelector === ':root') {
                            finalSelector = layoutSelectorTarget;
                        }

                        let includeThisPair = false;
                        if (finalSelector === layoutSelectorTarget) {
                            includeThisPair = true;
                        } else if (pair.layout && pair.layout == layoutId) {
                            includeThisPair = true;
                        }

                        if (includeThisPair) {
                            // Create a shallow copy to modify selector, keeping target reference intact
                            const pairToAdd = {...pair};
                            pairToAdd.selector = finalSelector; // Ensure the selector is the final one
                            processedPairs.push(pairToAdd);
                        }
                    }
                    uniquePairs = processedPairs;

                    // Deduplicate again, as different original pairs might now target the same finalSelector and property
                    const finalUniquePairsAfterTransform = [];
                    const finalUniqueKeysAfterTransform = new Set();
                    for (const p of uniquePairs) {
                        const key = `${p.selector}|${p.property}`;
                        if (!finalUniqueKeysAfterTransform.has(key)) {
                            finalUniqueKeysAfterTransform.add(key);
                            finalUniquePairsAfterTransform.push(p);
                        }
                    }
                    uniquePairs = finalUniquePairsAfterTransform;
                } else {
                    uniquePairs = []; // No layout ID, so no pairs if in layout mode
                }
            }

            // Get property values for each selector-property pair
            for (const pair of uniquePairs) {
                let propertyValue;

                // Use either Vue component instance or direct cssEditor access
                if (this.templateSettings && typeof this.templateSettings.getCssPropertyValue === 'function') {
                    propertyValue = this.templateSettings.getCssPropertyValue(pair.selector, pair.property);
                } else if (window.mw?.top()?.app?.cssEditor) {
                    propertyValue = window.mw.top().app.cssEditor.getPropertyForSelector(pair.selector, pair.property);
                }

                if (pair.target && typeof pair.target.object === 'object' && pair.target.object !== null) {
                    pair.target.object[pair.target.key] = propertyValue;
                }
            }

            // Build final values object for editing
            let valuesForEdit = {};
            for (const pair of uniquePairs) {
                let propertyValue;

                // Use either Vue component instance or direct cssEditor access
                if (this.templateSettings && typeof this.templateSettings.getCssPropertyValue === 'function') {
                    propertyValue = this.templateSettings.getCssPropertyValue(pair.selector, pair.property);
                } else if (window.mw?.top()?.app?.cssEditor) {
                    propertyValue = window.mw.top().app.cssEditor.getPropertyForSelector(pair.selector, pair.property);
                }

                let selectorKey = pair.selector;

                if (!valuesForEdit[selectorKey]) {
                    valuesForEdit[selectorKey] = {};
                }

                valuesForEdit[selectorKey][pair.property] = propertyValue;
            }            // Ensure valuesForEdit has the layout key if empty in layout mode
            if (Object.keys(valuesForEdit).length === 0 &&
                this.templateSettings.applyMode === 'layout' &&
                this.templateSettings.activeLayoutId && this.templateSettings.activeLayoutId !== 'None') {
                const layoutId = this.templateSettings.activeLayoutId;
                if (layoutId) {
                    valuesForEdit['#' + layoutId] = {};
                }
            }

            return valuesForEdit;
        },

        toggleAIChatForm() {
            this.showAIChatForm = !this.showAIChatForm;
            this.error = null;
            this.aiMessage = '';

            if (this.showAIChatForm && this.aiFormType === 'advanced' && !this.aiChatFormInstance && this.$refs.aiChatFormContainer) {
                try {
                    this.initAIChatForm();
                } catch (e) {
                    console.error('Error initializing AIChatForm', e);
                    this.aiFormType = 'simple'; // Fallback to simple form
                    this.error = 'Advanced form is not available. Using simple form.';
                }
            }
        },


        initAIChatForm() {
            this.aiChatFormInstance = new AIChatForm({
                multiLine: true,
                submitOnEnter: true,
                placeholder: 'Make it blue and white...'
            });


            // todo, must clear the box value

            this.$refs.aiChatFormBox.appendChild(this.aiChatFormInstance.form);
            this.aiChatFormInstance.on('submit', (value) => {
                this.aiMessage = value;
                this.changeDesign(value);
            });
            this.aiChatFormInstance.on('areaValue', (value) => {
                this.aiMessage = value;
            });

        },

        submitAiRequest() {
            if (!this.aiMessage.trim()) {
                return;
            }

            this.changeDesign(this.aiMessage);
        },

        async changeDesign(about) {
            if (!about) {
                return; // No message provided
            }

            // Double-check AI availability before proceeding
            if (!this.isAIAvailable) {
                this.error = 'AI functionality is not available';
                return;
            }

            if (!window.mw_template_settings_styles_and_selectors) {
                this.error = 'Template settings are not available';
                return;
            }

            this.loading = true;
            let designSelectors = JSON.parse(JSON.stringify(window.mw_template_settings_styles_and_selectors));

            // First, recursively remove clearAll items and unwanted properties
            designSelectors = this.prepareAndCleanTemplateStylesAndSelectorsData(designSelectors);

            // Prepare values for editing
            const valuesForEdit = this.prepareTemplateValuesForEdit(designSelectors);

            console.log('valuesForEdit:', valuesForEdit);
            let editSchema = JSON.stringify(valuesForEdit);

            // Only include supported fonts if the request contains the word "font"
            let fontInfo = '';
            if (about && about.toLowerCase().includes('font')) {
                let supportedFonts = this.supportedFonts.map(font => font).join(', ');
                fontInfo = `If the user asks to change the font, you must use one of the following fonts: ${supportedFonts} ,`;
            }

            const message = `Using the existing object IDS,
By using this schema: \n ${editSchema} \n
You must write CSS values to the given object,
You are CSS values editor, you must edit the values of the css to complete the user design task,

${fontInfo}

The css design task is to make the design: ${about}

You must write the text for the website and fill the existing object IDs with the text,

You must respond ONLY with the JSON schema with the following structure. Do not add any additional comments""" + \\
"""[
  JSON
{
   { Populated Schema Definition with the items filled with text ... populate the schema with the existing object IDs and the text  }
"""`;

            let messageOptions = {schema: editSchema};

            // Show spinner while waiting for AI response
            window.mw.top().spinner({element: window.mw.top().doc.body, size: 60, decorate: true}).show();

            let messages = [{role: 'user', content: message}];

            try {
                // Send to MwAi
                if (!window.mw?.top()?.win?.MwAi) {
                    throw new Error('AI functionality is not available');
                }

                let res = await window.mw.top().win.MwAi().sendToChat(messages, messageOptions);

                if (res.success && res.data) {
                    // Collect updates in a batch
                    const updates = [];

                    // Process each selector and property in the AI response
                    for (let selector in res.data) {
                        if (res.data.hasOwnProperty(selector)) {
                            // Loop through all properties for the current selector
                            for (let property in res.data[selector]) {
                                if (res.data[selector].hasOwnProperty(property)) {
                                    const value = res.data[selector][property];
                                    // Determine unit if needed
                                    const unit = property.includes('color') ? '' : '';

                                    // Add to updates batch
                                    updates.push({
                                        selector: selector,
                                        property: property,
                                        value: value + unit
                                    });
                                }
                            }
                        }
                    }


                    // Apply all updates at once
                    if (updates.length > 0) {
                        this.$emit('batch-update', updates);
                    }

                    // Clear form input after successful request
                    this.aiMessage = '';


                } else {
                    throw new Error('Invalid response from AI');
                }
            } catch (error) {
                console.error('AI design change error:', error);
                this.error = 'Failed to change design with AI: ' + (error.message || 'Unknown error');
            } finally {
                // Always remove the spinner and reset loading state
                window.mw.top().spinner({element: window.mw.top().doc.body, size: 60, decorate: true}).remove();
                this.loading = false;
            }
        }
    },


    mounted() {

        this.supportedFonts = mw.top().app.fontManager.getFonts();

        // Update the UI based on AI availability
        if (this.isAIAvailable && document.querySelector('.ai-change-template-design-button')) {
            document.querySelector('.ai-change-template-design-button').classList.remove('d-none');
        }

        this.initAIChatForm()

        console.log('FieldAiChangeDesign mounted, AI availability:', this.isAIAvailable);


    },
    beforeUnmount() {
        // Clean up AIChatForm instance if exists
        if (this.aiChatFormInstance && typeof this.aiChatFormInstance.remove === 'function') {
            try {
                this.aiChatFormInstance.remove();
            } catch (e) {
                console.error('Failed to remove AIChatForm', e);
            }
            this.aiChatFormInstance = null;
        }
    }
};
</script>
