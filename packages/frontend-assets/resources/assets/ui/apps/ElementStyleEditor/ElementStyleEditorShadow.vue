<template>
    <div class="d-flex">
        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
             style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path d="M12.2,3.9c4.5,0,8.1,3.6,8.1,8.1s-3.6,8.1-8.1,8.1S4.1,16.5,4.1,12S7.7,3.9,12.2,3.9"></path>
        </svg>
        <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showShadow }">
            Shadow
        </span>
    </div>

    <!-- task-2026-05-16-ea56d3: @click.stop — see ElementStyleEditorTypography.vue -->
    <div v-if="showShadow" @click.stop>
        <!-- Tab Navigation -->
        <div class="mw-admin-action-links-holder mb-3">
            <span
                class="mw-admin-action-links mw-adm-liveedit-tabs"
                :class="{'active': activeTab === 'box'}"
                @click="setActiveTab('box')">
                Box Shadow
            </span>
            <span
                class="mw-admin-action-links mw-adm-liveedit-tabs ms-2"
                :class="{'active': activeTab === 'text'}"
                @click="setActiveTab('text')">
                Text Shadow
            </span>
        </div>

        <!-- Tab Content -->
        <div>
            <ElementStyleEditorBoxShadow v-show="activeTab === 'box'"></ElementStyleEditorBoxShadow>
            <ElementStyleEditorTextShadow v-show="activeTab === 'text'"></ElementStyleEditorTextShadow>
        </div>
    </div>
</template>

<script>
import ElementStyleEditorBoxShadow from './ElementStyleEditorBoxShadow.vue';
import ElementStyleEditorTextShadow from './ElementStyleEditorTextShadow.vue';

export default {
    components: {
        ElementStyleEditorBoxShadow,
        ElementStyleEditorTextShadow
    },
    data() {
        return {
            'showShadow': false,
            'activeTab': 'box',
        };
    },
    mounted() {
        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow === 'showShadow') {
                this.showShadow = true;
            } else {
                this.showShadow = false;
            }
        });
    },
    methods: {
        toggleShadow: function () {
            this.showShadow = !this.showShadow;



        },
        setActiveTab(tab) {

            this.emitter.emit('element-style-editor-show', 'showShadow');

            this.activeTab = tab;
        }
    }
}
</script>
