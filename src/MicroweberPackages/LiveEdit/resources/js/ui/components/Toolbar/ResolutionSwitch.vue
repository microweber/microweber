<script>
export default {

    mounted() {
        mw.top().app.canvas.getFrame().addEventListener('transitionend', e => {
            var activeElement = mw.top().app.liveEdit.handles.get('element').getTarget();
            var activeModule = mw.top().app.liveEdit.handles.get('module').getTarget();
            if(activeElement) {
                mw.top().app.dispatch('mw.elementStyleEditor.refreshNode', activeElement);
            } else if(activeModule) {
                mw.top().app.dispatch('mw.elementStyleEditor.refreshNode', activeModule);
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
                mw.app.canvas.getFrame().style.width = width;
                mw.app.liveEdit.handles.hide();
                mw.top().app.resolutionMode = key;

            };
            responsiveEmulatorSet(mode);




        }


    }
}
</script>

<template>


    <nav id="preview-nav" class="toolbar-nav mw-live-edit-resolutions-wrapper toolbar-nav-hover">
       <span class="btn-icon live-edit-toolbar-buttons me-1" v-on:click="setPreviewMode('desktop')" data-preview="desktop"
             :class="[previewMode=='desktop' ? 'live-edit-resolution-active': '']">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="22" viewBox="0 96 960 960" width="22"><path d="M320 936v-80h80v-80H160q-33 0-56.5-23.5T80 696V296q0-33 23.5-56.5T160 216h640q33 0 56.5 23.5T880 296v400q0 33-23.5 56.5T800 776H560v80h80v80H320ZM160 696h640V296H160v400Zm0 0V296v400Z"/></svg>
        </span>

        <span class="btn-icon live-edit-toolbar-buttons" v-on:click="setPreviewMode('phone')" data-preview="phone"
              :class="[previewMode=='phone' ? 'live-edit-resolution-active': '']">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24"><g fill="currentColor"><path d="M6 5a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2V5zm10 0H8v14h8V5z"/><path d="M13 17a1 1 0 1 1-2 0a1 1 0 0 1 2 0z"/></g></svg>
        </span>

    </nav>
</template>
