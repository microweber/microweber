<template>
    <div id="live-edit-sidebar-admin-panel-wrapper" :class="[showSidebarAdminPanel == true ? 'active' : '']">

        <div v-if="showSidebarAdminPanel">

            <iframe :src="buildIframeUrl()" id="live-edit-sidebar-admin-panel-iframe"
                    frameborder="0"
                    allowfullscreen></iframe>

        </div>
    </div>

</template>


<script>

export default {
    components: {},
    methods: {

        buildIframeUrl: function (url) {

            var moduleType = 'editor/sidebar_quick_admin_panel';
            var attrsForSettings = {};

            attrsForSettings.live_edit = true;
            attrsForSettings.module_settings = true;
            attrsForSettings.id = 'mw_global_add_content_modal';
            attrsForSettings.type = moduleType;
            attrsForSettings.iframe = true;
            attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;


            var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);

            return src;

        },

        showAdminSidebar: function () {
            this.showSidebarAdminPanel = true;
        },

        closeAdminSidebar: function () {
            this.showSidebarAdminPanel = false;
        },
    },
    mounted() {
        this.emitter.on("live-edit-ui-show", show => {

            if (show == 'sidebar-admin-panel-toggle') {
                this.showAdminSidebar();
            } else if (show == 'sidebar-admin-panel') {
                this.showAdminSidebar();
            }  else {
                this.closeAdminSidebar();
            }
        });


        mw.app.canvas.on('liveEditCanvasBeforeUnload', () => {
            this.closeAdminSidebar();
        });

    },
    data() {
        return {
            showSidebarAdminPanel: false,
            iframeUrl: false
        }
    }
}
</script>


<style scoped>
#live-edit-sidebar-admin-panel-wrapper {
    position: fixed;
    top: var(--toolbar-height);
    bottom: 0;
    left: -300px;
    width: 300px;
    transition: var(--toolbar-height-animation-speed);
    overflow: auto;
    /*background-color: var(--tblr-body-bg);*/
    /*border-left: var(--tblr-body-color);*/

    z-index: 100;
}


#live-edit-sidebar-admin-panel-wrapper.active {
    left: 0
}


</style>
