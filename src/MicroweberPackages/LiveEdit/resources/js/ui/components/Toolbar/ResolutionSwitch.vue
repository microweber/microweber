<script>
export default {

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
                mw.app.liveEdit.handles.hide()

            };
            responsiveEmulatorSet(mode)

        }


    }
}
</script>

<template>


    <nav id="preview-nav" class="toolbar-nav toolbar-nav-hover">

        <span class="btn btn-icon live-edit-toolbar-buttons" v-on:click="setPreviewMode('desktop')" data-preview="desktop"
              :class="[previewMode=='desktop' ? 'btn btn-icon': '']">
            <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M320 936v-80h80v-80H160q-33 0-56.5-23.5T80 696V296q0-33 23.5-56.5T160 216h640q33 0 56.5 23.5T880 296v400q0 33-23.5 56.5T800 776H560v80h80v80H320ZM160 696h640V296H160v400Zm0 0V296v400Z"/></svg>
        </span>

    <!--            <span class="btn" v-on:click="setPreviewMode('tablet')" data-preview="tablet"-->
    <!--                  :class="[previewMode=='tablet' ? 'btn-primary': '']">-->
    <!--                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"-->
    <!--                     viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve">-->

    <!--                <path class="st0" d="M24.45,39.5c0.56,0,1.03-0.18,1.39-0.55c0.37-0.37,0.55-0.83,0.55-1.39s-0.18-1.03-0.55-1.39-->
    <!--                    c-0.37-0.37-0.83-0.55-1.39-0.55s-1.03,0.18-1.39,0.55c-0.37,0.37-0.55,0.83-0.55,1.39s0.18,1.03,0.55,1.39-->
    <!--                    C23.42,39.32,23.88,39.5,24.45,39.5z M7,46c-0.8,0-1.5-0.3-2.1-0.9C4.3,44.5,4,43.8,4,43V5c0-0.8,0.3-1.5,0.9-2.1C5.5,2.3,6.2,2,7,2-->
    <!--                    h34c0.8,0,1.5,0.3,2.1,0.9C43.7,3.5,44,4.2,44,5v38c0,0.8-0.3,1.5-0.9,2.1C42.5,45.7,41.8,46,41,46H7z"/>-->
    <!--                </svg>-->

    <!--            </span>-->

        <span class="btn btn-icon live-edit-toolbar-buttons" v-on:click="setPreviewMode('phone')" data-preview="phone"
              :class="[previewMode=='phone' ? 'btn btn-icon': '']">
              <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 96 960 960" width="24"><path d="M320 936v-80h80v-80H160q-33 0-56.5-23.5T80 696V296q0-33 23.5-56.5T160 216h640q33 0 56.5 23.5T880 296v400q0 33-23.5 56.5T800 776H560v80h80v80H320ZM160 696h640V296H160v400Zm0 0V296v400Z"/></svg>

        </span>
    </nav>
</template>
