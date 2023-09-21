<template>
    <div class="d-flex flex-column gap-3">
       <div>
           <Input v-model="fontSize" :label="'Font Size'"/>
           {{ fontSize }}
       </div>

        <div>
            <Dropdown v-model="fontWeight" :options='["normal","bold","bolder","lighter","100","200","300","400","500","600","700","800","900"]' :label="'Font Weight'"/>
        </div>

        <div>
            <Dropdown v-model="textTransform" :options='["none","capitalize","uppercase","lowercase"]' :label="'Text Transform'"/>
        </div>

        <div>
            <Dropdown v-model="fontStyle" :options='["normal", "italic", "oblique"]' :label="'Font Style'"/>
        </div>

        <div>
            <Input v-model="lineHeight" :label="'Line Height'"/>
        </div>

        <div>
            <Dropdown v-model="fontFamily" :label="'Font Family'"/>
        </div>

        <div>
            <Input v-model="color" :label="'Font Color'"/>
        </div>

    </div>
</template>

<script>
import Input from '../../components/Form/Input.vue';
import Dropdown from '../../components/Form/Dropdown.vue';

export default {
    components: {Dropdown, Input},
    data() {
        return {
            'activeNode': null,
            'isReady': false,
            'textAlign': null,
            'fontSize': null,
            'fontWeight': null,
            'fontStyle': null,
            'lineHeight': null,
            'fontFamily': null,
            'color': null,
            'textTransform': null,
            'textDecorationIsBold': null,
            'textDecorationIsItalic': null,
            'textDecorationIsUnderline': null,
            'textDecorationIsStrikethrough': null,
        };
    },

    methods: {
        resetAllProperties: function () {
            this.fontSize = null;
            this.fontWeight = null;
            this.fontStyle = null;
            this.lineHeight = null;
            this.fontFamily = null;
            this.color = null;
            this.textTransform = null;
            this.textDecorationIsBold = null;
            this.textDecorationIsItalic = null;
            this.textDecorationIsUnderline = null;
            this.textDecorationIsStrikethrough = null;
        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;


                this.populateCssTextAlign(css);
                this.populateCssTextDecoration(css);
                this.populateCssFont(css);

                this.isReady = true;
            }
        },


        populateCssTextAlign: function (css) {
            if (!css || !css.get) return;
            var align = css.get.alignNormalize();
            this.textAlign = align;
        },
        populateCssTextDecoration: function (css) {
            if (!css || !css.get) return;
            var is = css.get.is();

            this.textDecorationIsBold = is.bold;
            this.textDecorationIsItalic = is.italic;
            this.textDecorationIsUnderline = is.underlined;
            this.textDecorationIsStrikethrough = is.striked;

        },

        populateCssFont: function (css) {
            if (!css || !css.get) return;
            var font = css.get.font();

            this.fontSize = font.size;
            this.fontWeight = font.weight;
            this.fontStyle = font.style;
            this.lineHeight = font.lineHeight;
            this.fontFamily = font.family;
            this.color = font.color;
        },

        applyPropertyToActiveNode: function (prop, val) {
            if (!this.isReady) {
                return;
            }
            if (this.activeNode) {
                mw.top().app.dispatch('mw.elementStyleEditor.applyCssPropertyToNode', {
                    node: this.activeNode,
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
        // Font-related property watchers
        fontSize: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('fontSize', newValue);
        },
        fontWeight: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('fontWeight', newValue);
        },
        fontStyle: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('fontStyle', newValue);
        },
        lineHeight: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('lineHeight', newValue);
        },
        fontFamily: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('fontFamily', newValue);
        },
        color: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('color', newValue);
        },
        textTransform: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('textTransform', newValue);
        },
    },


}
</script>


