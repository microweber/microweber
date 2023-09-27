<template>
    <div class="d-flex flex-column gap-3">

        <div>
            <FontPicker v-model="fontFamily" :label="'Font Family'"/>
        </div>

        <div class="d-flex justify-content-between">
            <div class="mr-4">Font Color</div>
            <div>
                <ColorPicker />
            </div>
        </div>

        <div>
            <div class="mr-4">Font Size - {{fontSize}}</div>
            <div>
                <Slider
                    :min="6"
                    :max="120"
                    :step="1"
                    :merge="1"
                    :tooltips="false"
                    :tooltipPosition="'right'"
                    v-model="fontSize"
                />
            </div>
        </div>

        <div>
            <Dropdown v-model="fontWeight" :options="fontWeightOptions" :label="'Font Weight'"/>
        </div>

        <div>
            <Dropdown v-model="textTransform" :options="textTransformOptions" :label="'Text Transform'"/>
        </div>

        <div>
            <Dropdown v-model="fontStyle" :options="fontStylesOptions" :label="'Font Style'"/>
        </div>

        <div>
            <div class="mr-4">Line Heigh - {{fontSize}}</div>
            <div>
                <Slider
                    :min="6"
                    :max="120"
                    :step="1"
                    :merge="1"
                    :tooltips="false"
                    :tooltipPosition="'right'"
                    v-model="lineHeight"
                />
            </div>
        </div>

    </div>
</template>

<style src="@vueform/slider/themes/default.css"></style>

<script>
import Input from '../../components/Form/Input.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "../../components/Form/FontPicker.vue";
import ColorPicker from "../../components/Editor/Colors/ColorPicker.vue";
import Slider from '@vueform/slider';

export default {
    components: {ColorPicker, FontPicker, Dropdown, Input, Slider},
    data() {
        return {
            'activeNode': null,
            'isReady': false,
            'textTransformOptions': [
                { "key": "none", "value": "None" },
                { "key": "capitalize", "value": "Capitalize" },
                { "key": "uppercase", "value": "Uppercase" },
                { "key": "lowercase", "value": "Lowercase" }
            ],
            'fontWeightOptions': [
                { "key": "normal", "value": "Normal" },
                { "key": "bold", "value": "Bold" },
                { "key": "bolder", "value": "Bolder" },
                { "key": "lighter", "value": "Lighter" },
                { "key": "100", "value": "100" },
                { "key": "200", "value": "200" },
                { "key": "300", "value": "300" },
                { "key": "400", "value": "400" },
                { "key": "500", "value": "500" },
                { "key": "600", "value": "600" },
                { "key": "700", "value": "700" },
                { "key": "800", "value": "800" },
                { "key": "900", "value": "900" }
            ],
            "fontStylesOptions": [
                { "key": "normal", "value": "Normal" },
                { "key": "italic", "value": "Italic" },
                { "key": "oblique", "value": "Oblique" }
            ],
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
            this.applyPropertyToActiveNode('fontSize', newValue + 'px');
        },
        fontWeight: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('fontWeight', newValue);
        },
        fontStyle: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('fontStyle', newValue);
        },
        lineHeight: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('lineHeight', newValue + 'px');
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


