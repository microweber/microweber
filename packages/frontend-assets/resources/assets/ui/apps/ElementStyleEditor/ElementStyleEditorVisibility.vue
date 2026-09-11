<template>

    <div class="d-flex">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
            <circle cx="12" cy="12" r="3"/>
        </svg>

        <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showVisibility }">
            Visibility
        </span>
    </div>

    <!-- @click.stop keeps toggle clicks from bubbling to the wrapper's
         @click="toggleVisibility" (same pattern as the other sections). -->
    <div v-if="showVisibility" @click.stop>
        <div class="mw-ese-visibility">
            <div class="mw-ese-visibility__row" v-for="d in devices" :key="d.key">
                <label class="live-edit-label">{{ d.label }}</label>
                <button
                    type="button"
                    class="mw-tool-btn mw-tool-btn--toggle mw-ese-visibility__toggle"
                    :class="{ 'is-active': visible[d.key] }"
                    :aria-pressed="visible[d.key]"
                    :aria-label="(visible[d.key] ? 'Visible on ' : 'Hidden on ') + d.label"
                    :title="(visible[d.key] ? 'Visible on ' : 'Hidden on ') + d.label"
                    @click="toggleDevice(d.key)">
                    <svg v-if="visible[d.key]" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7z"/>
                        <circle cx="12" cy="12" r="3"/>
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-10-7-10-7a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 10 7 10 7a18.5 18.5 0 0 1-2.16 3.19"/>
                        <path d="M1 1l22 22"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

</template>


<script>
export default {
    data() {
        return {
            showVisibility: false,
            activeNode: null,
            // Per-device hide classes (defined in default.css so they apply on
            // the live site too). "visible" = class NOT present.
            devices: [
                { key: 'desktop', label: 'Desktop', cls: 'mw-visibility-hide-desktop' },
                { key: 'tablet', label: 'Tablet', cls: 'mw-visibility-hide-tablet' },
                { key: 'mobile', label: 'Mobile', cls: 'mw-visibility-hide-mobile' },
            ],
            visible: { desktop: true, tablet: true, mobile: true },
        };
    },

    methods: {
        toggleVisibility() {
            this.showVisibility = !this.showVisibility;
            this.emitter.emit('element-style-editor-show', 'visibility');
        },

        populate(node) {
            if (!node || node.nodeType !== 1) return;
            this.activeNode = node;
            this.devices.forEach((d) => {
                this.visible[d.key] = !node.classList.contains(d.cls);
            });
        },

        toggleDevice(key) {
            const d = this.devices.find((x) => x.key === key);
            if (!d || !this.activeNode) return;
            const nowVisible = !this.visible[key];
            this.visible[key] = nowVisible;
            if (nowVisible) {
                this.activeNode.classList.remove(d.cls);
            } else {
                this.activeNode.classList.add(d.cls);
            }
            try {
                mw.top().app.registerChange(this.activeNode);
            } catch (e) { /* live-edit not ready */ }
        },
    },

    mounted() {
        // Close this section when another one opens (same contract as siblings).
        this.emitter.on('element-style-editor-show', (which) => {
            if (which !== 'visibility') {
                this.showVisibility = false;
            }
        });
        if (this.$root.selectedElement) {
            this.populate(this.$root.selectedElement);
        }
    },

    watch: {
        '$root.selectedElement': {
            handler(el) {
                if (el) this.populate(el);
            },
            deep: true,
        },
    },
};
</script>
