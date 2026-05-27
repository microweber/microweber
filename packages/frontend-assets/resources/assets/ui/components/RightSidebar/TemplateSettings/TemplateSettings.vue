<template>


    <!-- AI-712 / task-2026-05-16-51c8e8 — Template Settings panel
         unification (slice 1, designer spec DESIGN_AUDIT.md L2.5).
         Three internal content-block patterns now share one visual
         rhythm:
           1. "Choose where to edit" (.edit-mode-toggle-container)
              wears the MwSegmented primitive (AI-684) alongside
              its legacy classes — same active-state visual contract
              as ESE preset toggles. Slice 1 ships this migration.
           2. "Website design settings" link rows (mainStyleGroups
              v-for) — AI-712a follow-up candidate: convert to
              MwField rows with trailing MwToolButton chevron once
              AI-687 (ESE 1.4 MwField primitive) ships.
           3. AI Assistant (FieldAiChangeDesign) — AI-712a follow-up
              candidate: collapse to a single MwField with inline
              send icon once AI-687 ships.
         Section headers visually unified via
         .mw-template-settings-section-header consuming
         --font-label / --weight-label / --letter-label /
         --ese-text-muted per spec. -->
    <div class="mw-template-settings-panel">
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

            <div class="mw-template-settings-back-button-sticky"
                 v-if="currentPath && currentPath !== '/' && !isSingleSettingMode">
                <FieldBackButton
                    v-if="!hasActiveStylePackOpener"
                    :current-path="currentPath"
                    :current-setting="currentSetting"
                    :show-button="currentPath !== '/'"
                    @go-back="navigateTo"
                />

            </div>
            <!-- Choose where to edit toggle -->
            <div v-if="hasStyleSettings" class="form-control-live-edit-label-wrapper"
                 v-show="!isSingleSettingMode">
                <label class="live-edit-label mw-template-settings-section-header mb-1">Choose where to edit</label>
                <p class="text-muted small mb-3" style="margin-top: 0; font-size: 0.75rem; opacity: 0.7;">Choose a page layout template or edit global styles</p>

                <!-- AI-712 slice 1: MwSegmented primitive (AI-684) applied
                     alongside the legacy .edit-mode-toggle-container class
                     so existing CSS keeps rendering while the segmented
                     primitive picks up the active-state ring contract. -->
                <div class="edit-mode-toggle-container mw-segmented">
                    <div
                        class="edit-mode-option mw-segmented__cell"
                        :class="{ active: applyMode === 'template', 'is-active': applyMode === 'template' }"
                        @click="handleApplyModeChange('template')"
                    >
                        <div class="edit-mode-icon d-flex align-items-center gap-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M120-120q-33 0-56.5-23.5T40-200v-80q0-33 23.5-56.5T120-360h240q33 0 56.5 23.5T440-280v80q0 33-23.5 56.5T360-120H120Zm480 0q-33 0-56.5-23.5T520-200v-560q0-33 23.5-56.5T600-840h240q33 0 56.5 23.5T920-760v560q0 33-23.5 56.5T840-120H600Zm-480-80h240v-80H120v80Zm480 0h240v-560H600v560Zm120-40q17 0 28.5-11.5T760-280q0-17-11.5-28.5T720-320q-17 0-28.5 11.5T680-280q0 17 11.5 28.5T720-240ZM120-440q-33 0-56.5-23.5T40-520v-240q0-33 23.5-56.5T120-840h240q33 0 56.5 23.5T440-760v240q0 33-23.5 56.5T360-440H120Zm160-200q17 0 28.5-11.5T320-680q0-17-11.5-28.5T280-720q-17 0-28.5 11.5T240-680q0 17 11.5 28.5T280-640ZM120-533l80-107 90 120h70v-240H120v227Zm120 293Zm480-240ZM240-640Z"/></svg>
                            <span class="live-edit-label mb-0">Template</span>

                        </div>
                        <div class="edit-mode-text">
                            <div class="edit-mode-subtitle mb-0">Edit global template styles</div>
                        </div>
                    </div>

                    <div
                        class="edit-mode-option mw-segmented__cell"
                        :class="{ active: applyMode === 'layout', 'is-active': applyMode === 'layout' }"
                        @click="handleApplyModeChange('layout')"
                    >
                        <div class="edit-mode-icon d-flex align-items-center gap-2 mb-2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M240-280h280v-80H240v80Zm400 0h80v-400h-80v400ZM240-440h280v-80H240v80Zm0-160h280v-80H240v80Zm-80 480q-33 0-56.5-23.5T80-200v-560q0-33 23.5-56.5T160-840h640q33 0 56.5 23.5T880-760v560q0 33-23.5 56.5T800-120H160Zm0-80h640v-560H160v560Zm0 0v-560 560Z"/></svg>
                            <span class="live-edit-label mb-0">Layout</span>

                        </div>
                        <div class="edit-mode-text">
                            <div class="edit-mode-subtitle mb-0">Edit specific layout styles</div>
                        </div>
                    </div>
                </div>

                <div id="layout-id-display" class="mt-3 small text-muted" v-if="applyMode === 'layout'"
                     style="display: block;">

                    <div class="mb-2">
                        <small class="text-muted fw-medium d-flex align-items-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" height="14px" viewBox="0 -960 960 960" width="14px" fill="currentColor"><path d="M240-120q-45 0-89-22t-71-58q26 0 53-20.5t27-59.5q0-50 35-85t85-35q50 0 85 35t35 85q0 66-47 113t-113 47Zm0-80q33 0 56.5-23.5T320-280q0-17-11.5-28.5T280-320q-17 0-28.5 11.5T240-280q0 23-5.5 42T220-202q5 2 10 2h10Zm230-160L360-470l358-358q11-11 27.5-11.5T774-828l54 54q12 12 12 28t-12 28L470-360Zm-190 80Z"/></svg>
                            <label for="" class="live-edit-label mb-0"> Currently editing layout:</label>
                        </small>
                    </div>

                    <div class="d-flex justify-content-between align-items-center p-2 bg-light rounded">
                        <span id="active-layout-id" @click="scrollToSelectedLayout" class="cursor-pointer text-primary">{{ activeLayoutId }}</span>
                        <span id="active-layout-id-open-settings" @click="openSelectedLayoutSettings" class="cursor-pointer text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" height="16px" viewBox="0 -960 960 960" width="16px" fill="currentColor"><path d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z"/></svg>                        </span>
                    </div>
                </div>
            </div>


            <!-- Settings detail when not at root path -->
            <div v-if="currentPath !== '/' && currentSetting && showStyleSettings !== 'styleEditor'">


                <div class="my-5" v-show="!isSingleSettingMode">
                    <label class="live-edit-label" v-if="currentSetting.title">{{ currentSetting.title }}</label>
                    <small v-if="currentSetting.description">{{ currentSetting.description }}</small>
                </div>



                <!-- If currentSetting itself is a field, render it using NestedSettingsItem -->
                <!-- NestedSettingsItem will also handle currentSetting.settings if it exists (for complex fields) -->
                <div v-if="currentSetting.fieldType">

                    <nested-settings-item
                        :setting="currentSetting"
                        :root-selector="getRootSelector()"
                        :is-single-setting-mode="isSingleSettingMode"
                        @navigate="navigateTo"
                        @update="handleSettingUpdate"
                        @batch-update="handleBatchUpdate"
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
                                :is-single-setting-mode="isSingleSettingMode"
                                @navigate="navigateTo"
                                @update="handleSettingUpdate"
                                @batch-update="handleBatchUpdate"
                                @open-style-editor="handleStyleEditorOpen"
                                @style-pack-expanded-state="handleStylePackExpandedState"
                                :ref="el => { if(!nestedItems) nestedItems = []; nestedItems.push(el); }"/>
                        </div>
                    </div>
                    <!-- Option 2: Children are found via subItems (URL matching), and no direct .settings array -->
                    <div v-else-if="subItems && subItems.length > 0">
                        <div v-for="(subItemFromFlatList, index) in subItems" :key="'sub_item_'+index" class="my-3">
                            <nested-settings-item
                                :setting="subItemFromFlatList"
                                :root-selector="getRootSelector()"
                                :is-single-setting-mode="isSingleSettingMode"
                                @navigate="navigateTo"
                                @update="handleSettingUpdate"
                                @batch-update="handleBatchUpdate"
                                @open-style-editor="handleStyleEditorOpen"
                                @style-pack-expanded-state="handleStylePackExpandedState"
                                :ref="el => { if(!nestedItems) nestedItems = []; nestedItems.push(el); }"/>
                        </div>
                    </div>
                    <!-- Fallback: no settings or sub-items available for this group -->
                    <div v-else class="text-muted text-center py-4" style="opacity: 0.6;">
                        <svg xmlns="http://www.w3.org/2000/svg" height="32px" viewBox="0 -960 960 960" width="32px" fill="currentColor" style="opacity: 0.4;" class="mb-2"><path d="M382-240 154-468l57-57 171 171 367-367 57 57-424 424Z"/></svg>
                        <p class="small mb-0">No style settings available for this section in the current template.</p>
                    </div>
                </div>
            </div>

            <!-- Style Editor iframe holder -->
            <div v-if="showStyleSettings === 'styleEditor'" class="mt-3">

                <div class="mw-template-settings-back-button-sticky">

                    <FieldBackButton
                        :current-path="currentPath"
                        :current-setting="styleEditorData"
                        :show-button="true"
                        @go-back="goBackFromStyleEditor"
                    />

                </div>

                <div v-if="!this.isSingleSettingMode">

                    <b v-if="styleEditorData.title">{{ styleEditorData.title }}</b>
                    <p v-if="styleEditorData.description">{{ styleEditorData.description }}</p>
                </div>
                <div class="my-3">
                    <div id="iframe-holder"></div>
                </div>
            </div>
            <!-- Template Settings -->
            <FieldSettingsGroups
                v-if="settingsGroups && Object.keys(settingsGroups).length !== 0"
                :settings-groups="settingsGroups"
                :options="options"
                :supported-fonts="supportedFonts"
                :style-sheet-source-file="styleSheetSourceFile"
                :option-group="optionGroup"
                :option-group-less="optionGroupLess"
                @settings-updated="handleSettingsUpdated"
                @load-more-fonts="loadMoreFonts"
            />


            <!-- Main settings list when at root path -->
            <div v-if="currentPath === '/' && hasStyleSettings && !isSingleSettingMode" class="mt-5">
                <span
                    class="fs-2 font-weight-bold settings-main-group d-flex align-items-center justify-content-between">
                    <span class="live-edit-label mw-template-settings-section-header">Website design settings</span>
                    <button type="button" data-bs-toggle="tooltip" data-bs-placement="top"
                            title="Reset stylesheet settings" class="reset-template-settings-and-stylesheet-button"
                            @click="resetAllDesignSelectorsValuesSettings">
                        <svg xmlns="http://www.w3.org/2000/svg" height="20px" viewBox="0 -960 960 960" width="20px" fill="currentColor"><path d="M204-318q-22-38-33-78t-11-82q0-134 93-228t227-94h7l-64-64 56-56 160 160-160 160-56-56 64-64h-7q-100 0-170 70.5T240-478q0 26 6 51t18 49l-60 60ZM481-40 321-200l160-160 56 56-64 64h7q100 0 170-70.5T720-482q0-26-6-51t-18-49l60-60q22 38 33 78t11 82q0 134-93 228t-227 94h-7l64 64-56 56Z"/></svg>
                          </button>
                </span>

                <div v-for="(settingGroup, index) in mainStyleGroups" :key="index" class="my-3">
                    <a @click="navigateTo(settingGroup.url)"
                       :class="['', '', 'settings-main-group', 'cursor-pointer', '', settingGroup.class]">
                        {{ settingGroup.title }}
                    </a>
                </div>
            </div>


            <!-- AI Design Button -->
            <FieldAiChangeDesign v-if="hasStyleSettings && !isSingleSettingMode" :is-ai-available="isAIAvailable"
                                 @batch-update="handleBatchUpdate"/>
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
import FieldSettingsGroups from './TemplateSettingsFields/FieldSettingsGroups.vue';
import FieldBackButton from './TemplateSettingsFields/FieldBackButton.vue';
import {reactive} from 'vue';

