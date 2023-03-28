<template>
    <div class="p-3">
        <div v-if="settings.length === 0" class="text-center">
            Loading...
        </div>
        <div v-else v-for="(setting,settingKey) in settings">

            <div v-if="setting.type === 'title'" class="mt-3">
                <div class="text-uppercase">
                    <b>{{ setting.label }}</b>
                </div>
            </div>

            <div v-if="setting.type === 'dropdown_image'" class="mt-3">
                <div>{{ setting.label }}</div>
                <select class="form-control" v-on:change="updateSettings($event, settingKey)" :name="settingKey">
                    <option v-for="(optionValue,optionKey) in setting.options" :value="optionKey">
                        {{ optionValue }}
                    </option>
                </select>
            </div>

            <div v-if="setting.type === 'dropdown'" class="mt-3">
                <div>{{ setting.label }}</div>
                <select class="form-control" v-on:change="updateSettings($event, settingKey)" :name="settingKey">
                    <option v-for="(optionValue,optionKey) in setting.options" :value="optionKey">
                        {{ optionValue }}
                    </option>
                </select>
            </div>

        </div>

        <div class="mt-2">
            <button v-on:click="resetTemplateSettings"
                    class="btn btn-primary btn-block">Reset Template Settings
            </button>
        </div>
    </div>
</template>

<style>
.greeting {
    color: red;
}
</style>
<script>
import axios from 'axios';

export default {
    methods: {
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
            mw.tools.confirm_reset_module_by_id(this.optionGroup, function (){
                // Reset template settings
            });
        }
    },
    mounted() {
        let appInstance = this;
        axios.get('/api/editor/template_settings_v2/list').then(function (response) {
            if (response.data) {
                appInstance.settings = response.data.settings;
                appInstance.optionGroup = response.data.optionGroup;
            }
        });
    },
    data() {
        return {
            settings: [],
            optionGroup: ''
        }
    }
}
</script>
