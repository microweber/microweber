<template>
    <div v-if="hasContainer">

        <div>
            <b class="mw-admin-action-links" v-on:click="toggleContainer">
                Container
            </b>
        </div>

        <div v-if="showContainer">

        <div class="s-field" id="field-conatiner-type">
            <label>Container type</label>
            <div class="s-field-content">

                <label class="mw-ui-check">
                    <input type="radio" name="containerType" value="container"
                           v-model="containerType"> <span></span> <span>Container</span>
                </label>
                <label class="mw-ui-check">
                    <input type="radio" name="containerType" value="container-fluid"
                           v-model="containerType"> <span></span> <span>Fluid</span>
                </label>


            </div>
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
            'showContainer': false,
            'activeContainerNode': null,
            'isReady': false,
            'hasContainer': false,
            'containerType': null,

        };
    },

    methods: {
        toggleContainer: function () {
            this.showContainer = !this.showContainer;
            this.emitter.emit('element-style-editor-show', 'container');
        },
        resetAllProperties: function () {
            this.hasContainer = null;
            this.containerType = null;

        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                this.isReady = false;
                this.resetAllProperties();
                var containerNode = mw.tools.firstParentOrCurrentWithAnyOfClasses(node, ['container', 'container-fluid']);

                if (containerNode && mw.tools.isEditable(containerNode)) {
                    if (containerNode) {
                        this.hasContainer = true;
                        this.activeContainerNode = containerNode;
                        this.populateCssContainerForNode(containerNode);
                    }
                }



                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },

        populateCssContainerForNode: function (node) {

            if (node.classList && node.classList.contains('container-fluid')) {
                this.containerType = 'container-fluid';
            } else {
                this.containerType = 'container';
            }
        },


        applyClassToActiveContainerNode: function (val) {
            if (!this.isReady) {
                return;
            }
            if (this.activeContainerNode) {



                if (val === 'container-fluid') {
                    mw.top().app.dispatch('mw.elementStyleEditor.removeClassFromNode', {
                        node: this.activeContainerNode,
                        class: 'container'
                    });
                    mw.top().app.dispatch('mw.elementStyleEditor.addClassToNode', {
                        node: this.activeContainerNode,
                        class: 'container-fluid'
                    });
                } else {
                    mw.top().app.dispatch('mw.elementStyleEditor.removeClassFromNode', {
                        node: this.activeContainerNode,
                        class: 'container-fluid'
                    });
                    mw.top().app.dispatch('mw.elementStyleEditor.addClassToNode', {
                        node: this.activeContainerNode,
                        class: 'container'
                    });
                }

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
            this.applyClassToActiveContainerNode(newValue);

        },

    },


}
</script>


