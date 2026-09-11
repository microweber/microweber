<style scoped >

.mw-ese-holder {
    background-color: #f5f5f5;
    border: 1px solid var(--ese-border, rgba(15,23,42,0.12));
    transition: .2s;
}

.mw-ese-holder.active {
    background-color: #fff;
    border: 1px solid var(--ese-accent, #182433);
    box-shadow: 0 0 3px rgba(24, 36, 51, .4);
}


.mw-ese-margin {

    padding: 35px 50px;
    position: relative;
    display: inline-block;
}

.mw-ese-padding {
    width: 100px;
    height: 65px;
    padding: 50px 35px;
    position: relative;
}

.mw-element-spacing-editor {
    padding: 10px 20px;
}

.mw-element-spacing-editor .input input {
    width: 40px;
    height: 22px;
    border-radius: var(--radius-xs, 4px);
    line-height: 20px;
    border: 1px solid var(--ese-border, rgba(15,23,42,0.12));
    padding: 0 3px;
    text-align: center;
    font-size: 12px;
}

.mw-element-spacing-editor .input {
    position: absolute;

    z-index: 1;
}

.mw-ese-top {
    top: -10px;
    left: 50%;
    transform: translateX(-50%);
}

.mw-ese-right {
    top: 50%;
    right: -20px;
    transform: translateY(-50%);
}

.mw-ese-bottom {
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%);
}

.mw-ese-left {
    top: 50%;
    left: -20px;
    transform: translateY(-50%);
}

.mw-ese-label {
    display: block;
    font-size: 10px;
    text-transform: uppercase;
}

.mw-ese-padding .mw-ese-label {
    text-align: center;
    position: absolute;
    top: 50%;
    left: 0;
    width: 100%;
    transform: translateY(-50%);
}
.btn-small-holder .btn {
    padding: 0  5px;
}

.btn svg {
    width: 19px;
    fill: currentColor;
}


.mw-live-edit-spacing-wrapper a.btn {
    @apply hover:bg-gray-900 hover:text-white text-[12px] font-bold cursor-pointer;

    cursor: pointer;
    width: 35px;
    height: 35px;
    padding: 0;
    min-height: 0 !important;
    padding: 0;

    &:before {
        font-size: 18px;
     }

    &.active {
        @apply bg-gray-900 dark:bg-slate-900 text-white;
     }
}

</style>



<template>
    <div class="d-flex">
        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
             style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path
                d="M10.6,12l4-4H11V6h7v7h-2V9.4l-4,4V16h8V4H8v8H10.6 M22,2v16H12v4H2V12h4V2H22 M10,14H4v6h6V14z"></path>
        </svg>

        <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showSpacing }">
            Spacing
        </span>
    </div>

    <!-- task-2026-05-16-ea56d3: @click.stop — see ElementStyleEditorTypography.vue -->
    <!-- task-2026-05-16-c3d0ed (ESE Slice 1.3b / AI-684 cont.): preset rows
         migrated to MwToolButton primitive per spec §4.5. S/M/L/XL/trash
         buttons carry .mw-tool-btn.mw-tool-btn--preset (32x28 label-sized,
         accent-soft on .is-active); the icon-only "Fine-tune each side"
         settings cog stays the default .mw-tool-btn (24x24 ghost). The
         legacy .btn .btn-icon classes are preserved alongside for back-
         compat per the migration plan — new primitives own the styling,
         legacy classes remain as DOM hooks for external code. -->
    <div v-if="showSpacing" @click.stop>

        <!-- LE redesign — Inner space = padding, Outer space = margin, applied as
             Bootstrap p-*/m-* utility CLASSES via the reusable SpaceAround
             control (soft-pill None/S/M/L). Per-side px fine-tune moves under
             "More options" (unchanged box-model editor). -->
        <SpaceAround label="Inner space" mode="padding" :show-mode-dropdown="false"
                     :value="innerSpace" @update="onInnerUpdate"/>
        <SpaceAround label="Outer space" mode="margin" :show-mode-dropdown="false"
                     :value="outerSpace" @update="onOuterUpdate"/>

        <details class="mw-typography-advanced">
            <summary class="cursor-pointer text-xs opacity-70 hover:opacity-100 py-2">
                More options
            </summary>
            <div class="mw-element-spacing-editor mt-2">
                <span class="mw-ese-label">Margin</span>
                <div class="mw-ese-holder mw-ese-margin">
                    <span class="input mw-ese-top"><input type="number" v-model="marginTop"></span>
                    <span class="input mw-ese-right"><input type="number" v-model="marginRight"></span>
                    <span class="input mw-ese-bottom"><input type="number" v-model="marginBottom"></span>
                    <span class="input mw-ese-left"><input type="number" v-model="marginLeft"></span>
                    <div class="mw-ese-holder mw-ese-padding">
                        <span class="input mw-ese-top"><input type="number" min="0" v-model="paddingTop"></span>
                        <span class="input mw-ese-right"><input type="number" min="0" v-model="paddingRight"></span>
                        <span class="input mw-ese-bottom"><input type="number" min="0" v-model="paddingBottom"></span>
                        <span class="input mw-ese-left"><input type="number" min="0" v-model="paddingLeft"></span>
                        <span class="mw-ese-label">Padding</span>
                    </div>
                </div>
            </div>
        </details>
    </div>

</template>

<script>
import SpaceAround from './components/SpaceAround.vue';

