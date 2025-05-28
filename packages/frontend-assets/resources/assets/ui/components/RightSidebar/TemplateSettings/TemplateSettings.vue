<template>
    <div class="p-3">
        <div v-if="isLoading" class="text-center">
            <div class="spinner-border" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>

        <div v-else-if="currentError" class="alert alert-danger">
            {{ currentError }}
        </div>

        <div v-else>
            <!-- Navigation path -->
            <div v-if="currentPath !== '/'" class="mb-3">
                <button @click="goBack"
                        class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start">
                    <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32"
                         height="32" viewBox="0 0 32 32">
                        <g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                            <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                            <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                        </g>
                    </svg>
                    <div class="ms-1 font-weight-bold">Back</div>
                </button>
            </div>

            <!-- Choose where to edit dropdown -->
            <div v-if="hasStyleSettings" class="form-control-live-edit-label-wrapper mt-3 mb-3">
                <label for="css_vars_design_apply_mode" class="live-edit-label">Choose where to edit</label>
                <select class="form-control-live-edit-input form-select" v-model="applyMode"
                        @change="handleApplyModeChange">
                    <option value="template">Template</option>
                    <option value="layout">Layout</option>
                </select>

                <div id="layout-id-display" class="mt-2 small text-muted my-2" v-if="applyMode === 'layout'"
                     style="display: block;">
                    <div class="d-flex justify-content-between">
                        <span id="active-layout-id" @click="scrollToSelectedLayout">{{ activeLayoutId }}</span>
                        <span id="active-layout-id-open-settings" @click="openSelectedLayoutSettings">⚙</span>
                    </div>
                </div>
            </div>

            <!-- AI Design Button -->
            <FieldAiChangeDesign v-if="hasStyleSettings" :is-ai-available="isAIAvailable" />

            <div v-if="currentPath !== '/'" class="mb-3 mt-3">
                <button @click="goBack"
                        class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start">
                    <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32"
                         height="32" viewBox="0 0 32 32">
                        <g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                            <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                            <path class="arrow-icon--arrow" d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                        </g>
                    </svg>
                    <div class="ms-1 font-weight-bold">Back</div>
                </button>
            </div>

            <!-- Main settings list when at root path -->
            <div v-if="currentPath === '/' && hasStyleSettings" class="mt-5">
                <span
                    class="fs-2 font-weight-bold settings-main-group d-flex align-items-center justify-content-between">
                    Styles
                    <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Reset stylesheet settings" class="reset-template-settings-and-stylesheet-button"
                            @click="resetAllDesignSelectorsValuesSettings">
                        <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                             width="24">
                            <path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/>
                        </svg>
                    </button>
                </span>

                <div v-for="(settingGroup, index) in mainStyleGroups" :key="index" class="my-3">
                    <a @click="navigateTo(settingGroup.url)"
                       class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group cursor-pointer mb-4">
                        {{ settingGroup.title }}
                    </a>
                </div>
            </div>

            <!-- Settings detail when not at root path -->
            <div v-if="currentPath !== '/' && currentSetting && showStyleSettings !== 'styleEditor'">
                <div class="mb-3">
                    <h4 v-if="currentSetting.title">{{ currentSetting.title }}</h4>
                    <p v-if="currentSetting.description">{{ currentSetting.description }}</p>
                </div>

                <!-- If currentSetting itself is a field, render it using NestedSettingsItem -->
                <!-- NestedSettingsItem will also handle currentSetting.settings if it exists (for complex fields) -->
                <div v-if="currentSetting.fieldType">
                    <nested-settings-item
                        :setting="currentSetting"
                        :root-selector="getRootSelector()"
                        @navigate="navigateTo"
                        @update="handleSettingUpdate"
                        @open-style-editor="handleStyleEditorOpen"/>
                </div>
                <!-- Else (currentSetting is a group, not a field itself) -->
                <div v-else>
                    <!-- Option 1: Children are in currentSetting.settings array -->
                    <div v-if="currentSetting.settings && currentSetting.settings.length > 0">
                        <div v-for="(childSetting, index) in currentSetting.settings" :key="'direct_child_'+index"
                             class="my-3">
                            <nested-settings-item
                                :setting="childSetting"
                                :root-selector="getRootSelector()"
                                @navigate="navigateTo"
                                @update="handleSettingUpdate"
                                @open-style-editor="handleStyleEditorOpen"/>
                        </div>
                    </div>
                    <!-- Option 2: Children are found via subItems (URL matching), and no direct .settings array -->
                    <div v-else-if="subItems && subItems.length > 0">
                        <div v-for="(subItemFromFlatList, index) in subItems" :key="'sub_item_'+index" class="my-3">
                            <nested-settings-item
                                :setting="subItemFromFlatList"
                                :root-selector="getRootSelector()"
                                @navigate="navigateTo"
                                @update="handleSettingUpdate"
                                @open-style-editor="handleStyleEditorOpen"/>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Style Editor iframe holder -->
            <div v-if="showStyleSettings === 'styleEditor'" class="mt-3">
                <div>
                    <button @click="goBackFromStyleEditor"
                            class="d-flex gap-2 btn btn-link mw-live-edit-toolbar-link mw-live-edit-toolbar-link--arrowed text-start">
                        <svg class="mw-live-edit-toolbar-arrow-icon" xmlns="http://www.w3.org/2000/svg" width="32"
                             height="32" viewBox="0 0 32 32">
                            <g fill="none" stroke-width="1.5" stroke-linejoin="round" stroke-miterlimit="10">
                                <circle class="arrow-icon--circle" cx="16" cy="16" r="15.12"></circle>
                                <path class="arrow-icon--arrow"
                                      d="M16.14 9.93L22.21 16l-6.07 6.07M8.23 16h13.98"></path>
                            </g>
                        </svg>
                        <div class="ms-1 font-weight-bold">Back</div>
                    </button>
                </div>

                <b v-if="styleEditorData.title">{{ styleEditorData.title }}</b>
                <p v-if="styleEditorData.description">{{ styleEditorData.description }}</p>

                <div class="my-3">
                    <div id="iframe-holder"></div>
                </div>
            </div>

            <!-- Template Settings -->
            <div v-if="settingsGroups && Object.keys(settingsGroups).length !== 0">
                <div v-for="(settings, settingGroupKey) in settingsGroups" :key="settingGroupKey" class="mt-2 mb-5">
                    <a v-on:click="showSettingsGroup(settingGroupKey)"
                       class="fs-2 mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                        {{ settingGroupKey }}
                    </a>

                    <div class="mt-3" style="display:block" :id="'settings-group-' + stringToId(settingGroupKey)">
                        <div class="" :id="'accordionFlush' + stringToId(settingGroupKey)">
                            <div v-for="(settingGroupInside, settingGroupInsideName) in settings.values"
                                 :key="settingGroupInsideName" class="mb-2 ps-2">
                                <label
                                    :id="'flush-heading-' + stringToId(settingGroupKey +'-'+ settingGroupInsideName)">
                                    <a class="mw-admin-action-links mw-action-links-with-accordion mw-adm-liveedit-tabs collapsed"
                                       type="button" data-bs-toggle="collapse"
                                       :data-bs-target="'#flush-collapse-' + stringToId(settingGroupKey +'-'+ settingGroupInsideName)"
                                       aria-expanded="false"
                                       :aria-controls="'flush-collapse-' + stringToId(settingGroupKey +'-'+ settingGroupInsideName)">
                                        {{ settingGroupInsideName }}
                                    </a>
                                </label>

                                <div :id="'flush-collapse-' + stringToId(settingGroupKey +'-'+ settingGroupInsideName)"
                                     class="accordion-collapse collapse"
                                     :aria-labelledby="'flush-heading-' + stringToId(settingGroupKey +'-'+ settingGroupInsideName)"
                                     :data-bs-parent="'#accordionFlush' + stringToId(settingGroupKey)">
                                    <div class="accordion-body ps-2">
                                        <div v-for="(setting, settingKey) in settingGroupInside" class="mt-2">
                                            <!-- Existing settings rendering -->
                                            <div v-if="setting.type === 'text'">
                                                <label class="mr-4">{{ setting.label }}</label>
                                                <div>
                                                    <input type="text" class="form-control"
                                                           :value="[setting.value ? setting.value : setting.default]"
                                                           v-on:change="updateSettings($event, settingKey, setting.optionGroup)"
                                                           :name="settingKey"/>
                                                </div>
                                            </div>

                                            <div v-if="setting.type === 'color'">
                                                <div class="d-flex justify-content-between">
                                                    <div class="mr-4">{{ setting.label }}</div>
                                                    <div>
                                                        <ColorPicker
                                                            :key="settingKey"
                                                            :color="[setting.value ? setting.value : setting.default]"
                                                            v-on:change="updateSettings($event, settingKey, setting.optionGroup)"
                                                            :name="settingKey"/>
                                                    </div>
                                                </div>
                                            </div>

                                            <div v-if="setting.type === 'title'">
                                                <div class="text-uppercase">
                                                    <span>{{ setting.label }}</span>
                                                </div>
                                            </div>

                                            <div v-if="setting.type === 'range'">
                                                <label class="mr-4">
                                                    {{ setting.label }} -
                                                    {{ options[setting.optionGroup][settingKey] }}
                                                    <span v-if="setting.range.unit">
                                                        {{ setting.range.unit ? setting.range.unit : '' }}
                                                    </span>
                                                </label>
                                                <div>
                                                    <FieldRangeSlider
                                                        :setting="{
                                                            title: setting.label,
                                                            fieldSettings: {
                                                                min: setting.range.min ? setting.range.min : 0,
                                                                max: setting.range.max ? setting.range.max : 100,
                                                                step: setting.range.step ? setting.range.step : 1,
                                                                unit: setting.range.unit ? setting.range.unit : '',
                                                                property: settingKey
                                                            }
                                                        }"
                                                        :selectorToApply="':root'"
                                                        @update="(data) => updateSettings(data.value, settingKey, setting.optionGroup)"
                                                    />
                                                </div>
                                            </div>

                                            <div v-if="setting.type === 'dropdown_image'">
                                                <div>{{ setting.label }}</div>
                                                <select class="form-select"
                                                        v-on:change="updateSettings($event, settingKey, setting.optionGroup)"
                                                        :name="settingKey"
                                                        :value="[setting.value ? setting.value : setting.default]">
                                                    <option v-for="(optionValue, optionKey) in setting.options"
                                                            :value="optionKey">
                                                        {{ optionValue }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div v-if="setting.type === 'toggle_switch'">
                                                <div class="mb-2">{{ setting.label }}</div>
                                                <label class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" checked="">
                                                </label>
                                            </div>

                                            <div v-if="setting.type === 'dropdown'">
                                                <div>{{ setting.label }}</div>
                                                <select class="form-select"
                                                        v-on:change="updateSettings($event, settingKey, setting.optionGroup)"
                                                        v-model="options[setting.optionGroup][settingKey]">
                                                    <option v-for="(optionValue, optionKey) in setting.options"
                                                            :value="optionKey">
                                                        {{ optionValue }}
                                                    </option>
                                                </select>
                                            </div>

                                            <div v-if="setting.type === 'font_selector'">
                                                <div>{{ setting.label }}</div>
                                                <select class="form-select"
                                                        v-on:change="updateSettings($event, settingKey, setting.optionGroup)"
                                                        :name="settingKey"
                                                        :value="[setting.value ? setting.value : setting.default]">
                                                    <option v-for="fontFamily in supportedFonts"
                                                            :value="fontFamily">
                                                        {{ fontFamily }}
                                                    </option>
                                                </select>
                                                <button type="button" class="btn btn-outline-dark btn-sm mt-3"
                                                        v-on:click="loadMoreFonts()">
                                                    Load more
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div style="display: none" v-on:click="resetStylesheetSettings"
                             class="d-flex align-items-center justify-content-between cursor-pointer"
                             v-if="settings && settings.type == 'stylesheet'">
                            <span>Reset Stylesheet Settings</span>
                            <svg fill="currentColor" title="Reset stylesheet settings"
                                 xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18">
                                <path
                                    d="M451-122q-123-10-207-101t-84-216q0-77 35.5-145T295-695l43 43q-56 33-87 90.5T220-439q0 100 66 173t165 84v60Zm60 0v-60q100-12 165-84.5T741-439q0-109-75.5-184.5T481-699h-20l60 60-43 43-133-133 133-133 43 43-60 60h20q134 0 227 93.5T801-439q0 125-83.5 216T511-122Z"/>
                            </svg>
                        </div>

                        <div v-on:click="resetTemplateSettings"
                             class="d-flex align-items-center justify-content-between cursor-pointer"
                             v-if="settings && settings.type == 'template'">
                            <span>Reset Template Settings</span>
                            <svg fill="currentColor" title="Reset template settings" xmlns="http://www.w3.org/2000/svg"
                                 height="18" viewBox="0 -960 960 960" width="18">
                                <path
                                    d="M451-122q-123-10-207-101t-84-216q0-77 35.5-145T295-695l43 43q-56 33-87 90.5T220-439q0 100 66 173t165 84v60Zm60 0v-60q100-12 165-84.5T741-439q0-109-75.5-184.5T481-699h-20l60 60-43 43-133-133 133-133 43 43-60 60h20q134 0 227 93.5T801-439q0 125-83.5 216T511-122Z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style>
.settings-main-group {
    cursor: pointer;
}
</style>


<script>
import axios from 'axios';
import ColorPicker from '../../../apps/ElementStyleEditor/components/ColorPicker.vue';
import NestedSettingsItem from './NestedSettingsItem.vue';
import FieldRangeSlider from './TemplateSettingsFields/FieldRangeSlider.vue';
import FieldAiChangeDesign from './TemplateSettingsFields/FieldAiChangeDesign.vue';
import { reactive } from 'vue';

export default {
    components: {
        ColorPicker,
        NestedSettingsItem,
        FieldRangeSlider,
        FieldAiChangeDesign
    },
    provide() {
        return {
            templateSettings: this
        };
    },    data() {
        return {
            isLoading: true,
            currentError: null,
            supportedFonts: [],
            settingsGroups: [],
            options: {},
            optionGroup: '',
            optionGroupLess: '',
            styleSheetSourceFile: false,
            styleSettingVars: [],
            currentPath: '/',
            applyMode: 'template', // 'template' or 'layout'
            activeLayoutId: 'None',
            isAIAvailable: false,
            styleEditorData: {},
            showStyleSettings: '/',
            styleValues: reactive({}), // Changed to reactive for Vue 3
            propertyChangeListeners: [], // Array to store registered listeners for Vue 3 event handling
        };
    },
    computed: {
        hasStyleSettings() {
            return this.styleSettingVars && this.styleSettingVars.length > 0;
        },

        mainStyleGroups() {
            if (!this.styleSettingVars) return [];
            return this.styleSettingVars.filter(item => item.main === true);
        },

        currentSetting() {
            if (this.currentPath === '/') return null;
            // Ensure we get a setting that is most likely to have children if multiple exist for the same URL
            // Prefer item with a 'settings' array, otherwise the first one found.
            const items = this.styleSettingVars.filter(item => item.url === this.currentPath);
            if (items.length === 0) return null;
            return items.find(item => item.settings && item.settings.length > 0) || items[0];
        },

        subItems() {
            // Only calculate subItems if currentSetting exists, is NOT a field,
            // AND does NOT have its own 'settings' array defining its children.
            if (!this.currentSetting ||
                this.currentSetting.fieldType ||
                (this.currentSetting.settings && this.currentSetting.settings.length > 0)) {
                return [];
            }

            const currentPathSegments = this.currentPath.split('/').filter(p => p);
            return this.styleSettingVars.filter(item => {
                if (!item.url || item.url === this.currentPath) return false;

                const itemSegments = item.url.split('/').filter(p => p);

                if (itemSegments.length === currentPathSegments.length + 1) {
                    return currentPathSegments.every((segment, index) =>
                        segment === itemSegments[index]
                    );
                }
                return false;
            });
        }
    },
    mounted() {
        this.fetchData().then(() => {
            this.initializeStyleValues();
        });
        this.checkAIAvailability();
        this.setupEventListeners();

        if (window.mw?.top()?.app) {
            window.mw.top().app.on('setPropertyForSelector', this.onPropertyChange);
            window.mw.top().app.__vueTemplateSettingsInstance = this; // Set instance reference
        }
        this.setupLayoutListener();
    },
    beforeUnmount() {
        if (window.mw?.top()?.app) {
            window.mw.top().app.off('setPropertyForSelector', this.onPropertyChange);
            if (window.mw.top().app.__vueTemplateSettingsInstance === this) {
                window.mw.top().app.__vueTemplateSettingsInstance = null; // Clear instance reference
            }
        }
    },
    watch: {
        applyMode(newMode, oldMode) {
            if (newMode !== oldMode) {
                window.css_vars_design_apply_mode = newMode;
                this.updateLayoutIdDisplay();
                this.initializeStyleValues();
            }
        },
        activeLayoutId(newId, oldId) {
            if (newId !== oldId) {
                const newActiveLayout = newId === 'None' || !newId ? null : window.mw?.top()?.app?.canvas?.getDocument()?.getElementById(newId);
                window.css_vars_design_active_layout = newActiveLayout;
                this.initializeStyleValues();
            }
        },
        styleSettingVars: {
            handler() {
                this.initializeStyleValues();
            },
            deep: true
        },
        currentPath() {
            // When path changes, the relevant rootSelector might change, so re-evaluating values might be needed
            // if not all values are pre-cached. For now, initializeStyleValues fetches all.
        }
    },
    methods: {
        async fetchData() {
            try {
                this.isLoading = true;
                const response = await axios.get(window.mw.settings.api_url + 'template/template-style-settings');
                if (response.data) {
                    this.settingsGroups = response.data.settingsGroups || {};
                    if (response.data.options && typeof response.data.options === 'object' && !Array.isArray(response.data.options)) {
                        this.options = response.data.options;
                    } else {
                        this.options = {};
                    }
                    this.optionGroup = response.data.optionGroup || '';
                    this.optionGroupLess = response.data.optionGroupLess || '';
                    this.styleSheetSourceFile = response.data.styleSheetSourceFile || false;
                    this.styleSettingVars = Array.isArray(response.data.styleSettingsVars)
                        ? response.data.styleSettingsVars.filter(item => item && typeof item === 'object')
                        : [];

                    if (this.styleSettingVars && this.styleSettingVars.length > 0) {
                        window.mw_template_settings_styles_and_selectors = this.styleSettingVars;
                        window.css_vars_design_apply_mode = this.applyMode;
                    }

                    if (this.settingsGroups && typeof this.settingsGroups === 'object') {
                        Object.keys(this.settingsGroups).forEach(groupKey => {
                            const group = this.settingsGroups[groupKey];
                            if (group && group.values && typeof group.values === 'object') {
                                Object.keys(group.values).forEach(valueName => {
                                    const settings = group.values[valueName];
                                    if (settings && typeof settings === 'object') {
                                        Object.keys(settings).forEach(settingKey => {
                                            const setting = settings[settingKey];
                                            if (setting && setting.optionGroup && !this.options[setting.optionGroup]) {
                                                this.$set(this.options, setting.optionGroup, {});
                                            }
                                        });
                                    }
                                });
                            }
                        });
                    }
                }
            } catch (error) {
                console.error("Error fetching template settings:", error);
                this.currentError = "Error loading template settings";
            } finally {
                this.isLoading = false;
            }
        },

        flattenStyleSettings(settingsArray) {
            let flat = [];
            if (!Array.isArray(settingsArray)) return flat;
            settingsArray.forEach(item => {
                flat.push(item);
                if (item.settings && Array.isArray(item.settings)) {
                    flat = flat.concat(this.flattenStyleSettings(item.settings));
                }
            });
            return flat;
        },

        findRootSelectorForPath(path) {
            if (!path || !this.styleSettingVars) return '';
            const pathSegments = path.split('/').filter(p => p);
            let currentPathAttempt = '';
            let foundRootSelector = '';

            for (const segment of pathSegments) {
                currentPathAttempt += '/' + segment;
                const pathSetting = this.styleSettingVars.find(s => s.url === currentPathAttempt);
                if (pathSetting && pathSetting.rootSelector) {
                    foundRootSelector = pathSetting.rootSelector;
                }
            }
            return foundRootSelector;
        },

        initializeStyleValues() {
            if (!this.styleSettingVars || !window.mw?.top()?.app?.cssEditor) {
                this.styleValues = reactive({});
                return;
            }
            const newStyleValues = {};
            const itemsToProcess = this.flattenStyleSettings(this.styleSettingVars);

            itemsToProcess.forEach(item => {
                if (item.fieldSettings && item.fieldSettings.property && item.selectors && item.selectors.length > 0) {
                    const baseSelector = item.selectors[item.selectors.length - 1];
                    const property = item.fieldSettings.property;

                    let itemRootSelector = item.rootSelector || this.findRootSelectorForPath(item.url) || '';

                    let finalSelector = baseSelector;
                    if (itemRootSelector && baseSelector) {
                        if (baseSelector === ':root') {
                            finalSelector = itemRootSelector;
                        } else {
                            if (baseSelector.startsWith(itemRootSelector) && itemRootSelector !== '') {
                                finalSelector = baseSelector;
                            } else {
                                finalSelector = `${itemRootSelector.trimEnd()} ${baseSelector.trimStart()}`.trim();
                            }
                        }
                    }

                    if (this.applyMode === 'layout' && this.activeLayoutId && this.activeLayoutId !== 'None') {
                        const layoutSelectorTarget = '#' + this.activeLayoutId;
                        if (baseSelector === ':root' || itemRootSelector === ':root') {
                            finalSelector = layoutSelectorTarget;
                        }
                    }

                    const value = window.mw.top().app.cssEditor.getPropertyForSelector(finalSelector, property);
                    newStyleValues[`${finalSelector}|${property}`] = value;
                }
            });

            // Replace the entire object instead of using $set
            this.styleValues = reactive(newStyleValues);
        },        getCssPropertyValue(selector, property) {
            const key = `${selector}|${property}`;
            if (this.styleValues.hasOwnProperty(key)) {
                return this.styleValues[key];
            }
            // Fallback to cssEditor if not in cache
            if (window.mw?.top()?.app?.cssEditor) {
                const value = window.mw.top().app.cssEditor.getPropertyForSelector(selector, property);
                // Cache the value for future use
                this.styleValues[key] = value;
                return value;
            }
            return undefined;
        },

        updateCssProperty(selector, property, value) {
            if (window.mw?.top()?.app?.cssEditor) {
                window.mw.top().app.cssEditor.setPropertyForSelector(selector, property, value, true, true);
                // Update our local cache immediately
                const key = `${selector}|${property}`;
                this.styleValues[key] = value;
            }
        },        onPropertyChange({ selector, property, value }) {
            const key = `${selector}|${property}`;
            // Update the reactive styleValues cache
            this.styleValues[key] = value;
            
            // Also update any setting fieldSettings.value that matches this selector and property
            this.updateSettingFieldValues(selector, property, value);
            
            // Notify all registered listeners (for Vue 3 event handling)
            if (this.propertyChangeListeners && this.propertyChangeListeners.length > 0) {
                const eventData = { selector, property, value };
                this.propertyChangeListeners.forEach(callback => {
                    if (typeof callback === 'function') {
                        try {
                            callback(eventData);
                        } catch (error) {
                            console.warn('Error calling property change listener:', error);
                        }
                    }
                });
            }
        },

        updateSettingFieldValues(selector, property, value) {
            // Find and update any setting fieldSettings.value that matches this selector and property
            const itemsToProcess = this.flattenStyleSettings(this.styleSettingVars);
            itemsToProcess.forEach(item => {
                if (item.fieldSettings && 
                    item.fieldSettings.property === property && 
                    item.selectors && 
                    item.selectors.length > 0) {
                    
                    // Calculate the final selector for this item to see if it matches
                    const baseSelector = item.selectors[item.selectors.length - 1];
                    let itemRootSelector = item.rootSelector || this.findRootSelectorForPath(item.url) || '';
                    
                    let finalSelector = baseSelector;
                    if (itemRootSelector && baseSelector) {
                        if (baseSelector === ':root') {
                            finalSelector = itemRootSelector;
                        } else {
                            if (baseSelector.startsWith(itemRootSelector) && itemRootSelector !== '') {
                                finalSelector = baseSelector;
                            } else {
                                finalSelector = `${itemRootSelector.trimEnd()} ${baseSelector.trimStart()}`.trim();
                            }
                        }
                    }

                    if (this.applyMode === 'layout' && this.activeLayoutId && this.activeLayoutId !== 'None') {
                        const layoutSelectorTarget = '#' + this.activeLayoutId;
                        if (baseSelector === ':root' || itemRootSelector === ':root') {
                            finalSelector = layoutSelectorTarget;
                        }
                    }

                    if (finalSelector === selector) {
                        item.fieldSettings.value = value;
                    }
                }
            });
        },

        navigateTo(path) {
            this.currentPath = path;
        },

        goBack() {
            if (this.currentSetting && this.currentSetting.backUrl) {
                this.navigateTo(this.currentSetting.backUrl);
            } else {
                this.navigateTo('/');
            }
        },

        getRootSelector() {
            if (!this.currentSetting) return '';

            if (this.currentSetting.rootSelector) {
                return this.currentSetting.rootSelector;
            }

            const pathSegments = this.currentPath.split('/').filter(p => p);
            let currentPath = '';
            let rootSelector = '';

            for (const segment of pathSegments) {
                currentPath += '/' + segment;
                const setting = this.styleSettingVars.find(item => item.url === currentPath);
                if (setting && setting.rootSelector) {
                    rootSelector = setting.rootSelector;
                }
            }

            return rootSelector;
        },

        handleSettingUpdate(data) {
            this.updateCssProperty(data.selector, data.property, data.value);
        },

        handleBatchUpdate(updates) {
            if (Array.isArray(updates)) {
                updates.forEach(update => {
                    if (update.selector && update.property) {
                        this.updateCssProperty(update.selector, update.property, update.value);
                    }
                });
            }
        },

        handleStyleEditorOpen(setting) {
            this.showStyleSettings = 'styleEditor';
            this.styleEditorData = setting;

            if (window.mw?.top()?.app) {
                window.mw.top().app.dispatch('mw.rte.css.editor2.open', setting);
            }
            this.openRTECssEditor2Vue(setting); // Call to openRTECssEditor2Vue
        },

        goBackFromStyleEditor() {
            if (this.styleEditorData.backUrl) {
                this.navigateTo(this.styleEditorData.backUrl);
            } else {
                this.navigateTo('/');
            }
            this.showStyleSettings = this.currentPath;
            this.styleEditorData = {};
        },

        updateSettings(event, settingKey, optionGroup) {
            let value = event;
            if (event.target) {
                value = event.target.value;
            }

            if (!this.options[optionGroup]) {
                this.$set(this.options, optionGroup, {});
            }

            this.options[optionGroup][settingKey] = value;

            axios.post(window.mw.settings.api_url + 'save_option', {
                'option_group': optionGroup,
                'option_key': settingKey,
                'option_value': value,
            }).then((response) => {
                if (response.data) {
                    if (this.styleSheetSourceFile) {
                        window.mw.app.templateSettings.reloadStylesheet(this.styleSheetSourceFile, this.optionGroupLess);
                    }
                }
            });
        },

        stringToId(str) {
            return str.replace(/[^a-z0-9]/gi, '-').toLowerCase();
        },

        showSettingsGroup(settingGroupKey) {
            let id = 'settings-group-' + this.stringToId(settingGroupKey);
            let el = document.getElementById(id);
            if (el.style.display === 'none') {
                el.style.display = 'block';
            } else {
                el.style.display = 'none';
            }
        },

        resetTemplateSettings() {
            let askForConfirmText = '<div class="">' +
                '<h4 class="">' + window.mw.lang('Are you sure you want to reset template settings ?') + '</h4>' +
                '</div>';

            window.mw.tools.confirm_reset_module_by_id(this.optionGroup, function () {
            }, askForConfirmText);
        },

        resetStylesheetSettings() {
            let askForConfirmText = '<div class="">' +
                '<h4 class="">' + window.mw.lang('Are you sure you want to reset stylesheet settings ?') + '</h4>' +
                '</div>';

            window.mw.tools.confirm_reset_module_by_id(this.optionGroupLess, () => {
                if (window.mw?.top()?.app?.templateSettings) {
                    window.mw.top().app.templateSettings.reloadStylesheet(this.styleSheetSourceFile, this.optionGroupLess);
                }
                setTimeout(function () {
                    window.mw.top().win.location.reload();
                }, 3000);
            }, askForConfirmText);
        },

        loadMoreFonts() {
            if (window.mw?.top()?.app?.fontManager) {
                window.mw.top().app.fontManager.manageFonts();
            }
        },

        checkAIAvailability() {
            this.isAIAvailable = typeof window.mw?.top()?.win?.MwAi === 'function';
        },

        openRTECssEditor2Vue(settings) {
            let iframeStyleEditorId = 'iframeStyleEditorId-Vue';
            let iframeHolder = document.getElementById('iframe-holder');

            if (!iframeHolder) {
                console.error('Cannot open style editor: iframe-holder element not found.');
                return;
            }

            // Clear previous iframe if exists
            let existingIframe = document.getElementById(iframeStyleEditorId);
            if (existingIframe) {
                existingIframe.remove();
            }

            // Create the iframe
            const iframe = document.createElement('iframe');
            iframe.id = iframeStyleEditorId;
            iframe.style.width = '100%';
            iframe.style.height = 'calc(100vh - 220px)'; // Adjust height as needed
            iframe.frameBorder = '0';

            // Determine the correct src for the style editor Vue app
            // This is a placeholder URL and needs to be configured for your application.
            // It should point to the route or HTML page that serves your ElementStyleEditorApp.
            let styleEditorUrl = window.mw?.settings?.site_url + 'editor_tools/style_editor_iframe';
            // Example of adding params, adjust as necessary based on how your style editor app consumes them
            if (settings && settings.type) {
                 styleEditorUrl += '?type=' + encodeURIComponent(settings.type);
            }
            if (settings && settings.selector) {
                 styleEditorUrl += (styleEditorUrl.includes('?') ? '&' : '?') + 'selector=' + encodeURIComponent(settings.selector);
            }
            // Add more params from settings if needed

            iframe.src = styleEditorUrl;

            iframe.onload = () => {
                console.log('Style editor iframe loaded for URL:', styleEditorUrl);
                // The ElementStyleEditorApp.vue (or similar in the iframe) should listen for events
                // like 'mw.rte.css.editor2.open' or receive data via postMessage/URL params
                // to initialize itself with the correct context and settings.
            };

            iframe.onerror = () => {
                console.error('Error loading style editor iframe from URL:', styleEditorUrl);
                iframeHolder.innerHTML = '<p class="text-danger">Error loading style editor. Please check console.</p>';
            }

            iframeHolder.appendChild(iframe);
        },
        setupLayoutListener() {
            // Setup layout selection and tracking
            this.$nextTick(() => {
                if (window.mw?.top()?.app?.canvas) {
                    const activeLayout = window.mw.top().app.liveEdit.getSelectedLayoutNode();
                    window.css_vars_design_active_layout = activeLayout;
                    this.updateLayoutIdDisplay();

                    window.mw.top().app.canvas.on('canvasDocumentClick', () => {
                        const activeLayout = window.mw.top().app.liveEdit.getSelectedLayoutNode();
                        window.css_vars_design_active_layout = activeLayout;
                        this.updateLayoutIdDisplay();
                    });
                }
            });
        },
        setupEventListeners() {
            // Add any additional event listeners here
        },
        updateLayoutIdDisplay() {
            if (this.applyMode === 'layout') {
                const activeLayout = window.css_vars_design_active_layout;
                const layoutId = typeof activeLayout === 'string'
                    ? activeLayout
                    : (activeLayout?.id || activeLayout?.getAttribute?.('id') || 'None');

                this.activeLayoutId = layoutId;
            }
        },

        scrollToSelectedLayout() {
            if (!this.activeLayoutId || this.activeLayoutId === 'None') return;

            const firstLayoutElement = window.mw?.top()?.app?.canvas?.getDocument()?.getElementById(this.activeLayoutId);
            if (firstLayoutElement) {
                window.mw.top().app.canvas.getWindow().mw.tools.scrollTo(firstLayoutElement, 100);
            }
        },

        openSelectedLayoutSettings() {
            if (!this.activeLayoutId || this.activeLayoutId === 'None') return;

            const firstLayoutElement = window.mw?.top()?.app?.canvas?.getDocument()?.getElementById(this.activeLayoutId);
            if (firstLayoutElement) {
                window.mw.top().app.editor.dispatch('onLayoutSettingsRequest', firstLayoutElement);
            }
        },

        async resetAllDesignSelectorsValuesSettings() {
            const askForConfirmText = window.mw.lang('Are you sure you want to reset stylesheet settings ?');
            const confirmed = confirm(askForConfirmText);

            if (!confirmed) return;

            let designSelectors = window.mw_template_settings_styles_and_selectors;
            if (designSelectors) {
                designSelectors = JSON.parse(JSON.stringify(designSelectors));
                designSelectors = this.prepareAndCleanTemplateStylesAndSelectorsData(designSelectors);
                const valuesForEdit = this.prepareTemplateValuesForEdit(designSelectors);

                for (const selector in valuesForEdit) {
                    if (valuesForEdit.hasOwnProperty(selector)) {
                        const properties = valuesForEdit[selector];
                        for (const property in properties) {
                            if (properties.hasOwnProperty(property)) {
                                if (window.mw?.top()?.app?.cssEditor) {
                                    window.mw.top().app.cssEditor.setPropertyForSelector(
                                        selector,
                                        property,
                                        '',
                                        true,
                                        true
                                    );
                                }
                            }
                        }
                    }
                }
            }        },

        registerPropertyChangeListener(callback) {
            // Store callback for Vue 3 event handling since we can't use $on/$off
            if (!this.propertyChangeListeners) {
                this.propertyChangeListeners = [];
            }
            this.propertyChangeListeners.push(callback);
        },

        unregisterPropertyChangeListener(callback) {
            // Remove callback from listeners array
            if (this.propertyChangeListeners) {
                const index = this.propertyChangeListeners.indexOf(callback);
                if (index > -1) {
                    this.propertyChangeListeners.splice(index, 1);
                }
            }
        },
    }
};
</script>
