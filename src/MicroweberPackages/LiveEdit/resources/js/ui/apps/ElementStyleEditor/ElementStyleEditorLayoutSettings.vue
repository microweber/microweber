<template>
    <div class="mb-4" v-if="activeLayoutNode">


        <div class="alert alert-info" role="alert">
            <div class="d-flex">
                <div>
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon alert-icon" width="24" height="24"
                         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                         stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                        <path d="M12 9h.01"></path>
                        <path d="M11 12h1v4h1"></path>
                    </svg>
                </div>
                <div>
                    You have selected a layout. <br>
                    <input type="button" class="btn btn-dark live-edit-toolbar-buttons btn-sm btn-block w-100"
                           value="Edit layout" @click="editLayout">
                </div>
            </div>
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
            if (node && node.nodeType === 1) {

                this.isReady = false;

                this.activeLayoutNode = node;


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },


    },
};
</script>
