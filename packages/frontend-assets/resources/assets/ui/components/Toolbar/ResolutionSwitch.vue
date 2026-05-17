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
      task-2026-05-17-439f34 / AI-811 — semantic upgrade of device-
      preview cells from `<span role="button" tabindex="0">` to
      native `<button type="button" role="radio">`. Pre-fix three
      a11y defects from one element choice:
        (1) span requires manual tabindex + manual Enter/Space
            keydown handlers (fragile vs. native <button> defaults).
        (2) role="button" announces as generic button; role="radio"
            inside a role="radiogroup" parent communicates the
            mutually-exclusive choice semantic to AT.
        (3) browsers paint default focus rings on <button> but may
            suppress them on <span tabindex="0"> in some UA defaults.
      Designer spec (email 2026-05-17T10:21:03Z) called for
      role="radiogroup" + role="radio" + aria-checked.

      Native <button> brings: Tab-focusable by default; Enter +
      Space activate via the browser; browser-native focus ring
      (CSS focus-visible rule already defined at
      .mw-segmented__cell:focus-visible). Dropping the manual
      tabindex / keydown handlers reduces surface area + eliminates
      the WCAG 2.1.1 fragility.

      Back-compat preservation (per task-5fe1f9 / AI-698b lineage):
      .live-edit-resolution-active + .is-active class chain kept
      verbatim on the active cell so external CSS + scripts that
      target the legacy hooks still work. Replaced :aria-pressed
      with :aria-checked per radio APG semantics — the CSS rule
      .mw-segmented__cell[aria-pressed="true"] picks up
      [aria-checked="true"] in the AI-811 paired CSS update.

      type="button" on every cell — defence-in-depth against the
      buttons ever ending up inside a <form> ancestor (in which
      case the default type="submit" would trigger an accidental
      form submission on click).
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
</template>
