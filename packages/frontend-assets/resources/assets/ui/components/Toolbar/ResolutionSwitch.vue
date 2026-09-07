<script>
import CSSGUIService from "../../../api-core/services/services/css-gui.service.js";

// task-2026-09-07-devicedropdown — the three device-preview icons
// (Desktop / Tablet / Mobile) are collapsed behind ONE trigger icon that
// opens a small dropdown. The dropdown still holds the original
// role="radiogroup" + 3 role="radio" segmented cells VERBATIM, so these
// prior contracts stay satisfied and their markers are preserved:
//   • task-2026-05-17-439f34 / AI-811 — native <button role="radio"> cells
//     inside role="radiogroup" (no legacy <span> shims, no tabindex).
//   • task-2026-05-16-5fe1f9 / AI-698b — MwSegmented primitive (.mw-segmented
//     wrapper + exactly 3 .mw-segmented__cell modes).
// Only the chrome changes from "3 always-visible cells" to "1 icon + popover".
// The trigger shows the icon of the active device.
const DEVICE_ICONS = {
    desktop: '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 96 960 960" width="22" aria-hidden="true"><path d="M320 936v-80h80v-80H160q-33 0-56.5-23.5T80 696V296q0-33 23.5-56.5T160 216h640q33 0 56.5 23.5T880 296v400q0 33-23.5 56.5T800 776H560v80h80v80H320ZM160 696h640V296H160v400Zm0 0V296v400Z"/></svg>',
    tablet: '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 18H5V6h14m0-4H5a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>',
    phone: '<svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><g fill="currentColor"><path d="M6 5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5zm10 0H8v14h8V5z"/><path d="M13 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0z"/></g></svg>',
};
const DEVICE_LABELS = { desktop: 'Desktop', tablet: 'Tablet', phone: 'Mobile' };

export default {

    mounted() {
        mw.top().app.canvas.getFrame().addEventListener('transitionend', e => {
            var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();
            var activeModule = mw.top().app.liveEdit.handles.get('module').getTarget();
            if(activeElement) {
                mw.top().app.dispatch('mw.elementStyleEditor.refreshNode', activeElement);
            }
        })

        this._onDocClick = (e) => {
            if (!this.open) return;
            if (this.$el && !this.$el.contains(e.target)) this.open = false;
        };
        document.addEventListener('click', this._onDocClick, true);
    },

    beforeUnmount() {
        try { document.removeEventListener('click', this._onDocClick, true); } catch (e) {}
    },

    data() {
        return {
            previewMode: 'desktop',
            open: false,
        }
    },

    computed: {
        currentIcon() { return DEVICE_ICONS[this.previewMode] || DEVICE_ICONS.desktop; },
        currentLabel() { return DEVICE_LABELS[this.previewMode] || 'Desktop'; },
    },

    methods: {
        toggleOpen() { this.open = !this.open; },
        setPreviewMode(mode) {

            this.previewMode = mode;
            this.emulatorSet(mode);
            this.open = false;


        },
        emulatorSet(mode) {

            var _reTypes = {
                tablet: 800,
                phone: 400,
                desktop: '100%',
            }
            var responsiveEmulatorSet = function (key) {
                var width = _reTypes[key];
                if (typeof width === 'number') {
                    width = width + 'px'
                }
                // task-2026-09-07-devicefix — set the frame width FIRST and guard
                // each side-effect so a throw in handles.hide() can never abort
                // the actual resize (the reported "icons don't switch" symptom).
                var _frame = mw.app.canvas.getFrame();
                if (_frame) {
                    _frame.classList.add('resizing');
                    _frame.style.setProperty('width', width, 'important');
                    setTimeout(() => _frame.classList.remove('resizing'), 400);
                }
                try { mw.top().app.resolutionMode = key; } catch (e) {}
                try { mw.app.liveEdit.handles.hide(); } catch (e) {}
            };
            responsiveEmulatorSet(mode);




        }


    }
}
</script>

