<template>
    <div class="mb-4" v-if="activeLayoutNode">


        <div class="row" role="alert">

                <div>
                    <input type="button" class="btn btn-dark live-edit-toolbar-buttons btn-sm btn-block w-100"
                           value="Edit layout" @click="editLayout">
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