export default {
    components: {
        ColorPicker,
        NestedSettingsItem,
        FieldRangeSlider,
        FieldAiChangeDesign,
        FieldSettingsGroups,
        FieldBackButton
    },
    props: {
        setting: {
            type: String,
            default: null,
            required: false
        }
    },
    provide() {
        return {
            templateSettings: this
        };
    },    data() {
        return {
            shouldTeleport: true,
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
            activeModuleId: 'None',
            activeElementId: 'None',
            isAIAvailable: false,
            styleEditorData: {},
            showStyleSettings: '/',
            styleValues: reactive({}),
            propertyChangeListeners: [], // Array to store registered listeners for Vue 3 event handling
            existingLayoutSelectors: [],
            existingLayoutSelectorsInitialized: false,
            stylePacksExpandedState: {},
            activeStylePackOpener: null,
            hasActiveStylePackOpener: false,
            nestedItems: [], // Array to store references to nested settings items

        };
    },computed: {
        displayedStyleSettingVars() {
            let vars = this.styleSettingVars;

            // Apply layout mode filtering if needed
            if (this.isLayoutMode && this.existingLayoutSelectorsInitialized) {
                vars = this.filterSettingsForLayoutMode(vars, this.existingLayoutSelectors);
            }

            // If setting prop is provided, filter to show only that specific setting
            if (this.setting) {
                vars = this.filterSingleSetting(vars, this.setting);
            }

            return vars;
        },

        hasStyleSettings() {
            return this.displayedStyleSettingVars && this.displayedStyleSettingVars.length > 0;
        },

        mainStyleGroups() {
            if (!this.displayedStyleSettingVars) return [];
            return this.displayedStyleSettingVars.filter(item => item.main === true);
        },

        currentSetting() {
            if (this.currentPath === '/') return null;
            // Ensure we get a setting that is most likely to have children if multiple exist for the same URL
            // Prefer item with a 'settings' array, otherwise the first one found.
            const items = this.displayedStyleSettingVars.filter(item => item.url === this.currentPath);
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
            return this.displayedStyleSettingVars.filter(item => {
                if (!item.url || item.url === this.currentPath) return false;

                const itemSegments = item.url.split('/').filter(p => p);

                if (itemSegments.length === currentPathSegments.length + 1) {
                    return currentPathSegments.every((segment, index) =>
                        segment === itemSegments[index]
                    );
                }
                return false;
            });
        },

        // Design mode checking and selector transformation methods
        isLayoutMode() {
            return this.applyMode === 'layout';
        },

        isTemplateMode() {
            return this.applyMode === 'template';
        },

        // Check if we're in single setting mode (filtering enabled)
        isSingleSettingMode() {
            return this.setting && this.setting.trim() !== '';
        },

        // Get the URL of the first filtered setting to auto-navigate to
        autoNavigationUrl() {
            if (!this.isSingleSettingMode || !this.displayedStyleSettingVars.length) {
                return null;
            }

            // Find the first setting with a URL
            const findFirstUrlInSettings = (settings) => {
                for (const setting of settings) {
                    if (setting.url) {
                        return setting.url;
                    }
                    if (setting.settings && setting.settings.length > 0) {
                        const nestedUrl = findFirstUrlInSettings(setting.settings);
                        if (nestedUrl) {
                            return nestedUrl;
                        }
                    }
                }
                return null;
            };

            return findFirstUrlInSettings(this.displayedStyleSettingVars);
        },    }, mounted() {
        this.fetchData().then(() => {
            this.initializeStyleValues();
            if (this.isLayoutMode) {
                this.fetchExistingLayoutSelectors();
            }

            // Auto-navigate to the first setting when in single setting mode
            if (this.isSingleSettingMode && this.autoNavigationUrl) {
                this.$nextTick(() => {
                    this.currentPath = this.autoNavigationUrl;

                    // Also try to auto-open style pack openers after navigation
                    this.$nextTick(() => {
                        this.autoOpenStylePackOpeners();
                    });
                });
            } else if (this.isSingleSettingMode) {
                // If no auto navigation URL, still try to open style pack openers
                this.$nextTick(() => {
                    this.autoOpenStylePackOpeners();
                });
            }
        });

        // Check AI availability on mount
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
    }, watch: {        applyMode(newMode, oldMode) {
            if (newMode !== oldMode) {
                this.updateLayoutIdDisplay();
                this.initializeStyleValues();

                if (newMode === 'layout') {
                    this.fetchExistingLayoutSelectors();
                } else {
                    this.existingLayoutSelectors = [];
                    this.existingLayoutSelectorsInitialized = false;
                }                // Trigger global reload events for style preview updates when mode changes via watcher
                console.log('Apply mode watcher triggered, mode changed from', oldMode, 'to', newMode);

                // Trigger style pack global reload for preview components
                if (window.mw?.top()?.app) {
                    window.mw.top().app.dispatch('stylePackGlobalReload', {
                        reason: 'applyModeWatcherChanged',
                        newMode: newMode,
                        oldMode: oldMode,
                        isLayoutMode: newMode === 'layout',
                        isTemplateMode: newMode === 'template'
                    });
                }
            }
        },activeLayoutId(newId, oldId) {
            if (newId !== oldId) {
                const newActiveLayout = newId === 'None' || !newId ? null : window.mw?.top()?.app?.canvas?.getDocument()?.getElementById(newId);
                this.initializeStyleValues();

                if (this.isLayoutMode) {
                    this.fetchExistingLayoutSelectors();
                }                // Trigger global reload events for style preview updates when layout changes in single layout editing mode
                if (this.isSingleSettingMode && this.isLayoutMode) {
                    console.log('Layout changed in single layout editing mode, triggering global reload events');

                    // Trigger style pack global reload for preview components
                    if (window.mw?.top()?.app) {
                        window.mw.top().app.dispatch('stylePackGlobalReload', {
                            reason: 'layoutChanged',
                            newLayoutId: newId,
                            oldLayoutId: oldId,
                            isSingleSettingMode: true,
                            isLayoutMode: true
                        });
                    }
                }
            }
        },
        styleSettingVars: {
            handler() {
                this.initializeStyleValues();
            },
            deep: true
        }, currentPath() {
            // When path changes, the relevant rootSelector might change, so re-evaluating values might be needed
            // if not all values are pre-cached. For now, initializeStyleValues fetches all.
        },        // Watch for changes in displayedStyleSettingVars when in single setting mode
        displayedStyleSettingVars: {
            handler(newVars) {
                // Clear nested items array when settings change
                this.nestedItems = [];

                if (this.isSingleSettingMode && newVars && newVars.length > 0) {
                    console.log('displayedStyleSettingVars changed in single setting mode:', newVars.length, 'items');

                    if (this.autoNavigationUrl) {
                        this.$nextTick(() => {
                            if (this.currentPath === '/') {
                                this.currentPath = this.autoNavigationUrl;
                            }
                            // Auto-open style pack openers in single setting mode
                            this.$nextTick(() => {
                                this.autoOpenStylePackOpeners();
                            });
                        });
                    } else {
                        // If no auto navigation URL, still try to open style pack openers
                        this.$nextTick(() => {
                            this.autoOpenStylePackOpeners();
                        });
                    }
                }
            },
            immediate: false
        }
    },

    methods: {
        fetchExistingLayoutSelectors() {
            let selectors = [];
            const layoutElement = this.getActiveLayoutElement();

            if (layoutElement && window.mw?.top()?.app?.cssEditor?.getUsedSelectorsForElement) {
                // Get selectors specifically for the layout element
                selectors = window.mw.top().app.cssEditor.getUsedSelectorsForElement(layoutElement);
            } else if (layoutElement && window.mw?.top()?.app?.cssEditor?.getUsedSelectors) {
                // Fallback: get all selectors and filter for layout-specific ones
                const allSelectors = window.mw.top().app.cssEditor.getUsedSelectors();
                const layoutId = this.getActiveLayoutId();
                if (layoutId) {
                    selectors = allSelectors.filter(selector =>
                        selector.includes('#' + layoutId) || selector === ':root'
                    );
                }
            } else {
                console.warn('getUsedSelectors function not found for layout mode filtering.');
            }

            this.existingLayoutSelectors = Array.isArray(selectors) ? selectors : [];
            this.existingLayoutSelectorsInitialized = true;
        },

        getActiveLayoutElement() {
            const layoutId = this.getActiveLayoutId();
            if (!layoutId) return null;
            return window.mw?.top()?.app?.canvas?.getDocument()?.getElementById(layoutId);
        },

        filterSettingsForLayoutMode(settings, existingSelectors, parentRootSelector = '') {
            if (!this.isLayoutMode || !this.existingLayoutSelectorsInitialized) {
                return settings;
            }

            const effectiveExistingSelectors = Array.isArray(existingSelectors) ? existingSelectors : [];
            const layoutId = this.getActiveLayoutId();
            const layoutSelector = layoutId ? '#' + layoutId : '';

            return settings.reduce((acc, setting) => {
                const currentGroupRootSelector = setting.rootSelector || parentRootSelector;

                if (setting.settings && Array.isArray(setting.settings)) { // It's a group
                    let groupItselfVisible = true;
                    if (setting.rootSelector) {
                        // Check if group's root selector is relevant to current layout
                        if (setting.rootSelector === ':root') {
                            groupItselfVisible = true; // :root is always relevant
                        } else if (layoutSelector && setting.rootSelector.includes(layoutSelector)) {
                            groupItselfVisible = true; // Directly related to layout
                        } else if (effectiveExistingSelectors.includes(setting.rootSelector)) {
                            groupItselfVisible = true; // Selector exists in layout
                        } else {
                            groupItselfVisible = false;
                        }
                    }

                    if (groupItselfVisible) {
                        const filteredChildren = this.filterSettingsForLayoutMode(setting.settings, effectiveExistingSelectors, currentGroupRootSelector);
                        if (filteredChildren.length > 0) {
                            acc.push({...setting, settings: filteredChildren});
                        } else if (setting.url && setting.title && !setting.fieldType) {
                            // Keep navigational groups even if empty
                            acc.push({...setting, settings: []});
                        }
                    }
                } else if (setting.fieldType) { // It's a field
                    if (setting.fieldType === 'clearAll') {
                        acc.push(setting); // Always show clearAll fields
                    } else {
                        const fieldSpecificSelectors = setting.selectors && Array.isArray(setting.selectors) && setting.selectors.length > 0 ? setting.selectors : null;
                        let isVisible = false;

                        if (fieldSpecificSelectors) {
                            // Check if any field selector is relevant to layout
                            for (const selector of fieldSpecificSelectors) {
                                if (selector === ':root') {
                                    isVisible = true;
                                    break;
                                } else if (layoutSelector && selector.includes(layoutSelector)) {
                                    isVisible = true;
                                    break;
                                } else if (effectiveExistingSelectors.includes(selector)) {
                                    isVisible = true;
                                    break;
                                }
                            }
                        } else if (currentGroupRootSelector) {
                            // Use parent group's root selector
                            if (currentGroupRootSelector === ':root') {
                                isVisible = true;
                            } else if (layoutSelector && currentGroupRootSelector.includes(layoutSelector)) {
                                isVisible = true;
                            } else if (effectiveExistingSelectors.includes(currentGroupRootSelector)) {
                                isVisible = true;
                            }
                        }

                        if (isVisible) {
                            acc.push(setting);
                        }
                    }
                } else { // Other items (titles, descriptions, etc.)
                    acc.push(setting);
                }

                return acc;
            }, []);
        },        // Design mode checking and selector transformation methods
        getActiveLayoutId() {
            if (!this.isLayoutMode) return null;
            return this.activeLayoutId && this.activeLayoutId !== 'None' ? this.activeLayoutId : null;
        },


        updateActiveLayoutFromElement(activeLayoutElement) {
            if (activeLayoutElement) {
                const layoutId = typeof activeLayoutElement === 'string'
                    ? activeLayoutElement
                    : (activeLayoutElement?.id || activeLayoutElement?.getAttribute?.('id'));
                this.activeLayoutId = layoutId || 'None';
            } else {
                this.activeLayoutId = 'None';
            }
        },

        transformSelectorBasedOnMode(selector, rootSelector = '') {
            // If in template mode, no transformation needed
            if (this.isTemplateMode) {
                return this.applySelectorWithRoot(selector, rootSelector);
            }

            // If in layout mode, transform :root selectors to layout ID
            if (this.isLayoutMode) {
                const layoutId = this.getActiveLayoutId();
                if (!layoutId) {
                    console.warn('Layout mode active but no layout ID found');
                    return this.applySelectorWithRoot(selector, rootSelector);
                }

                const layoutSelectorTarget = '#' + layoutId;

                // Transform :root to layout ID
                if (selector === ':root') {
                    return layoutSelectorTarget;
                }

                // If the rootSelector is :root, use layout ID instead
                if (rootSelector === ':root') {
                    return selector ? `${layoutSelectorTarget} ${selector}`.trim() : layoutSelectorTarget;
                }

                // Apply normal root selector logic but with potential layout transformation
                return this.applySelectorWithRoot(selector, rootSelector);
            }

            return selector;
        },

        applySelectorWithRoot(selector, rootSelector = '') {
            if (!rootSelector || !selector) return selector || rootSelector || '';

            if (selector === ':root') {
                return rootSelector;
            } else if (selector.startsWith(rootSelector) && rootSelector !== '') {
                return selector;
            } else {
                const rs = rootSelector.trimEnd();
                const s = selector.trimStart();
                return `${rs} ${s}`.trim();
            }
        },

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
        }, initializeStyleValues() {
            if (!this.displayedStyleSettingVars || !window.mw?.top()?.app?.cssEditor) {
                this.styleValues = reactive({});
                return;
            }
            const newStyleValues = {};
            const itemsToProcess = this.flattenStyleSettings(this.displayedStyleSettingVars);

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
        },


        getCssPropertyValue(selector, property) {
            // Get the root selector for the current context
            const rootSelector = this.getRootSelector();

            // Transform selector based on current design mode
            const transformedSelector = this.transformSelectorBasedOnMode(selector, rootSelector);

            const key = `${transformedSelector}|${property}`;
            if (this.styleValues.hasOwnProperty(key)) {
                return this.styleValues[key];
            }
            // Fallback to cssEditor if not in cache
            if (window.mw?.top()?.app?.cssEditor) {
                const value = window.mw.top().app.cssEditor.getPropertyForSelector(transformedSelector, property);
                // Cache the value for future use
                this.styleValues[key] = value;
                return value;
            }
            return undefined;
        },

        updateCssProperty(selector, property, value) {
            // Get the root selector for the current context
            const rootSelector = this.getRootSelector();

            // Transform selector based on current design mode
            const transformedSelector = this.transformSelectorBasedOnMode(selector, rootSelector);


            if (window.mw?.top()?.app?.cssEditor) {
                window.mw.top().app.cssEditor.setPropertyForSelector(transformedSelector, property, value, true, true);
                // Update our local cache with the transformed selector
                const key = `${transformedSelector}|${property}`;
                this.styleValues[key] = value;
            }
        }, onPropertyChange({selector, property, value}) {
            const key = `${selector}|${property}`;
            // Update the reactive styleValues cache
            this.styleValues[key] = value;

            // Also update any setting fieldSettings.value that matches this selector and property
            this.updateSettingFieldValues(selector, property, value);

            // Notify all registered listeners (for Vue 3 event handling)
            if (this.propertyChangeListeners && this.propertyChangeListeners.length > 0) {
                const eventData = {selector, property, value};
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
            const itemsToProcess = this.flattenStyleSettings(this.displayedStyleSettingVars);
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
            // Prevent navigation when in single setting mode and style pack is open
            if (this.isSingleSettingMode && this.hasActiveStylePackOpener) {
                return;
            }

            // Prevent navigation when in single setting mode
            if (this.isSingleSettingMode) {
                return;
            }

            // Try to collapse any active style pack first
            if (this.hasActiveStylePackOpener && path !== this.currentPath) {
                if (this.collapseActiveStylePack()) {
                    return; // Stop navigation if we collapsed a style pack
                }
            }

            console.log('Navigating to ' + path)

            this.currentPath = path;
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
                const setting = this.displayedStyleSettingVars.find(item => item.url === currentPath);
                if (setting && setting.rootSelector) {
                    rootSelector = setting.rootSelector;
                }
            }

            return rootSelector;
        },

        handleSettingUpdate(data) {
            // Handle regular field updates with single property changes
            this.updateCssProperty(data.selector, data.property, data.value);
        },


        handleBatchUpdate(updates) {
            // Handle batch updates for multiple properties (like color palettes or clear all)
            if (Array.isArray(updates) && updates.length > 0) {
                // Group updates by selector to use bulk update
                const updatesBySelector = {};

                updates.forEach(update => {
                    if (update.selector && update.property !== undefined) {
                        // Get the root selector for the current context
                        const rootSelector = this.getRootSelector();

                        // Transform selector based on current design mode
                        const transformedSelector = this.transformSelectorBasedOnMode(update.selector, rootSelector);

                        if (!updatesBySelector[transformedSelector]) {
                            updatesBySelector[transformedSelector] = {};
                        }

                        updatesBySelector[transformedSelector][update.property] = update.value;

                        // Update our local cache
                        const key = `${transformedSelector}|${update.property}`;
                        this.styleValues[key] = update.value;
                    }
                });

                // Apply bulk updates using setPropertyForSelectorBulk
                if (window.mw?.top()?.app?.cssEditor && Object.keys(updatesBySelector).length > 0) {


                    window.mw?.top().app.registerAskUserToStay(true);


                    for (const selector in updatesBySelector) {
                        if (updatesBySelector.hasOwnProperty(selector)) {
                            const properties = updatesBySelector[selector];

                            // Use the bulk update method from stylesheet-editor service
                            window.mw.top().app.cssEditor.setPropertyForSelectorBulk(
                                selector,
                                properties,
                                true, // record = true
                                false // skipMedia = false
                            );
                        }
                    }
                }
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


        goBackFromStyleEditor(path) {
            // If path is provided by FieldBackButton, use it; otherwise use the original logic
            if (path) {
                this.navigateTo(path);
            } else if (this.styleEditorData.backUrl) {
                this.navigateTo(this.styleEditorData.backUrl);
            } else {
                this.navigateTo('/');
            }
            this.showStyleSettings = this.currentPath;
            this.styleEditorData = {};
        },

        loadMoreFonts() {
            if (window.mw?.top()?.app?.fontManager) {
                window.mw.top().app.fontManager.manageFonts();
            }
        },

        checkAIAvailability() {
            const isAvailable = typeof window.mw?.top()?.win?.MwAi === 'function';
            this.isAIAvailable = isAvailable;

            // Set global indicator for template use
            window.mwAIAvailable = isAvailable;
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


            let styleEditorUrl = window.mw?.settings?.site_url + 'editor_tools/style_editor_iframe';
            if (settings && settings.type) {
                styleEditorUrl += '?type=' + encodeURIComponent(settings.type);
            }
            if (settings && settings.selector) {
                styleEditorUrl += (styleEditorUrl.includes('?') ? '&' : '?') + 'selector=' + encodeURIComponent(settings.selector);
            }

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
        }, setupLayoutListener() {
            // Setup layout selection and tracking
            this.$nextTick(() => {
                if (window.mw?.top()?.app?.canvas) {
                    const activeLayout = window.mw.top().app.liveEdit.getSelectedLayoutNode();
                    this.updateActiveLayoutFromElement(activeLayout);
                    const activeModule = window.mw.top().app.liveEdit.getSelectedModuleNode();
                    this.updateActiveModuleFromElement(activeModule);
                    const activeElement = window.mw.top().app.liveEdit.getSelectedElementNode();
                    this.updateActiveElementFromElement(activeElement);
                    this.updateLayoutIdDisplay();

                    window.mw.top().app.canvas.on('canvasDocumentClick', () => {
                        const activeLayout = window.mw.top().app.liveEdit.getSelectedLayoutNode();
                        this.updateActiveLayoutFromElement(activeLayout);
                        const activeModule = window.mw.top().app.liveEdit.getSelectedModuleNode();
                        this.updateActiveModuleFromElement(activeModule);
                        const activeElement = window.mw.top().app.liveEdit.getSelectedElementNode();
                        this.updateActiveElementFromElement(activeElement);
                        this.updateLayoutIdDisplay();
                    });
                }
            });
        },

        updateActiveModuleFromElement(activeModuleElement) {
            if (activeModuleElement) {
                const moduleId = typeof activeModuleElement === 'string'
                    ? activeModuleElement
                    : (activeModuleElement?.id || activeModuleElement?.getAttribute?.('id'));
                this.activeModuleId = moduleId || 'None';
            } else {
                this.activeModuleId = 'None';
            }
        },

        updateActiveElementFromElement(activeElementElement) {
            if (activeElementElement) {
                const elementId = typeof activeElementElement === 'string'
                    ? activeElementElement
                    : (activeElementElement?.id || activeElementElement?.getAttribute?.('id'));
                this.activeElementId = elementId || 'None';
            } else {
                this.activeElementId = 'None';
            }
        },


        setupEventListeners() {
            // Add any additional event listeners here
        },

        handleSettingsUpdated(eventData) {
            // Handle settings update from FieldSettingsGroups component
            // The component already handles the API call and stylesheet reload
            // This is just for any additional parent component logic if needed
            console.log('Settings updated:', eventData);
        }, updateLayoutIdDisplay() {
            if (this.applyMode === 'layout') {
                this.activeLayoutId = this.activeLayoutId || 'None';
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

            const firstLayoutElement = window.mw?.top()?.app?.canvas?.getDocument().getElementById(this.activeLayoutId);
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

        registerPropertyChangeListener(callback) {
            // Store callback for Vue 3 event handling since we can't use $on/$off
            if (!this.propertyChangeListeners) {
                this.propertyChangeListeners = [];
            }
            this.propertyChangeListeners.push(callback);
        }, unregisterPropertyChangeListener(callback) {
            // Remove callback from listeners array
            if (this.propertyChangeListeners) {
                const index = this.propertyChangeListeners.indexOf(callback);
                if (index > -1) {
                    this.propertyChangeListeners.splice(index, 1);
                }
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
            if (this.applyMode === 'layout' && this.activeLayoutId && this.activeLayoutId !== 'None') {
                const layoutId = this.activeLayoutId;

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
                this.applyMode === 'layout' &&
                this.activeLayoutId && this.activeLayoutId !== 'None') {
                const layoutId = this.activeLayoutId;
                if (layoutId) {
                    valuesForEdit['#' + layoutId] = {};
                }
            }

            return valuesForEdit;
        },

        handleStylePackExpandedState(data) {
            const {id, isExpanded} = data;

            // Store the expanded state by id
            this.stylePacksExpandedState[id] = isExpanded;

            // Track the currently active style pack opener
            if (isExpanded) {
                this.activeStylePackOpener = id;
                this.hasActiveStylePackOpener = true;
            } else if (this.activeStylePackOpener === id) {
                this.activeStylePackOpener = null;
                this.hasActiveStylePackOpener = false;
            }
        },

        collapseActiveStylePack() {
            if (this.hasActiveStylePackOpener && this.nestedItems) {

                console.log('Closing style pack');

                for (const item of this.nestedItems) {
                    if (item && typeof item.collapseStylePack === 'function') {
                        if (item.collapseStylePack()) {
                            this.activeStylePackOpener = null;
                            this.hasActiveStylePackOpener = false;
                            return true;
                        }
                    }
                }
            }
            return false;
        },        filterSingleSetting(settings, settingParameter) {
            if (!settingParameter || !Array.isArray(settings)) {
                return settings;
            }

            // Helper function to recursively search through settings
            const findMatchingSetting = (settingsArray, searchTerm) => {
                const results = [];

                for (const setting of settingsArray) {
                    let isMatch = false;

                    // Check if the setting matches by title (case insensitive)
                    if (setting.title && setting.title.toLowerCase().includes(searchTerm.toLowerCase())) {
                        isMatch = true;
                    }

                    // Check if the setting matches by fieldType
                    if (setting.fieldType && setting.fieldType.toLowerCase() === searchTerm.toLowerCase()) {
                        isMatch = true;
                    }

                    // Check if the setting matches by URL
                    if (setting.url && setting.url.toLowerCase().includes(searchTerm.toLowerCase())) {
                        isMatch = true;
                    }

                    if (isMatch) {
                        results.push(setting);
                    }

                    // Recursively search in nested settings
                    if (setting.settings && Array.isArray(setting.settings)) {
                        const nestedResults = findMatchingSetting(setting.settings, searchTerm);
                        if (nestedResults.length > 0) {
                            // If we found matches in nested settings, include the parent with filtered children
                            results.push({
                                ...setting,
                                settings: nestedResults
                            });
                        }
                    }
                }

                return results;
            };

            return findMatchingSetting(settings, settingParameter);
        },        // Auto-open style pack openers when in single setting mode
        autoOpenStylePackOpeners() {
            if (!this.isSingleSettingMode) {
                console.log('Not in single setting mode, skipping auto-open');
                return;
            }

            console.log('Single setting mode active - style packs should auto-expand via their own logic');
            // The FieldStylePack components will handle auto-expansion themselves
            // This method is kept for potential future use or manual triggering
        },// Open a style pack opener and disable navigation
        openStylePackOpener(setting) {
            console.log('Opening style pack opener:', setting.title);

            // Mark that we have an active style pack opener
            this.hasActiveStylePackOpener = true;
            this.activeStylePackOpener = setting.url || 'auto-opened';

            // Use a longer delay to ensure all nested components are fully rendered
            setTimeout(() => {
                // Find the nested item component and trigger expansion
                if (this.nestedItems && this.nestedItems.length > 0) {
                    console.log('Found nestedItems:', this.nestedItems.length);
                    for (const item of this.nestedItems) {
                        if (item && typeof item.expandStylePack === 'function') {
                            console.log('Trying to expand style pack for item:', item.setting?.title);
                            if (item.expandStylePack(setting)) {
                                console.log('Successfully expanded style pack');
                                break;
                            }
                        }
                    }
                } else {
                    console.log('No nested items found, retrying...');
                    // Retry after another delay if nested items aren't ready yet
                    setTimeout(() => {
                        if (this.nestedItems && this.nestedItems.length > 0) {
                            console.log('Retry - Found nestedItems:', this.nestedItems.length);
                            for (const item of this.nestedItems) {
                                if (item && typeof item.expandStylePack === 'function') {
                                    console.log('Retry - Trying to expand style pack for item:', item.setting?.title);
                                    if (item.expandStylePack(setting)) {
                                        console.log('Retry - Successfully expanded style pack');
                                        break;
                                    }
                                }
                            }
                        }
                    }, 500);
                }
            }, 500); // Increased delay to 500ms
        },        handleApplyModeChange(newMode) {
            console.log('Apply mode changing from', this.applyMode, 'to', newMode);

            if (typeof newMode === 'string') {
                this.applyMode = newMode;
            } else {
                // Handle event from select element (legacy support)
                this.applyMode = newMode.target.value;
            }

            // Existing logic for mode change
            this.updateLayoutIdDisplay();
            this.initializeStyleValues();

            if (this.applyMode === 'layout') {
                this.fetchExistingLayoutSelectors();
            } else {
                this.existingLayoutSelectors = [];
                this.existingLayoutSelectorsInitialized = false;
            }            // Trigger global reload events for style preview updates when switching modes
            console.log('Apply mode changed to:', this.applyMode, '- triggering global reload events');

            // Trigger style pack global reload for preview components
            if (window.mw?.top()?.app) {
                window.mw.top().app.dispatch('stylePackGlobalReload', {
                    reason: 'applyModeChanged',
                    newMode: this.applyMode,
                    isLayoutMode: this.isLayoutMode,
                    isTemplateMode: this.isTemplateMode
                });
            }

            console.log('Apply mode changed to:', this.applyMode);
        },
    }
};
</script>
