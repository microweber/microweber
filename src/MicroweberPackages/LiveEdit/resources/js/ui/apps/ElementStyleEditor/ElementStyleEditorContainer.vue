<template>
    <div v-if="hasContainer">


        <div class="s-field" id="field-conatiner-type">
            <label>Container type</label>
            <div class="s-field-content">

                <label class="mw-ui-check">
                    <input type="radio" name="containerType" value="container"
                           v-model="containerType"> <span></span> <span>Container</span>
                </label>
                <label class="mw-ui-check">
                    <input type="radio" name="containerType" value="container-fluid"
                           v-model="containerType"> <span></span> <span>Container Fluid</span>
                </label>


            </div>
        </div>


    </div>

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
            'containerType': null,

        };
    },

    methods: {
        resetAllProperties: function () {
            this.hasContainer = null;
            this.containerType = null;

        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                this.isReady = false;
                this.resetAllProperties();
                var containerNode = mw.tools.firstParentOrCurrentWithAnyOfClasses(node, ['container', 'container-fluid']);

                if (containerNode) {
                    this.hasContainer = true;
                    this.activeContainerNode = containerNode;
                    this.populateCssContainerForNode(containerNode);
                }

                this.isReady = true;
            }
        },

        populateCssContainerForNode: function (node) {

            if (node.classList && node.classList.contains('container-fluid')) {
                this.containerType = 'container-fluid';
            } else {
                this.containerType = 'container';
            }
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
        containerType: function (newValue, oldValue) {
         //   this.applyPropertyToActiveContainerNode('fontFamily', newValue);
        },

    },


}
</script>


