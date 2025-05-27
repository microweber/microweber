<template>
    <a href="#" @click="openStyleEditor" 
       class="mw-admin-action-links mw-adm-liveedit-tabs settings-main-group">
        {{ setting.title }}
    </a>
</template>

<script>
export default {
    props: {
        setting: {
            type: Object,
            required: true
        }
    },
    methods: {
        openStyleEditor() {
            this.$emit('open-style-editor', this.setting);
        }
    }
};
</script>
