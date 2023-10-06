<template>

    <div>
        <b v-on:click="toggleTypography">
            Typography
        </b>
    </div>
    <div v-if="showTypography">
     <div class="d-flex flex-column gap-3">

        <div>
            <FontPicker v-model="fontFamily" v-bind:value=fontFamily @change="handleFontChange" :label="'Font Family'"/>
        </div>


        <Align :textAlign="textAlign" @update:textAlign="setTextAlignment"/>


        <ColorPicker v-model="color" v-bind:color=color :label="'Font Color'" @change="handleFontColorChange"/>

        <SliderSmall
            label="Font Size"
            v-model="fontSize"
            :min="0"
            :max="100"
            :step="5"
        ></SliderSmall>


        <DropdownSmall v-model="fontWeight" :options="fontWeightOptions" :label="'Font Weight'"/>
        <DropdownSmall v-model="textTransform" :options="textTransformOptions" :label="'Text Transform'"/>
        <DropdownSmall v-model="fontStyle" :options="fontStylesOptions" :label="'Font Style'"/>


        <SliderSmall
            label="Line Height"
            v-model="lineHeight"
            :min="0"
            :max="100"
            :step="5"
        ></SliderSmall>


    </div>
    </div>

</template>


<script>
import Input from '../../components/Form/Input.vue';
import Align from './components/Align.vue';
import DropdownSmall from './components/DropdownSmall.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "./components/FontPicker.vue";
import ColorPicker from "./components/ColorPicker.vue";
import SliderSmall from "./components/SliderSmall.vue";
import Slider from '@vueform/slider';

export default {
    components: {ColorPicker, FontPicker, Dropdown, Input, Slider, Align, DropdownSmall, SliderSmall},
    data() {
        return {
            'showTypography': false,
            'activeNode': null,
            'isReady': false,
            'textTransformOptions': [
                {"key": "none", "value": "None"},
                {"key": "capitalize", "value": "Capitalize"},
                {"key": "uppercase", "value": "Uppercase"},
                {"key": "lowercase", "value": "Lowercase"}
            ],
            'fontWeightOptions': [
                {"key": "normal", "value": "Normal"},
                {"key": "bold", "value": "Bold"},
                {"key": "bolder", "value": "Bolder"},
                {"key": "lighter", "value": "Lighter"},
                {"key": "100", "value": "100"},
                {"key": "200", "value": "200"},
                {"key": "300", "value": "300"},
                {"key": "400", "value": "400"},
                {"key": "500", "value": "500"},
                {"key": "600", "value": "600"},
                {"key": "700", "value": "700"},
                {"key": "800", "value": "800"},
                {"key": "900", "value": "900"}
            ],
            "fontStylesOptions": [
                {"key": "normal", "value": "Normal"},
                {"key": "italic", "value": "Italic"},
                {"key": "oblique", "value": "Oblique"}
            ],
            'textAlign': null,
            'fontSize': null,
            'fontWeight': null,
            'fontStyle': null,
            'lineHeight': null,
            'fontFamily': null,
            'color': null,
            'textTransform': 'none',
            'textDecorationIsBold': null,
            'textDecorationIsItalic': null,
            'textDecorationIsUnderline': null,
            'textDecorationIsStrikethrough': null,
        };
    },

    methods: {
        toggleTypography() {
            this.showTypography = !this.showTypography;

        },
        resetAllProperties: function () {
            this.fontSize = null;
            this.fontWeight = null;
            this.fontStyle = null;
            this.lineHeight = null;
            this.fontFamily = null;
            this.color = null;
            this.textTransform = 'none';
            this.textDecorationIsBold = null;
            this.textDecorationIsItalic = null;
            this.textDecorationIsUnderline = null;
            this.textDecorationIsStrikethrough = null;
        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {

                mw.top().app.dispatch('mw.elementStyleEditor.closeAllOpenedMenus');
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;


                this.populateCssTextAlign(css);
                this.populateCssTextDecoration(css);
                this.populateCssFont(css);
                this.populateCssTextTransform(css);



                setTimeout(() => {
                    this.isReady = true;
                }, 100);
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

        setTextAlignment: function (alignment) {
            this.textAlign = alignment;

        },
        handleFontChange: function (fontFamily) {
            this.fontFamily = fontFamily;
        },

        handleFontColorChange: function (color) {
            if (typeof (color) != 'string') {
                return;
            }

            this.color = color;
        },

        populateCssFont: function (css) {
            if (!css || !css.get) return;
            var font = css.get.font();


            //repalce px
            if (font.size) {
                font.size = font.size.replace('px', '');
            }
            if (font.lineHeight) {
                font.lineHeight = font.lineHeight.replace('px', '');
            }
            this.fontSize = font.size;

            this.fontWeight = font.weight;
            this.fontStyle = font.style;
            this.lineHeight = font.lineHeight;
            this.fontFamily = font.family;
            this.color = font.color;
        },
        populateCssTextTransform: function (css) {
            if (!css || !css.get) return;
            var textTransform = css.get.textTransform();
            this.textTransform = textTransform;
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
        fontFamily: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('fontFamily', newValue);
        },
        fontSize: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('fontSize', newValue + 'px');
        },
        fontWeight: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('fontWeight', newValue);
        },
        fontStyle: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('fontStyle', newValue);
        },
        textAlign: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('textAlign', newValue);
        },
        lineHeight: function (newValue, oldValue) {
            var setAuto = false;
            if (typeof (newValue) === 'undefined') {
                setAuto = true;
            }
            if (newValue == null) {
                setAuto = true;
            }
            if (setAuto) {
                this.applyPropertyToActiveNode('lineHeight', 'auto');
                return;
            }
            this.applyPropertyToActiveNode('lineHeight', newValue + 'px');
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


