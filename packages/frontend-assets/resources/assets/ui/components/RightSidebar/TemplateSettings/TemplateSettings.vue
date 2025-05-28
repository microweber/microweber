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
            <div class="ai-settings-wrapper" v-if="hasStyleSettings">
                <label class="live-edit-label mb-2">MAKE YOUR WEBSITE FASTER WITH AI</label>
                <div :class="{'d-none': !isAIAvailable}" class="ai-change-template-design-button"></div>
                <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                        title="Change Design with AI" class="btn btn-link p-0"
                        @click="changeDesign" :disabled="!isAIAvailable">
                    Go with AI
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

export default {
    components: {
        ColorPicker,
        NestedSettingsItem,
        FieldRangeSlider
    },
    data() {
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
            applyMode: 'template',
            activeLayoutId: 'None',
            isAIAvailable: false,
            styleEditorData: {},
            showStyleSettings: '/'
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
        this.fetchData();
        this.checkAIAvailability();
        this.setupEventListeners();
        this.setupLayoutListener();
    },
    methods: {
        async fetchData() {
            try {
                this.isLoading = true;
                const response = await axios.get(window.mw.settings.api_url + 'template/template-style-settings');
                if (response.data) {
                    // Ensure we have proper array/object structures
                    this.settingsGroups = response.data.settingsGroups || {};

                    // Ensure options is an object
                    if (response.data.options && typeof response.data.options === 'object' && !Array.isArray(response.data.options)) {
                        this.options = response.data.options;
                    } else {
                        this.options = {}; // Default to empty object if it's an array, null, or not an object
                    }

                    this.optionGroup = response.data.optionGroup || '';
                    this.optionGroupLess = response.data.optionGroupLess || '';
                    this.styleSheetSourceFile = response.data.styleSheetSourceFile || false;

                    // Ensure styleSettingVars is always an array and has proper structure
                    this.styleSettingVars = Array.isArray(response.data.styleSettingsVars)
                        ? response.data.styleSettingsVars.filter(item => item && typeof item === 'object')
                        : [];

                    // Set global variables from Blade template
                    if (this.styleSettingVars && this.styleSettingVars.length > 0) {
                        window.mw_template_settings_styles_and_selectors = this.styleSettingVars;
                        window.css_vars_design_apply_mode = 'template';
                    }

                    // Ensure options structure for settings groups
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
                this.isLoading = false;
            } catch (error) {
                console.error("Error fetching template settings:", error);
                this.currentError = "Error loading template settings";
                this.isLoading = false;
            }
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

            // Look for rootSelector in the current setting
            if (this.currentSetting.rootSelector) {
                return this.currentSetting.rootSelector;
            }

            // Look for rootSelector in parent items by traversing the URL path
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
            if (!window.mw?.top()?.app?.cssEditor) return;

            window.mw.top().app.cssEditor.setPropertyForSelector(
                data.selector,
                data.property,
                data.value,
                true,
                true
            );
        },

        handleStyleEditorOpen(setting) {
            this.showStyleSettings = 'styleEditor';
            this.styleEditorData = setting;

            // Trigger the style editor open event
            if (window.mw?.top()?.app) {
                window.mw.top().app.dispatch('mw.rte.css.editor2.open', setting);
            }
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
                // Reset template settings
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
            let checkIframeStyleEditor = document.getElementById(iframeStyleEditorId);

            if (!checkIframeStyleEditor) {
                const moduleType = 'microweber/toolbar/editor_tools/rte_css_editor2/rte_editor_vue';
                const attrsForSettings = {
                    live_edit: true,
                    module_settings: true,
                    id: 'mw_global_rte_css_editor2_editor_vue',
                    type: moduleType,
                    iframe: true,
                    disable_auto_element_change: true,
                    output_static_selector: true,
                    from_url: window.mw.top().app.canvas.getWindow().location.href
                };

                const src = window.route('live_edit.module_settings') + "?" + window.json2url(attrsForSettings);
                const iframeHolder = document.getElementById('iframe-holder');

                if (iframeHolder) {
                    iframeHolder.innerHTML += `<iframe id="${iframeStyleEditorId}" src="${src}" style="width:100%;height:500px;border:none;"></iframe>`;

                    document.getElementById(iframeStyleEditorId).addEventListener('load', () => {
                        window.mw.top().app.dispatch('cssEditorSelectElementBySelector', settings.selectors[0]);
                        window.mw.top().app.dispatch('cssEditorSettings', settings);
                    });
                }
            } else {
                window.mw.top().app.dispatch('cssEditorSelectElementBySelector', settings.selectors[0]);
                window.mw.top().app.dispatch('cssEditorSettings', settings);
            }
        },

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
                const propertyValue = window.mw?.top()?.app?.cssEditor?.getPropertyForSelector(pair.selector, pair.property);
                if (pair.target && typeof pair.target.object === 'object' && pair.target.object !== null) {
                    pair.target.object[pair.target.key] = propertyValue;
                }
            }

            let valuesForEdit = {};
            for (const pair of uniquePairs) {
                const propertyValue = window.mw?.top()?.app?.cssEditor?.getPropertyForSelector(pair.selector, pair.property);
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

        setupEventListeners() {
            // Setup event listeners for CSS editor and other MW events
            if (window.mw?.top()?.app) {
                window.mw.top().app.on('fontsChanged', () => {
                    if (window.mw.top().app.fontManager) {
                        window.mw.top().app.fontManager.reloadLiveEdit();
                    }

                    setTimeout(() => {
                        if (window.mw?.top()?.app?.templateSettings) {
                            window.mw.top().app.templateSettings.reloadStylesheet(this.styleSheetSourceFile, this.optionGroupLess);
                        }
                    }, 1000);
                });

                // Setup style editor event listener
                window.mw.top().app.on('mw.rte.css.editor2.open', (settings) => {
                    this.openRTECssEditor2Vue(settings);
                });
            }
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

        handleApplyModeChange() {
            window.css_vars_design_apply_mode = this.applyMode;
            const activeLayout = window.mw?.top()?.app?.liveEdit?.getSelectedLayoutNode();
            window.css_vars_design_active_layout = activeLayout;
            this.updateLayoutIdDisplay();
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
            }
        },

        async changeDesign(about) {
            if (!window.mw?.top()?.win?.MwAi) {
                alert('AI functionality is not available');
                return;
            }

            let designSelectors = JSON.parse(JSON.stringify(window.mw_template_settings_styles_and_selectors));

            if (!about) {
                about = prompt('Please enter the design task description:', 'Make it blue and white');
                if (!about) return;
            }

            designSelectors = this.prepareAndCleanTemplateStylesAndSelectorsData(designSelectors);
            const valuesForEdit = this.prepareTemplateValuesForEdit(designSelectors);

            console.log('valuesForEdit:', valuesForEdit);
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

                                    if (window.mw?.top()?.app?.cssEditor) {
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
