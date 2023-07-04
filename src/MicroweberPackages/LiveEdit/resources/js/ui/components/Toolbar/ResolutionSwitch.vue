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

        <span class="btn live-edit-toolbar-buttons" v-on:click="setPreviewMode('desktop')" data-preview="desktop"
              :class="[previewMode=='desktop' ? 'btn btn-icon': '']">
            <svg width="20px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 48 36.17" style="enable-background:new 0 0 48 36.17;" xml:space="preserve">


                <path d="M25.59,34.11h-3.58v-6.59h3.58V34.11z M14.16,34.88L14.16,34.88c0-0.71,0.58-1.29,1.29-1.29h17.1
                    c0.71,0,1.29,0.58,1.29,1.29v0c0,0.71-0.58,1.29-1.29,1.29h-17.1C14.74,36.17,14.16,35.59,14.16,34.88z"/>


                <path class="st0" d="M3.32,27.6h41.35c1.53,0,2.76-1.24,2.76-2.76V3.24c0-1.53-1.24-2.76-2.76-2.76H3.32
                    c-1.53,0-2.76,1.24-2.76,2.76v21.6C0.56,26.37,1.8,27.6,3.32,27.6z"/>

            </svg>
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

        <span class="btn live-edit-toolbar-buttons" v-on:click="setPreviewMode('phone')" data-preview="phone"
              :class="[previewMode=='phone' ? 'btn btn-icon': '']">
            <svg width="20px" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve">

            <path class="st0" d="M18,11.5c0.43,0,0.79-0.14,1.08-0.43c0.28-0.28,0.42-0.64,0.42-1.07s-0.14-0.79-0.42-1.07
                C18.79,8.64,18.43,8.5,18,8.5s-0.79,0.14-1.08,0.43C16.64,9.21,16.5,9.57,16.5,10s0.14,0.79,0.42,1.07
                C17.21,11.36,17.57,11.5,18,11.5z M13,46c-0.8,0-1.5-0.3-2.1-0.9C10.3,44.5,10,43.8,10,43V5c0-0.8,0.3-1.5,0.9-2.1
                C11.5,2.3,12.2,2,13,2h22c0.8,0,1.5,0.3,2.1,0.9C37.7,3.5,38,4.2,38,5v38c0,0.8-0.3,1.5-0.9,2.1C36.5,45.7,35.8,46,35,46H13z"/>
            </svg>
        </span>
    </nav>
</template>
