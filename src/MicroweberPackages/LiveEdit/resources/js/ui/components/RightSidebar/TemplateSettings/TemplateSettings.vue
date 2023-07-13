<template>
    <div class="p-3">

        <div v-if="settingsGroups.length === 0" class="text-center">
            Loading...
        </div>

        <div v-else v-for="(settings,settingGroupKey) in settingsGroups" class="mb-3">
            <div class="d-flex align-items-center justify-content-between">
                <a v-on:click="showSettingsGroup(settingGroupKey)"
                   class="fs-2 mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                    {{ settingGroupKey }}
                </a>

                <div v-if="settings.type == 'stylesheet'">
                    <span v-on:click="resetStylesheetSettings">
                        <svg class="cursor-pointer mb-2" fill="currentColor" v-tooltip data-bs-toggle="tooltip" data-bs-placement="top" title="Reset stylesheet settings" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="M451-122q-123-10-207-101t-84-216q0-77 35.5-145T295-695l43 43q-56 33-87 90.5T220-439q0 100 66 173t165 84v60Zm60 0v-60q100-12 165-84.5T741-439q0-109-75.5-184.5T481-699h-20l60 60-43 43-133-133 133-133 43 43-60 60h20q134 0 227 93.5T801-439q0 125-83.5 216T511-122Z"/></svg>
                    </span>
                </div>


                <div v-if="settings.type == 'template'">
                    <span v-on:click="resetTemplateSettings">
                        <svg class="cursor-pointer mb-2" fill="currentColor" v-tooltip data-bs-toggle="tooltip" data-bs-placement="top" title="Reset template settings" xmlns="http://www.w3.org/2000/svg" height="18" viewBox="0 -960 960 960" width="18"><path d="M451-122q-123-10-207-101t-84-216q0-77 35.5-145T295-695l43 43q-56 33-87 90.5T220-439q0 100 66 173t165 84v60Zm60 0v-60q100-12 165-84.5T741-439q0-109-75.5-184.5T481-699h-20l60 60-43 43-133-133 133-133 43 43-60 60h20q134 0 227 93.5T801-439q0 125-83.5 216T511-122Z"/></svg>
                    </span>
                </div>
            </div>

            <div class="mt-3" style="display:none" :id="'settings-group-' + stringToId(settingGroupKey)">
                <div class="" :id="'accordionFlush' + stringToId(settingGroupKey)">

                    <div v-for="(settingGroupInside,settingGroupInsideName) in settings.values" class="mb-2">

                        <label
                            :id="'flush-heading-' + stringToId(settingGroupKey +'-'+ settingGroupInsideName)">
                            <a class="mw-admin-action-links mw-adm-liveedit-tabs collapsed" type="button" data-bs-toggle="collapse"
                                    :data-bs-target="'#flush-collapse-' + stringToId(settingGroupKey +'-'+ settingGroupInsideName)"
                                    aria-expanded="false"
                                    :aria-controls="'flush-collapse-' + stringToId(settingGroupKey +'-'+ settingGroupInsideName)">
                                {{ settingGroupInsideName }}
                            </a>
                        </label>

                        <div
                            :id="'flush-collapse-' + stringToId(settingGroupKey +'-'+ settingGroupInsideName)"
                            class="accordion-collapse collapse"
                            :aria-labelledby="'flush-heading-' + stringToId(settingGroupKey +'-'+ settingGroupInsideName)"
                            :data-bs-parent="'#accordionFlush' + stringToId(settingGroupKey)">

                            <div class="accordion-body">
                                <div v-for="(setting,settingKey) in settingGroupInside" class="mt-2">

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
                                                      :name="settingKey" />
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
                                             {{options[setting.optionGroup][settingKey]}}
                                             <span v-if="setting.range.unit">
                                                    {{ setting.range.unit ? setting.range.unit : '' }}
                                             </span>
                                         </label>
                                         <div>
                                             <Slider
                                                 :min="[setting.range.min ? setting.range.min : 0]"
                                                 :max="[setting.range.max ? setting.range.max : 100]"
                                                 :step="[setting.range.step ? setting.range.step : 1]"
                                                 v-on:change="updateSettings($event, settingKey, setting.optionGroup)"
                                                 v-model="options[setting.optionGroup][settingKey]"
                                                 :merge="1"
                                                 :tooltips="false"
                                                 :tooltipPosition="'right'"
                                             />
                                         </div>
                                    </div>

                                    <div v-if="setting.type === 'dropdown_image'">
                                        <div>{{ setting.label }}</div>
                                        <select class="form-select"
                                                v-on:change="updateSettings($event, settingKey, setting.optionGroup)"
                                                :name="settingKey"
                                                :value="[setting.value ? setting.value : setting.default]">
                                            <option v-for="(optionValue,optionKey) in setting.options"
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

                                            <option v-for="(optionValue,optionKey) in setting.options" :value="optionKey">
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
                                            <option value="Arial">Arial</option>
                                            <option value="Tahoma">Tahoma</option>
                                        </select>
                                    </div>

                                </div>
                            </div>

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


<style src="@vueform/slider/themes/default.css"></style>
<script>
import axios from 'axios';
import ColorPicker from '../../Editor/Colors/ColorPicker.vue';
import Slider from '@vueform/slider'

export default {
    components: {
        ColorPicker,
        Slider
    },
    methods: {
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
        updateSettings(event, settingKey, optionGroup) {

            let value = event;
            if (event.target) {
                value = event.target.value;
            }

            this.options[optionGroup][settingKey] = value;

            let appInstance = this;
            axios.post(mw.settings.api_url + 'save_option', {
                'option_group': optionGroup,
                'option_key': settingKey,
                'option_value': value,
            }).then(function (response) {
                if (response.data) {
                    // saved
                    if (appInstance.styleSheetSourceFile) {
                        mw.app.templateSettings.reloadStylesheet(appInstance.styleSheetSourceFile, appInstance.optionGroupLess);
                    }
                }
            });
        },
        resetTemplateSettings() {
            mw.tools.confirm_reset_module_by_id(this.optionGroup, function () {
                // Reset template settings
            });
        },
        resetStylesheetSettings() {
            mw.tools.confirm_reset_module_by_id(this.optionGroupLess, function () {
                // Reset template settings
            });
        }
    },
    mounted() {
        let appInstance = this;
        axios.get(mw.settings.api_url + 'editor/template_settings_v2/list').then(function (response) {
            if (response.data) {
                appInstance.settingsGroups = response.data.settingsGroups;
                appInstance.options = response.data.options;
                appInstance.optionGroup = response.data.optionGroup;
                appInstance.optionGroupLess = response.data.optionGroupLess;
                appInstance.styleSheetSourceFile = response.data.styleSheetSourceFile;
            }
        });
    },
    data() {
        return {
            settingsGroups: [],
            options: {},
            optionGroup: '',
            optionGroupLess: '',
            styleSheetSourceFile: false
        }
    }
}
</script>
