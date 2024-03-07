<template>
    <div>

        <DropdownSmall v-model="selectedPosition" :options="selectedPositionOptions" :label="'Position'"/>


        <div class="form-group">
            <label for="topInput">Top (px):</label>
            <input id="topInput" class="form-control" type="number" v-model.number="topValue">
        </div>

        <div class="form-group">
            <label for="leftInput">Left (px):</label>
            <input id="leftInput" class="form-control" type="number" v-model.number="leftValue">
        </div>

        <div class="form-group">
            <label for="zIndexInput">Z-Index:</label>
            <input id="zIndexInput" class="form-control" type="number" v-model.number="zIndexValue">
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

            if(this.selectedPosition === 'static') {
                mw.top().app.liveEdit.elementHandleContent.elementActions.destroyFreeDraggableElement(this.activeNode)
            }
            else if(this.selectedPosition === 'absolute' || this.selectedPosition === 'relative' || this.selectedPosition === 'fixed' || this.selectedPosition === 'sticky') {
                mw.top().app.liveEdit.elementHandleContent.elementActions.makeFreeDraggableElement(this.activeNode)
            }

        },

        applyPosition() {
            if (!this.isReady) {
                return;
            }

            if (!this.activeNode) return;

            this.applyPropertyToActiveNode('position', this.selectedPosition);
            this.applyPropertyToActiveNode('top', `${this.topValue}px`);
            this.applyPropertyToActiveNode('left', `${this.leftValue}px`);
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
