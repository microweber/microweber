<template>
    <div class="p-3">

        <div v-if="settingsGroups.length === 0" class="text-center">
            Loading...
        </div>

        <div v-else v-for="(settings,settingGroupKey) in settingsGroups" class="mb-3">

            <div>
                <span v-on:click="showSettingsGroup(settingGroupKey)" class="border-bottom pt-1 pb-1 settings-main-group">
                    {{ settingGroupKey }}
                </span>
            </div>

            <div style="display:none" :id="'settings-group-' + stringToId(settingGroupKey)">
             <div class="accordion accordion-flush" :id="'accordionFlush' + stringToId(settingGroupKey)">

                <div v-for="(settingGroupInside,settingGroupInsideName) in settings" class="accordion-item">

                    <h2 class="accordion-header" :id="'flush-heading-' + stringToId(settingGroupInsideName)">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" :data-bs-target="'#flush-collapse-' + stringToId(settingGroupInsideName)" aria-expanded="false" :aria-controls="'flush-collapse-' + stringToId(settingGroupInsideName)">
                            {{settingGroupInsideName}}
                        </button>
                    </h2>
                    <div
                        v-for="(setting,settingKey) in settingGroupInside"
                        :id="'flush-collapse-' + stringToId(settingGroupInsideName)"
                        class="accordion-collapse collapse"
                        :aria-labelledby="'flush-heading-' + stringToId(settingGroupInsideName)"
                        :data-bs-parent="'#accordionFlush' + stringToId(settingGroupKey)">

                          <div class="accordion-body">
                            <div v-if="setting.type === 'delimited'">
                                <hr/>
                            </div>

                        <div v-if="setting.type === 'color'">
                            <div class="d-flex justify-content-between">
                                <div class="mr-4">{{ setting.label }}</div>
                                <div>
                                <input type="color" class="w-10px"
                                       :value="setting.value"
                                       v-on:change="updateSettings($event, settingKey)"
                                       :name="settingKey">
                                </div>
                            </div>
                        </div>

                        <div v-if="setting.type === 'title'">
                            <div class="text-uppercase">
                                <span>{{ setting.label }}</span>
                            </div>
                        </div>

                        <div v-if="setting.type === 'dropdown_image'">
                            <div>{{ setting.label }}</div>
                            <select class="form-control" v-on:change="updateSettings($event, settingKey)"
                                    :name="settingKey">
                                <option v-for="(optionValue,optionKey) in setting.options" :value="optionKey">
                                    {{ optionValue }}
                                </option>
                            </select>
                        </div>

                        <div v-if="setting.type === 'dropdown'">
                            <div>{{ setting.label }}</div>
                            <select class="form-control" v-on:change="updateSettings($event, settingKey)"
                                    :name="settingKey">
                                <option v-for="(optionValue,optionKey) in setting.options" :value="optionKey">
                                    {{ optionValue }}
                                </option>
                            </select>
                        </div>
                   </div>
                   </div>

                </div>

            </div>
            </div>
        </div>

        <div class="mt-2">
            <button v-on:click="resetTemplateSettings"
                    class="btn btn-primary btn-sm btn-block">Reset Template Settings
            </button>
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

export default {
    methods: {
        stringToId(str) {
            return str.replace(/[^a-z0-9]/gi, '_').toLowerCase();
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
        updateSettings(event, settingKey) {
            let value = event.target.value;
            let appInstance = this;
            axios.post('/api/save_option', {
                'option_group': this.optionGroup,
                'option_key': settingKey,
                'option_value': value,
            }).then(function (response) {
                if (response.data) {
                    // saved
                }
            });
        },
        resetTemplateSettings() {
            mw.tools.confirm_reset_module_by_id(this.optionGroup, function () {
                // Reset template settings
            });
        }
    },
    mounted() {
        let appInstance = this;
        axios.get('/api/editor/template_settings_v2/list').then(function (response) {
            if (response.data) {
                appInstance.settingsGroups = response.data.settingsGroups;
                appInstance.optionGroup = response.data.optionGroup;
            }
        });
    },
    data() {
        return {
            settingsGroups: [],
            optionGroup: ''
        }
    }
}
</script>