export default {
    components: { SpaceAround },

    data() {
        return {
            'activePadding': null,
            'activeMargin': null,
            // LE redesign — current p-*/m-* class size (px) for the SpaceAround
            // active-state (Inner=padding, Outer=margin).
            'innerSpace': null,
            'outerSpace': null,
            'showSpacing': false,
            'showMarginAndPaddingControlls': false,
            'activeNode': null,
            'isReady': false,

            'marginTop': null,
            'marginRight': null,
            'marginBottom': null,
            'marginLeft': null,

            'paddingTop': null,
            'paddingRight': null,
            'paddingBottom': null,
            'paddingLeft': null,

        };
    },

    methods: {
        iconResolver: function (icon) {
            return mw.iconResolver(icon)
        },
        toggleSpacing: function () {
            this.showSpacing = !this.showSpacing;
            this.emitter.emit('element-style-editor-show', 'spacing');
        },
        toggleMarginAndPaddingControlls: function () {
            this.showMarginAndPaddingControlls = !this.showMarginAndPaddingControlls;
         },
        resetAllProperties: function () {
            this.marginTop = null;
            this.marginRight = null;
            this.marginBottom = null;
            this.marginLeft = null;

            this.paddingTop = null;
            this.paddingRight = null;
            this.paddingBottom = null;
            this.paddingLeft = null;
        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                // LE redesign — reflect current p-*/m-* class in the SpaceAround controls.
                this.innerSpace = this._readSpaceClass('p');
                this.outerSpace = this._readSpaceClass('m');

                this.populateCssPadding(css);
                this.populateCssMargin(css);

                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },


        // LE redesign — SpaceAround (Inner=padding / Outer=margin) applies a
        // Bootstrap p-*/m-* utility class. px<->step: 0->0,8->2,16->3,48->5.
        _pxToStep: function (px) {
            var m = {0: '0', 8: '2', 16: '3', 48: '5', 4: '1', 24: '4'};
            return m[px] != null ? m[px] : '3';
        },
        _stepToPx: function (step) {
            var m = {'0': 0, '1': 4, '2': 8, '3': 16, '4': 24, '5': 48};
            return m[step] != null ? m[step] : null;
        },
        _applySpaceClass: function (prefix, px) {
            var node = this.activeNode;
            if (!node) return;
            var re = new RegExp('^' + prefix + '-[0-5]$');
            Array.from(node.classList).forEach(function (c) { if (re.test(c)) node.classList.remove(c); });
            node.classList.add(prefix + '-' + this._pxToStep(px));
            try { mw.top().app.registerChange(node); } catch (e) { /* noop */ }
        },
        _readSpaceClass: function (prefix) {
            var node = this.activeNode;
            if (!node) return null;
            var re = new RegExp('^' + prefix + '-([0-5])$');
            var self = this, found = null;
            try { Array.from(node.classList).forEach(function (c) { var m = re.exec(c); if (m) found = self._stepToPx(m[1]); }); } catch (e) {}
            return found;
        },
        onInnerUpdate: function (payload) {
            var px = payload && typeof payload === 'object' ? payload.px : payload;
            this.innerSpace = px;
            this._applySpaceClass('p', px);
        },
        onOuterUpdate: function (payload) {
            var px = payload && typeof payload === 'object' ? payload.px : payload;
            this.outerSpace = px;
            this._applySpaceClass('m', px);
        },

        setPaddingOnAllSides: function (val) {
            this.paddingTop = val;
            this.paddingRight = val;
            this.paddingBottom = val;
            this.paddingLeft = val;
            this.activePadding = val;
        },

        setMarginOnAllSides: function (val) {
            this.marginTop = val;
            this.marginRight = val;
            this.marginBottom = val;
            this.marginLeft = val;
            this.activeMargin = val;

        },

        populateCssPadding: function (css) {
            var padding = css.get.padding(undefined, true);
            this.paddingTop = this.coerceFinite(padding.top);
            this.paddingRight = this.coerceFinite(padding.right);
            this.paddingBottom = this.coerceFinite(padding.bottom);
            this.paddingLeft = this.coerceFinite(padding.left);
        },

        populateCssMargin: function (css) {
            if (!css || !css.get) return;
            var margin = css.get.margin(undefined, true);
            this.marginTop = this.coerceFinite(margin.top);
            this.marginRight = this.coerceFinite(margin.right);
            this.marginBottom = this.coerceFinite(margin.bottom);
            this.marginLeft = this.coerceFinite(margin.left);
        },

        // TICKET-B (audit-test reply 2026-05-06): css.get.margin/padding can
        // return "auto" or other non-numeric strings; parseFloat("auto") is
        // NaN, which v-model would render literally in the spacing inputs.
        // Coerce to null so the input shows empty instead of "NaN".
        coerceFinite: function (val) {
            if (val === null || val === undefined || val === '') return null;
            const n = parseFloat(val);
            return Number.isFinite(n) ? n : null;
        },


        applyPropertyToActiveNode: function (prop, val) {
            if (!this.isReady) {
                return;
            }


            if (this.activeNode) {
                this.$root.applyPropertyToActiveNode(this.activeNode, prop, val);
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
            if (elementStyleEditorShow !== 'spacing') {
                this.showSpacing = false;
            }
        });

        // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
        //   this.populateStyleEditor(element)
        // });

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

        // Margin-related property watchers
        marginTop: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('marginTop', newValue + 'px');
        },
        marginRight: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('marginRight', newValue + 'px');
        },
        marginBottom: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('marginBottom', newValue + 'px');
        },
        marginLeft: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('marginLeft', newValue + 'px');
        },

        // Padding-related property watchers
        paddingTop: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('paddingTop', newValue + 'px');
        },
        paddingRight: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('paddingRight', newValue + 'px');
        },
        paddingBottom: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('paddingBottom', newValue + 'px');
        },
        paddingLeft: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('paddingLeft', newValue + 'px');
        },

    },


}
</script>
