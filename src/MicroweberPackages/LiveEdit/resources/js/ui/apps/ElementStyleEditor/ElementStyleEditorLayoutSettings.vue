<template>
    <div v-if="activeLayoutNode">


        <div class="mb-4 d-flex">
            <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
                 xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
                 style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path d="M12.2,3.9c4.5,0,8.1,3.6,8.1,8.1s-3.6,8.1-8.1,8.1S4.1,16.5,4.1,12S7.7,3.9,12.2,3.9"></path>
        </svg>
            <b class="mw-admin-action-links ms-3" v-on:click="editLayout">
                Section settings
            </b>
        </div>


    </div>
</template>


<script>

export default {


    data() {
        return {
            activeLayoutNode: null,
            isReady: false,

        };
    },

    mounted() {


    },

    watch: {
        '$root.selectedElement': {
            handler: function (element) {
                if (element) {

                    this.populateLayoutSettings(element);
                }
            },
            deep: true
        },
        '$root.selectedLayout': {
            handler: function (element) {
                if (element) {
                    this.populateLayoutSettings(element);
                }
            },
            deep: true
        },


    },

    methods: {


        editLayout: function () {
            mw.top().app.editor.dispatch('onLayoutSettingsRequest', this.activeLayoutNode);
        },

        populateLayoutSettings: function (node) {

            this.activeLayoutNode = null;

            var layoutNode = mw.tools.firstParentOrCurrentWithAnyOfClasses(node, ['module-layouts']);

            if (node && node.nodeType === 1) {
                
                this.isReady = false;

                if (layoutNode) {

                    this.activeLayoutNode = layoutNode;

                    setTimeout(() => {
                        this.isReady = true;
                    }, 100);

                }
            }
        },


    },
};
</script>
