<template>
    <!-- AI Design Button -->
    <div class="ai-settings-wrapper">
        <label class="live-edit-label mb-2">MAKE YOUR WEBSITE FASTER WITH AI</label>
        <div :class="{'d-none': !isAIAvailable}" class="ai-change-template-design-button"></div>
        <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                title="Change Design with AI" class="btn btn-link p-0"
                @click="changeDesign" :disabled="!isAIAvailable">
            Go with AI
        </button>
    </div>
</template>

<script>
export default {
    props: {
        isAIAvailable: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        prepareAndCleanTemplateStylesAndSelectorsData(items) {
            if (!Array.isArray(items)) return items;

            return items.filter(item => {
                if (item.fieldType === 'clearAll') return false;

                ['readSettingsFromFiles', 'parent', 'backUrl', 'url'].forEach(prop => {
                    if (item.hasOwnProperty(prop)) delete item[prop];
                });

                if (item.settings && Array.isArray(item.settings)) {
                    item.settings = this.prepareAndCleanTemplateStylesAndSelectorsData(item.settings);
                }

                if (item.fieldSettings && Array.isArray(item.fieldSettings)) {
                    item.fieldSettings = this.prepareAndCleanTemplateStylesAndSelectorsData(item.fieldSettings);
                }

                return item.settings?.length > 0 || item.fieldSettings || item.selectors;
            });
        },

        prepareTemplateValuesForEdit(designSelectors) {
            designSelectors = designSelectors.filter(item => {
                return item.settings && Array.isArray(item.settings) && item.settings.length > 0;
            });

            let allSelectorPropertyPairs = [];

            for (let i = 0; i < designSelectors.length; i++) {
                let item = designSelectors[i];

                for (let k = 0; k < item.settings.length; k++) {
                    let setting = item.settings[k];

                    if (setting.selectors && setting.selectors.length > 0 && setting.fieldSettings) {
                        const nestedSelector = setting.selectors[0];

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
                                    layout: setting.layout || item.layout
                                });
                            }
                        } else if (Array.isArray(setting.fieldSettings) && setting.fieldSettings.length > 0) {
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
                                        layout: setting.layout || item.layout
                                    });
                                }
                            }
                        }
                    }
                }
            }

            let uniquePairs = [];
            let uniqueKeys = new Set();

            for (const pair of allSelectorPropertyPairs) {
                const key = `${pair.selector}|${pair.property}`;
                if (!uniqueKeys.has(key)) {
                    uniqueKeys.add(key);
                    uniquePairs.push(pair);
                }
            }

            if (window.css_vars_design_apply_mode === 'layout' && window.css_vars_design_active_layout) {
                const activeLayout = window.css_vars_design_active_layout;
                const layoutId = typeof activeLayout === 'string'
                    ? activeLayout
                    : (activeLayout?.id || activeLayout?.getAttribute?.('id'));

                if (layoutId) {
                    const layoutSelectorTarget = '#' + layoutId;
                    const processedPairs = [];

                    for (const pair of uniquePairs) {
                        let finalSelector = pair.selector;

                        if (pair.selector === ':root') {
                            finalSelector = layoutSelectorTarget;
                        }

                        let includeThisPair = false;
                        if (finalSelector === layoutSelectorTarget || (pair.layout && pair.layout == layoutId)) {
                            includeThisPair = true;
                        }

                        if (includeThisPair) {
                            const pairToAdd = {...pair};
                            pairToAdd.selector = finalSelector;
                            processedPairs.push(pairToAdd);
                        }
                    }
                    uniquePairs = processedPairs;

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
                    uniquePairs = [];
                }
            }

            for (const pair of uniquePairs) {
                let propertyValue;
                if (window.mw?.top()?.app?.__vueTemplateSettingsInstance) {
                    propertyValue = window.mw.top().app.__vueTemplateSettingsInstance.getCssPropertyValue(pair.selector, pair.property);
                } else {
                    propertyValue = window.mw?.top()?.app?.cssEditor?.getPropertyForSelector(pair.selector, pair.property);
                }

                if (pair.target && typeof pair.target.object === 'object' && pair.target.object !== null) {
                    pair.target.object[pair.target.key] = propertyValue;
                }
            }

            let valuesForEdit = {};
            for (const pair of uniquePairs) {
                let propertyValue;
                if (window.mw?.top()?.app?.__vueTemplateSettingsInstance) {
                    propertyValue = window.mw.top().app.__vueTemplateSettingsInstance.getCssPropertyValue(pair.selector, pair.property);
                } else {
                    propertyValue = window.mw?.top()?.app?.cssEditor?.getPropertyForSelector(pair.selector, pair.property);
                }

                let selectorKey = pair.selector;

                if (!valuesForEdit[selectorKey]) {
                    valuesForEdit[selectorKey] = {};
                }

                valuesForEdit[selectorKey][pair.property] = propertyValue;
            }

            if (Object.keys(valuesForEdit).length === 0 &&
                window.css_vars_design_apply_mode === 'layout' &&
                window.css_vars_design_active_layout) {
                const activeLayout = window.css_vars_design_active_layout;
                const layoutId = typeof activeLayout === 'string'
                    ? activeLayout
                    : (activeLayout?.id || activeLayout?.getAttribute?.('id'));
                if (layoutId) {
                    valuesForEdit['#' + layoutId] = {};
                }
            }

            return valuesForEdit;
        },

        async changeDesign(about) {
            if (!window.mw?.top()?.win?.MwAi) {
                alert('AI functionality is not available');
                return;
            }

            let designSelectors = window.mw_template_settings_styles_and_selectors;
            if (!designSelectors) {
                alert('Template settings are not available');
                return;
            }

            designSelectors = JSON.parse(JSON.stringify(designSelectors));

            if (!about) {
                about = prompt('Please enter the design task description:', 'Make it blue and white');
                if (!about) return;
            }

            designSelectors = this.prepareAndCleanTemplateStylesAndSelectorsData(designSelectors);
            const valuesForEdit = this.prepareTemplateValuesForEdit(designSelectors);

            let editSchema = JSON.stringify(valuesForEdit);

            const message = `Using the existing object IDS,
By using this schema: \n ${editSchema} \n
You must write CSS values to the given object,
You are CSS values editor, you must edit the values of the css to complete the user design task,

The css design task is : ${about}

You must write the text for the website and fill the existing object IDs with the text,

You must respond ONLY with the JSON schema with the following structure. Do not add any additional comments""" + \\
"""[
  JSON
{
   { Populated Schema Definition with the items filled with text ... populate the schema with the existing object IDs and the text  }
"""`

            let messageOptions = {schema: editSchema};
            window.mw.top().spinner({element: window.mw.top().doc.body, size: 60, decorate: true}).show();

            let messages = [{role: 'user', content: message}];

            try {
                let res = await window.mw.top().win.MwAi().sendToChat(messages, messageOptions);

                if (res.success && res.data) {
                    for (let selector in res.data) {
                        if (res.data.hasOwnProperty(selector)) {
                            for (let property in res.data[selector]) {
                                if (res.data[selector].hasOwnProperty(property)) {
                                    const value = res.data[selector][property];

                                    if (window.mw?.top()?.app?.__vueTemplateSettingsInstance) {
                                        window.mw.top().app.__vueTemplateSettingsInstance.updateCssProperty(
                                            selector,
                                            property,
                                            value
                                        );
                                    } else if (window.mw?.top()?.app?.cssEditor) {
                                        const unit = property.includes('color') ? '' : '';
                                        window.mw.top().app.cssEditor.setPropertyForSelector(
                                            selector,
                                            property,
                                            value + unit,
                                            true,
                                            true
                                        );
                                    }
                                }
                            }
                        }
                    }
                }
            } catch (error) {
                console.error('AI design change failed:', error);
                alert('Failed to change design with AI');
            } finally {
                window.mw.top().spinner({element: window.mw.top().doc.body, size: 60, decorate: true}).remove();
            }
        }
    }
};
</script>
