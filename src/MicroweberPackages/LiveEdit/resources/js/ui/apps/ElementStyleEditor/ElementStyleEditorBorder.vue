<style>
.mw-field {
    width: 100%;
}

.mw-field.unit input + input {
    width: 40px;
    padding-inline-start: 0;
    padding-inline-end: 0;
    text-align: center;
}

.rouded-corners {
    padding-bottom: 20px;
}

.rouded-corners .mw-field .mw-field {
    width: 70px;
    margin: 10px 0 5px;
}

.rouded-corners .mw-field .mw-field + .mw-field {
    margin-inline-start: 10px;
}

.angle {
    display: inline-block;
    width: 15px;
    height: 15px;
    border: 1px dotted #ccc;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

.angle-top-left {
    border-top-left-radius: 7px;
    border-left: 1px solid #000;
    border-top: 1px solid #000;
}

.angle-top-right {
    border-top-right-radius: 7px;
    border-right: 1px solid #000;
    border-top: 1px solid #000;
}

.angle-bottom-left {
    border-bottom-left-radius: 7px;
    border-left: 1px solid #000;
    border-bottom: 1px solid #000;
}

.angle-bottom-right {
    border-bottom-right-radius: 7px;
    border-right: 1px solid #000;
    border-bottom: 1px solid #000;
}

.s-field-content {
    display: flex;
    flex-direction: column;
}
</style>

<template>

    <div class="d-flex">
        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
             style="enable-background:new 0 0 24 24;" xml:space="preserve">
            <path
                d="M15,21h2v-2h-2 M19,21h2v-2h-2 M7,21h2v-2H7 M11,21h2v-2h-2 M19,17h2v-2h-2 M19,13h2v-2h-2 M3,3v18h2V5h16V3 M19,9h2V7h-2"></path>
        </svg>

        <b class="mw-admin-action-links ms-3" :class="{'active': showBorder }" v-on:click="toggleBorder">
            Border
        </b>
    </div>

    <div v-if="showBorder">


        <DropdownSmall v-model="borderPosition" :options="borderPositionOptions" label="Position"/>


        <DropdownSmall v-if="borderPosition == 'all'" v-model="borderStyle" :options="borderStylesOptions" label="Style"/>


        <DropdownSmall v-if="borderPosition == 'top'" v-model="borderStyleTop" :options="borderStylesOptions" label="Top Border Style"/>


        <DropdownSmall v-if="borderPosition == 'left'" v-model="borderStyleLeft" :options="borderStylesOptions" label="Left Border Style"/>
        <DropdownSmall v-if="borderPosition == 'right'" v-model="borderStyleRight" :options="borderStylesOptions" label="Right Border Style"/>
        <DropdownSmall v-if="borderPosition == 'bottom'" v-model="borderStyleBottom" :options="borderStylesOptions" label="Bottom Border Style"/>

        <SliderSmall v-if="borderPosition == 'all'" label="Size" v-model="borderSize" :min="0" :max="30" :step="1"></SliderSmall>

        <SliderSmall v-if="borderPosition == 'top'" label="Top Border Size" v-model="borderSizeTop" :min="0" :max="30" :step="1"></SliderSmall>
        <SliderSmall v-if="borderPosition == 'left'" label="Left Border Size" v-model="borderSizeLeft" :min="0" :max="30" :step="1"></SliderSmall>
        <SliderSmall v-if="borderPosition == 'right'" label="Right Border Size" v-model="borderSizeRight" :min="0" :max="30" :step="1"></SliderSmall>
        <SliderSmall v-if="borderPosition == 'bottom'" label="Bottom Border Size" v-model="borderSizeBottom" :min="0" :max="30" :step="1"></SliderSmall>



        <ColorPicker v-if="borderPosition == 'all'" v-model="borderColor" v-bind:color=borderColor :label="'Color'"
                     @change="handleBorderColorChange"/>



        <ColorPicker v-if="borderPosition == 'top'" v-model="borderColorTop" v-bind:color="borderColorTop" :label="'Top Border Color'" @change="handleBorderColorTopChange"/>
        <ColorPicker v-if="borderPosition == 'left'" v-model="borderColorLeft" v-bind:color="borderColorLeft" :label="'Left Border Color'" @change="handleBorderColorLeftChange"/>
        <ColorPicker v-if="borderPosition == 'right'" v-model="borderColorRight" v-bind:color="borderColorRight" :label="'Right Border Color'" @change="handleBorderColorRightChange"/>
        <ColorPicker v-if="borderPosition == 'bottom'" v-model="borderColorBottom" v-bind:color="borderColorBottom" :label="'Bottom Border Color'" @change="handleBorderColorBottomChange"/>



    </div>

</template>

<script>
import Input from '../../components/Form/Input.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "./components/FontPicker.vue";
import ColorPicker from "./components/ColorPicker.vue";
import DropdownSmall from "./components/DropdownSmall.vue";
import SliderSmall from "./components/SliderSmall.vue";

import Slider from '@vueform/slider';

export default {

    components: {Dropdown, Input, FontPicker, ColorPicker, Slider, DropdownSmall, SliderSmall},

    data() {
        return {
            'showBorder': false,
            'activeNode': null,
            'isReady': false,

            'borderPositionOptions': [
                {"key": "all", "value": "All"},
                {"key": "top", "value": "Top"},
                {"key": "right", "value": "Right"},
                {"key": "bottom", "value": "Bottom"},
                {"key": "left", "value": "Left"},
            ],

            'borderStylesOptions': [
                {"key": "none", "value": 'None'},
                {"key": "solid", "value": "Solid"},
                {"key": "dotted", "value": "Dotted"},
                {"key": "dashed", "value": "Dashed"},
                {"key": "double", "value": "Double"},
                {"key": "groove", "value": "Groove"},
                {"key": "ridge", "value": "Ridge"},
                {"key": "inset", "value": "Inset"},
                {"key": "outset", "value": "Outset"}
            ],

            'borderPosition': null,
            'borderSize': null,
            'borderSizeTop': null,
            'borderSizeBottom': null,
            'borderSizeLeft': null,
            'borderSizeRight': null,


            'borderImage': null,
            'borderImageUrl': null,


            'borderStyle': null,
            'borderStyleTop': null,
            'borderStyleBottom': null,
            'borderStyleLeft': null,
            'borderStyleRight': null,


            'borderColor': null,
            'borderColorTop': null,
            'borderColorLeft': null,
            'borderColorRight': null,
            'borderColorBottom': null,


        };
    },

    methods: {
        toggleBorder: function () {
            this.showBorder = !this.showBorder;
            this.emitter.emit('element-style-editor-show', 'border');
        },
        handleBorderColorChange(color) {
            if (typeof (color) != 'string') {
                return;
            }
            this.borderColor = color;
        },
        resetAllProperties: function () {
            this.borderPosition = null;
            this.borderSize = null;
            this.borderSizeTop = null;
            this.borderSizeBottom = null;
            this.borderSizeLeft = null;
            this.borderSizeRight = null;
            this.borderColor = null;
            this.borderStyle = null;
            this.borderStyleTop = null;
            this.borderStyleBottom = null;
            this.borderStyleLeft = null;
            this.borderStyleRight = null;

            this.borderColorTop = null;
            this.borderColorLeft = null;
            this.borderColorRight = null;
            this.borderColorBottom = null;

            this.borderImageUrl = null;
            this.borderImage = null;
        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                this.populateCssBorder(css);

                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },
        populateCssBorder: function (css) {
            if (!css || !css.get) return;
            var border = css.get.border(true);



            var frst = {};
            for (var i in border) {
                if (border[i].width !== 0) {
                    frst = border[i];
                    break;
                }
            }
            var size = frst.width || 0;
            var color = frst.color || '';
            var style = frst.style || 'none';

            this.borderSize = size;
            this.borderColor = color;
            this.borderStyle = style;



            if (border.top && border.top.style) {
                this.borderStyleTop = border.top.style;
            }
            if (border.bottom && border.bottom.style) {
                this.borderStyleBottom = border.bottom.style;
            }
            if (border.left && border.left.style) {
                this.borderStyleLeft = border.left.style;
            }
            if (border.right && border.right.style) {
                this.borderStyleRight = border.right.style;
            }

            if (border.top && border.top.color  && border.top.color != "rgb(0, 0, 0)") {
                this.borderColorTop = border.top.color;
            }
            if (border.bottom && border.bottom.color  && border.bottom.color != "rgb(0, 0, 0)") {
                this.borderColorBottom = border.bottom.color;
            }
            if (border.left && border.left.color  && border.left.color != "rgb(0, 0, 0)") {
                this.borderColorLeft = border.left.color;
            }
            if (border.right && border.right.color  && border.right.color != "rgb(0, 0, 0)") {
                this.borderColorRight = border.right.color;
            }

            if (border.top && border.top.width) {
                this.borderPosition = 'top';
                this.borderSizeTop = border.top.width;
            }
            if (border.bottom && border.bottom.width) {
                this.borderPosition = 'bottom';
                this.borderSizeBottom = border.bottom.width;
            }
            if (border.left && border.left.width) {
                this.borderPosition = 'left';
                this.borderSizeLeft = border.left.width;
            }
            if (border.right && border.right.width) {
                this.borderPosition = 'right';
                this.borderSizeRight = border.right.width;
            }

            if(!this.borderPosition){
                this.borderPosition = 'all';
            }



        },


        applyPropertyToActiveNode: function (prop, val) {
            if (!this.isReady) {
                return;
            }

            if (this.activeNode) {
                this.$root.applyPropertyToActiveNode(this.activeNode, prop, val);
            }
        },


        handleBorderColorTopChange(color) {
            if (typeof color === 'string') {
                this.borderColorTop = color;
                this.applyPropertyToActiveNode('border-top-color', color);
            }
        },
        handleBorderColorLeftChange(color) {
            if (typeof color === 'string') {
                this.borderColorLeft = color;
                this.applyPropertyToActiveNode('border-left-color', color);
            }
        },
        handleBorderColorRightChange(color) {
            if (typeof color === 'string') {
                this.borderColorRight = color;
                this.applyPropertyToActiveNode('border-right-color', color);
            }
        },
        handleBorderColorBottomChange(color) {
            if (typeof color === 'string') {
                this.borderColorBottom = color;
                this.applyPropertyToActiveNode('border-bottom-color', color);
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
            if (elementStyleEditorShow !== 'border') {
                this.showBorder = false;
            }
        });

        // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
        //
        //   this.populateStyleEditor(element)
        //
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


        // Border-related property watchers
        borderImageUrl: function (newValue, oldValue) {
            var borderImageValue = '';
            borderImageValue += 'url(' + newValue + ') ';
            borderImageValue += this.borderSize + ' ';
            //    borderImageValue +=  this.borderStyle + ' ';
            borderImageValue += ' space ';
            this.borderImage = borderImageValue;
        },
        borderImage: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-image', newValue);
        },

        borderPosition: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('borderPosition', newValue);
        },
        borderSize: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-width', newValue + 'px');
        },
        borderColor: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-color', newValue);
        },
        borderStyle: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-style', newValue);
        },
        borderStyleTop: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-top-style', newValue);
        },
        borderStyleBottom: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-bottom-style', newValue);
        },
        borderStyleLeft: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-left-style', newValue);
        },
        borderStyleRight: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-right-style', newValue);
        },
        borderColorTop: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-top-color', newValue);
        },
        borderColorBottom: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-bottom-color', newValue);
        },
        borderColorLeft: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-left-color', newValue);
        },
        borderColorRight: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-right-color', newValue);
        },
        borderSizeTop: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-top-width', newValue + 'px');
        },
        borderSizeBottom: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-bottom-width', newValue + 'px');
        },
        borderSizeLeft: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-left-width', newValue + 'px');
        },
        borderSizeRight: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('border-right-width', newValue + 'px');
        },



    },
}
</script>
