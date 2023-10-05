<template>
container
</template>


<script>

import DropdownSmall from './components/DropdownSmall.vue';


export default {
    components: {DropdownSmall},
    data() {
        return {
            'activeContainerNode': null,
            'isReady': false,
            'hasContainer': false,

        };
    },

    methods: {
        resetAllProperties: function () {
            this.hasContainer = null;

        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {

                var containerNode = mw.tools.firstParentOrCurrentWithAnyOfClasses(node, ['container', 'container-fluid']);

console.log(containerNode);

                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeContainerNode = node;


                this.populateCssContainerForNode(node);


                this.isReady = true;
            }
        },

        populateCssContainerForNode: function (node) {

        },




        applyPropertyToActiveContainerNode: function (prop, val) {
            if (!this.isReady) {
                return;
            }
            if (this.activeContainerNode) {
                mw.top().app.dispatch('mw.elementStyleEditor.applyCssPropertyToNode', {
                    node: this.activeContainerNode,
                    prop: prop,
                    val: val
                });
            }
        },

    },

    mounted() {
        mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
            this.populateStyleEditor(element)
        });
    },

    watch: {
        fontFamily: function (newValue, oldValue) {
            this.applyPropertyToActiveContainerNode('fontFamily', newValue);
        },

    },


}
</script>


