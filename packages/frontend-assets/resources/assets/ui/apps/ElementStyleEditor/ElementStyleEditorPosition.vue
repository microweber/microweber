<template>


    <div v-if="canChangePosition">
    <div class="d-flex">

        <!-- Heroicon outline arrows-pointing-out — matches the other section header icons
             (outline, viewBox 0 0 24 24) instead of a filled custom icon. -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" width="24" height="24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15" />
        </svg>

        <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showPosition }"
           v-on:click="togglePosition">
            Element Position
        </span>
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
            canChangePosition: null,
            topValue: 0,
            leftValue: 0,
            zIndexValue: 0
        };
    },


    mounted() {
        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'position') {
                this.showPosition = false;
            } else {
                this.showPosition = true;
            }
        });

    },
    methods: {
        togglePosition: function () {
            if (!this.showPosition) {
                this.emitter.emit('element-style-editor-show', 'position');
            } else {
                this.emitter.emit('element-style-editor-show', 'none');
            }
        },

        makeElementFreelyDraggableElementIfPositionAllows() {
            if (!this.isReady) {
                return;
            }

            if (!this.activeNode) return;

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
            this.canChangePosition = null;
        },
        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();

                this.canChangePosition = mw.tools.hasAnyOfClassesOnNodeOrParent(node, [
                    'element',
                    'module',
                    'position-relative',
                    'position-absolute',
                    'position-fixed',
                    'position-sticky'
                ]);



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
                this.topValue = this.coerceFinite(result);
            }

            result = css.get.left();
            if (result) {
                //remove px
                result = result.replace('px', '');
                this.leftValue = this.coerceFinite(result);
            }

            result = css.get.zIndex();
            if (result) {
                //remove px
                this.zIndexValue = this.coerceFinite(result);
            }
        },
        // TICKET-B (audit-test reply 2026-05-06): coerce non-finite values
        // (e.g. css.get.zIndex() returning "auto", or .top() returning ""
        // after a strip) to null before binding into a v-model.number input.
        // Without this, the input renders the literal string "NaN" — the
        // exact bug agent-test asked us to stop slipping.
        coerceFinite(val) {
            if (val === null || val === undefined || val === '') return null;
            const n = Number(val);
            return Number.isFinite(n) ? n : null;
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
