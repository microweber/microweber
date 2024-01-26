<template>
    <div v-if="hasContainer">

        <div class="mb-4 d-flex">
            <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="currentColor" d="M19,17H5V7H19M19,5H5A2,2 0 0,0 3,7V17A2,2 0 0,0 5,19H19A2,2 0 0,0 21,17V7C21,5.89 20.1,5 19,5Z"></path>
            </svg>

            <b class="mw-admin-action-links ms-3" :class="{'active': showContainer }" v-on:click="toggleContainer">
                Container
            </b>
        </div>

        <div v-if="showContainer">

        <div class="form-control-live-edit-label-wrapper my-4 d-flex align-items-center flex-wrap gap-2" id="field-conatiner-type">
            <label class="live-edit-label px-0 col-4">Container</label>
            <div class="s-field-content">

                <label class="form-check">
                    <input class="form-check-input" type="radio" name="containerType" value="container" v-model="containerType">
                    <span class="form-check-label">Container</span>
                </label>
                <label class="form-check">
                    <input class="form-check-input" type="radio" name="containerType" value="container-fluid" v-model="containerType">
                    <span class="form-check-label">Fluid</span>
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

                if (containerNode && containerNode.parentNode &&  mw.tools.isEditable(containerNode.parentNode)) {
                        this.hasContainer = true;
                        this.activeContainerNode = containerNode;
                        this.populateCssContainerForNode(containerNode);
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

        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (this.$root.selectedElement) {
                this.populateStyleEditor(this.$root.selectedElement);
            }
        });
        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'container') {
                this.showContainer = false;
            }
        });


        // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
        //     this.populateStyleEditor(element)
        // });
    },

    watch: {
        '$root.selectedElement': {
            handler: function (element) {
                if(element) {
                    this.populateStyleEditor(element);
                }
            },
            deep: true
        },
        containerType: function (newValue, oldValue) {
            this.applyClassToActiveContainerNode(newValue);

        },

    },


}
</script>


