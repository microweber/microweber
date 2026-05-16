<script>
import CSSGUIService from "../../../api-core/services/services/css-gui.service.js";
export default {

    mounted() {
        mw.top().app.canvas.getFrame().addEventListener('transitionend', e => {
            var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();
            var activeModule = mw.top().app.liveEdit.handles.get('module').getTarget();
            if(activeElement) {
                mw.top().app.dispatch('mw.elementStyleEditor.refreshNode', activeElement);
            }
        })
    },

    data() {
        return {
            previewMode: 'desktop'
        }
    },

    methods: {
        setPreviewMode(mode) {

            this.previewMode = mode;
            this.emulatorSet(mode);


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
                var _frame = mw.app.canvas.getFrame();
                _frame.classList.add('resizing');
                _frame.style.width = width;
                setTimeout(() => _frame.classList.remove('resizing'), 400)
                mw.app.liveEdit.handles.hide();
                mw.top().app.resolutionMode = key;

            };
            responsiveEmulatorSet(mode);




        }


    }
}
</script>

<template>

    <!--
      task-2026-05-16-5fe1f9 / AI-698b item 1 — device-preview switch
      now uses the MwSegmented primitive from slice 1.3a (AI-684 /
      task-f69d54). Three modes per spec §4.3 (Desktop / Tablet /
      Mobile) — the legacy 2-mode markup carried only Desktop +
      Phone; Tablet (800px breakpoint, already wired in emulatorSet
      above) is now exposed in the UI. Legacy classes (.toolbar-nav,
      .mw-live-edit-resolutions-wrapper, .btn-icon, .live-edit-
      toolbar-buttons, .live-edit-resolution-active) kept alongside
      the new .mw-segmented* classes — external scripts and CSS
      target the old hooks. .is-active mirrors the legacy .live-edit-
      resolution-active so the primitive's active-state styling
      applies without dropping back-compat. ARIA toggle-button APG
      contract (aria-pressed + tabindex=0 + keydown) preserved per
      task-2026-05-04-a11y.
    -->
    <nav id="preview-nav" aria-label="Device preview" class="toolbar-nav mw-live-edit-resolutions-wrapper toolbar-nav-hover me-2 mw-segmented">
       <span class="btn-icon live-edit-toolbar-buttons mw-segmented__cell" role="button" tabindex="0" aria-label="Desktop view" :aria-pressed="previewMode=='desktop'" v-on:click="setPreviewMode('desktop')" v-on:keydown.enter.prevent="setPreviewMode('desktop')" v-on:keydown.space.prevent="setPreviewMode('desktop')" data-preview="desktop"
             :class="[previewMode=='desktop' ? 'live-edit-resolution-active is-active': '']">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 96 960 960" width="22" aria-hidden="true"><path d="M320 936v-80h80v-80H160q-33 0-56.5-23.5T80 696V296q0-33 23.5-56.5T160 216h640q33 0 56.5 23.5T880 296v400q0 33-23.5 56.5T800 776H560v80h80v80H320ZM160 696h640V296H160v400Zm0 0V296v400Z"/></svg>
        </span>

        <span class="btn-icon live-edit-toolbar-buttons mw-segmented__cell" role="button" tabindex="0" aria-label="Tablet view" :aria-pressed="previewMode=='tablet'" v-on:click="setPreviewMode('tablet')" v-on:keydown.enter.prevent="setPreviewMode('tablet')" v-on:keydown.space.prevent="setPreviewMode('tablet')" data-preview="tablet"
              :class="[previewMode=='tablet' ? 'live-edit-resolution-active is-active': '']">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><path d="M19 18H5V6h14m0-4H5a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z"/></svg>
        </span>

        <span class="btn-icon live-edit-toolbar-buttons mw-segmented__cell" role="button" tabindex="0" aria-label="Mobile view" :aria-pressed="previewMode=='phone'" v-on:click="setPreviewMode('phone')" v-on:keydown.enter.prevent="setPreviewMode('phone')" v-on:keydown.space.prevent="setPreviewMode('phone')" data-preview="phone"
              :class="[previewMode=='phone' ? 'live-edit-resolution-active is-active': '']">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" aria-hidden="true"><g fill="currentColor"><path d="M6 5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5zm10 0H8v14h8V5z"/><path d="M13 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0z"/></g></svg>
        </span>

    </nav>
</template>