<template>

    <div class="mw-device-switch" :class="{ 'is-open': open }">
        <!-- Single trigger showing the active device icon; opens the dropdown. -->
        <button type="button"
                class="btn-icon live-edit-toolbar-buttons mw-device-switch__trigger"
                aria-haspopup="true"
                :aria-expanded="open ? 'true' : 'false'"
                :aria-label="'Device preview: ' + currentLabel"
                :title="'Device preview: ' + currentLabel"
                v-on:click="toggleOpen">
            <i class="mw-device-switch__icon" v-html="currentIcon" aria-hidden="true"></i>
            <svg class="mw-device-switch__caret" width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M7 10l5 5 5-5z"/></svg>
        </button>

        <div class="mw-device-switch__panel" v-show="open">
            <!--
              AI-811 / AI-698b — role="radiogroup" + three role="radio"
              segmented cells kept VERBATIM (a11y + MwSegmented contracts).
              The dropdown only wraps them behind the single trigger above.
            -->
            <nav id="preview-nav" role="radiogroup" aria-label="Device preview" class="toolbar-nav mw-live-edit-resolutions-wrapper toolbar-nav-hover me-2 mw-segmented">
               <button type="button" class="btn-icon live-edit-toolbar-buttons mw-segmented__cell" role="radio" aria-label="Desktop view" :aria-checked="previewMode=='desktop' ? 'true' : 'false'" v-on:click="setPreviewMode('desktop')" data-preview="desktop"
                     :class="[previewMode=='desktop' ? 'live-edit-resolution-active is-active': '']">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 96 960 960" width="22" aria-hidden="true"><path d="M320 936v-80h80v-80H160q-33 0-56.5-23.5T80 696V296q0-33 23.5-56.5T160 216h640q33 0 56.5 23.5T880 296v400q0 33-23.5 56.5T800 776H560v80h80v80H320ZM160 696h640V296H160v400Zm0 0V296v400Z"/></svg>
                </button>

                <button type="button" class="btn-icon live-edit-toolbar-buttons mw-segmented__cell" role="radio" aria-label="Tablet view" :aria-checked="previewMode=='tablet' ? 'true' : 'false'" v-on:click="setPreviewMode('tablet')" data-preview="tablet"
                      :class="[previewMode=='tablet' ? 'live-edit-resolution-active is-active': '']">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 18H5V6h14m0-4H5a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>
                </button>

                <button type="button" class="btn-icon live-edit-toolbar-buttons mw-segmented__cell" role="radio" aria-label="Mobile view" :aria-checked="previewMode=='phone' ? 'true' : 'false'" v-on:click="setPreviewMode('phone')" data-preview="phone"
                      :class="[previewMode=='phone' ? 'live-edit-resolution-active is-active': '']">
                    <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><g fill="currentColor"><path d="M6 5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5zm10 0H8v14h8V5z"/><path d="M13 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0z"/></g></svg>
                </button>

            </nav>
        </div>
    </div>
</template>

<style scoped>
.mw-device-switch {
    position: relative;
    display: inline-flex;
    align-items: center;
}
.mw-device-switch__trigger {
    display: inline-flex;
    align-items: center;
    gap: 2px;
    padding: 0 4px;
}
.mw-device-switch__icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-style: normal;
}
.mw-device-switch__caret {
    opacity: .55;
    transition: transform .15s ease;
}
.mw-device-switch.is-open .mw-device-switch__caret {
    transform: rotate(180deg);
}
.mw-device-switch__panel {
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 6px;
    z-index: 1200;
    padding: 4px;
    border-radius: 10px;
    background: #ffffff;
    box-shadow: 0 4px 16px rgba(24, 36, 51, .14), 0 1px 3px rgba(24, 36, 51, .1);
}
/* :global on the ancestor — a scoped selector appends [data-v] to html.dark
   too, which never matches (vue3-scoped-dark-mode skill). */
:global(html.dark) .mw-device-switch__panel {
    background: #1b1e22;
    box-shadow: 0 8px 24px rgba(0, 0, 0, .5);
}
/* The nav inside the panel drops its right-margin — it's no longer inline. */
.mw-device-switch__panel .mw-live-edit-resolutions-wrapper {
    margin-right: 0 !important;
}
</style>
