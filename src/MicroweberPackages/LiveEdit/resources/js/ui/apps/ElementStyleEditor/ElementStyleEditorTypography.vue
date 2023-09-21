<template>
    <div>

        <div>
            fontSize: {{fontSize}} <br />
            fontWeight: {{fontWeight}} <br />
            fontStyle: {{fontStyle}} <br />
            lineHeight: {{lineHeight}} <br />
            fontFamily: {{fontFamily}} <br />
            color: {{color}} <br />
            textAlign: {{textAlign}} <br />
            textDecorationIsBold: {{textDecorationIsBold}} <br />
            textDecorationIsItalic: {{textDecorationIsItalic}} <br />
            textDecorationIsUnderline: {{textDecorationIsUnderline}} <br />
            textDecorationIsStrikethrough: {{textDecorationIsStrikethrough}} <br />
        </div>

       <div>
           <Input v-model="fontSize" :label="'Font Size'"/>
       </div>

        <div>
            <Input v-model="fontWeight" :label="'Font Weight'"/>
        </div>

        <div>
            <Input v-model="fontStyle" :label="'Font Style'"/>
        </div>

        <div>
            <Input v-model="lineHeight" :label="'Line Height'"/>
        </div>

        <div>
            <Input v-model="fontFamily" :label="'Font Family'"/>
        </div>

        <div>
            <Input v-model="color" :label="'Font Color'"/>
        </div>
    </div>
</template>

<script>
import Input from '../../components/Form/Input.vue';

export default {
    components: {Input},
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
    },


}
</script>


