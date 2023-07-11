<template>
    <div class="p-3">

        <div v-if="settingsGroups.length === 0" class="text-center">
            Loading...
        </div>

        <div v-else v-for="(settings,settingGroupKey) in settingsGroups" class="mb-3">
            <a v-on:click="showSettingsGroup(settingGroupKey)"
                  class="fs-2 mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
                {{ settingGroupKey }}
            </a>

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
                                        <select class="form-control"
                                                v-on:change="updateSettings($event, settingKey, setting.optionGroup)"
                                                :name="settingKey"
                                                :value="[setting.value ? setting.value : setting.default]">
                                            <option v-for="(optionValue,optionKey) in setting.options"
                                                    :value="optionKey">
                                                {{ optionValue }}
                                            </option>
                                        </select>
                                    </div>

                                    <div v-if="setting.type === 'dropdown'">
                                        <div>{{ setting.label }}</div>
                                        <select class="form-control"
                                                v-on:change="updateSettings($event, settingKey, setting.optionGroup)"
                                                v-model="options[setting.optionGroup][settingKey]">

                                            <option v-for="(optionValue,optionKey) in setting.options" :value="optionKey">
                                                {{ optionValue }}
                                            </option>
                                        </select>
                                    </div>

                                    <div v-if="setting.type === 'font_selector'">
                                        <div>{{ setting.label }}</div>
                                        <select class="form-control"
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

                <div class="mt-2 mr-2" v-if="settings.type == 'stylesheet'">
                    <button v-on:click="resetStylesheetSettings"
                            style="border-radius: 20px"
                            class="btn btn-dark btn-sm btn-block">Reset Stylesheet Settings
                    </button>
                </div>

                <div class="mt-2 mr-2" v-if="settings.type == 'template'">
                    <button v-on:click="resetTemplateSettings"
                            style="border-radius: 20px"
                            class="btn btn-dark btn-sm btn-block">Reset Template Settings
                    </button>
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
