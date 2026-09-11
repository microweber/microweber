<template>
    <div class="d-flex">
        <!-- task-2026-05-22-cd4d21 / AI-915 — replace meaningless filled-circle
             with sparkles icon (heroicon-o-sparkles equivalent, stroke-based). -->
        <svg fill="none" height="24" width="24" viewBox="0 0 24 24" stroke="currentColor"
             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
             xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z"/>
        </svg>
        <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showShadow }">
            Shadow
        </span>
    </div>

    <!-- task-2026-05-16-ea56d3: @click.stop — see ElementStyleEditorTypography.vue -->
    <!-- LE redesign (frame 1c) — the Box/Text choice is now a soft-pill segmented
         "Shadow type" (replacing the two text links). -->
    <div v-if="showShadow" @click.stop>
        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Shadow type</label>
            <div class="mw-segmented mw-ese-seg">
                <span class="mw-segmented__cell"
                      :class="{ 'active': activeTab === 'box', 'is-active': activeTab === 'box' }"
                      role="button" tabindex="0"
                      :aria-pressed="activeTab === 'box' ? 'true' : 'false'"
                      @click="setActiveTab('box')"
                      @keydown.enter.prevent="setActiveTab('box')"
                      @keydown.space.prevent="setActiveTab('box')">Box</span>
                <span class="mw-segmented__cell"
                      :class="{ 'active': activeTab === 'text', 'is-active': activeTab === 'text' }"
                      role="button" tabindex="0"
                      :aria-pressed="activeTab === 'text' ? 'true' : 'false'"
                      @click="setActiveTab('text')"
                      @keydown.enter.prevent="setActiveTab('text')"
                      @keydown.space.prevent="setActiveTab('text')">Text</span>
            </div>
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
