<template>
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
</template>

<script>
import axios from 'axios';
import ColorPicker from '../../../../apps/ElementStyleEditor/components/ColorPicker.vue';
import FieldRangeSlider from './FieldRangeSlider.vue';

export default {
    name: 'FieldSettingsGroups',
    components: {
        ColorPicker,
        FieldRangeSlider
    },
    inject: ['templateSettings'],
    props: {
        settingsGroups: {
            type: Object,
            default: () => ({})
        },
        options: {
            type: Object,
            default: () => ({})
        },
        supportedFonts: {
            type: Array,
            default: () => []
        },
        styleSheetSourceFile: {
            type: [String, Boolean],
            default: false
        },
        optionGroup: {
            type: String,
            default: ''
        },
        optionGroupLess: {
            type: String,
            default: ''
        }
    },
    methods: {
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

            // Emit to parent component
            this.$emit('settings-updated', {
                settingKey,
                optionGroup,
                value
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
            this.$emit('load-more-fonts');
        }
    }
};
</script>

<style scoped>
.settings-main-group {
    cursor: pointer;
}
</style>