<template>
    <div class="d-flex">

        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
             style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path
                d="M19,19h2v2h-2V19 M19,17h2v-2h-2V17 M3,13h2v-2H3V13 M3,17h2v-2H3V17 M3,9h2V7H3V9 M3,5h2V3H3V5 M7,5h2V3H7V5 M15,21h2v-2  h-2V21 M11,21h2v-2h-2V21 M15,21h2v-2h-2V21 M7,21h2v-2H7V21 M3,21h2v-2H3V21 M21,8c0-2.8-2.2-5-5-5h-5v2h5c1.7,0,3,1.3,3,3v5h2V8z"></path>
        </svg>

        <b class="mw-admin-action-links ms-3" :class="{'active': showPosition }"
           v-on:click="togglePosition">
            Element Position
        </b>
    </div>




    <div :class="{'d-none': !showPosition }">

        <DropdownSmall v-model="selectedPosition" :options="selectedPositionOptions" :label="'Position'"/>

<!--
        <div class="form-group">
            <label for="topInput">Top (px):</label>
            <input id="topInput" class="form-control" type="number" v-model.number="topValue">
        </div>

        <div class="form-group">
            <label for="leftInput">Left (px):</label>
            <input id="leftInput" class="form-control" type="number" v-model.number="leftValue">
        </div>-->

        <div class="form-group">
            <label for="zIndexInput">Z-Index:</label>
            <input id="zIndexInput" class="form-control" type="number" min="0" v-model.number="zIndexValue">
        </div>

        <button @click="resetAllProperties">Reset All</button>
    </div>
</template>

<script>
import DropdownSmall from "./components/DropdownSmall.vue";

export default {
    components: {DropdownSmall},
    data() {
        return {
            selectedPositionOptions: [
                {key: null, value: "Default"},
                {key: "static", value: "Static"},
                {key: "absolute", value: "Absolute"},
                {key: "relative", value: "Relative"},
                {key: "sticky", value: "Sticky"},
                {key: "fixed", value: "Fixed"}
            ],
            showPosition: null,
            activeNode: null,
            isReady: false,
            selectedPosition: null,
            topValue: 0,
            leftValue: 0,
            zIndexValue: 0
        };
    },
    methods: {
        togglePosition: function () {
            this.showPosition = !this.showPosition;
            this.emitter.emit('element-style-editor-show', 'position');
        },

        makeElementFreelyDraggableElementIfPositionAllows() {
            if (!this.isReady) {
                return;
            }

            if (!this.activeNode) return;

            // if(this.selectedPosition === 'static') {
            //     mw.top().app.liveEdit.elementHandleContent.elementActions.destroyFreeDraggableElement(this.activeNode)
            // }
            // else if(this.selectedPosition === 'absolute' || this.selectedPosition === 'relative' || this.selectedPosition === 'fixed' || this.selectedPosition === 'sticky') {
            //     mw.top().app.liveEdit.elementHandleContent.elementActions.makeFreeDraggableElement(this.activeNode)
            // }

        },

        applyPosition() {
            if (!this.isReady) {
                return;
            }

            if (!this.activeNode) return;

            this.applyPropertyToActiveNode('position', this.selectedPosition);
           // this.applyPropertyToActiveNode('top', `${this.topValue}px`);
          //  this.applyPropertyToActiveNode('left', `${this.leftValue}px`);
            this.applyPropertyToActiveNode('zIndex', this.zIndexValue);
            this.makeElementFreelyDraggableElementIfPositionAllows();
        },
        applyPropertyToActiveNode: function (prop, val) {
            if (!this.isReady) {
                return;
            }

            if (this.activeNode) {
                this.$root.applyPropertyToActiveNode(this.activeNode, prop, val);
            }
        },
        resetAllProperties: function () {
            this.selectedPosition = null;
            this.topValue = null;
            this.leftValue = null;
            this.zIndexValue = null;
        },
        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                this.populateCssPosition(css);

                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },
        populateCssPosition: function (css) {
            if (!css || !css.get) return;

            var result = css.get.position();
            if (result) {
                this.selectedPosition = result;
            }

            result = css.get.top();
            if (result) {
                //remove px
                result = result.replace('px', '');
                this.topValue = result;
            }

            result = css.get.left();
            if (result) {
                //remove px
                result = result.replace('px', '');
                this.leftValue = result;
            }

            result = css.get.zIndex();
            if (result) {
                //remove px
                this.zIndexValue = result;
            }
        }
    },
    watch: {
        '$root.selectedElement': {
            handler: function (element) {
                if (element) {
                    this.populateStyleEditor(element);
                }
            },
            deep: true
        },
        selectedPosition: function () {
            this.applyPosition();
        },
        topValue: function () {
            this.applyPosition();
        },
        leftValue: function () {
            this.applyPosition();
        },
        zIndexValue: function () {
            this.applyPosition();
        }
    }
};
</script>
