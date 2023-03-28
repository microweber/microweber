
<template>
    <div>
        <div v-for="setting in settings" class="p-3">

            <div v-if="setting.type === 'title'" class="mt-3">
                <div>{{ setting.label }}</div>
            </div>

            <div v-if="setting.type === 'dropdown_image'" class="mt-3">
                <div>{{ setting.label }}</div>
                <select class="form-control">
                    <option v-for="(optionValue,optionKey) in setting.options" :value="optionKey">
                        {{ optionValue }}
                    </option>
                </select>
            </div>

            <div v-if="setting.type === 'dropdown'" class="mt-3">
                <div>{{ setting.label }}</div>
                <select class="form-control">
                    <option v-for="(optionValue,optionKey) in setting.options" :value="optionKey">
                        {{ optionValue }}
                    </option>
                </select>
            </div>

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

    },
    mounted() {
        let appInstance = this;
        axios.get('/api/editor/template_settings_v2/list').then(function (response) {
            if (response.data) {
                appInstance.settings = response.data;
            }
        });
    },
    data() {
        return {
            settings: []
        }
    }
}
</script>
