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

        <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showBorder }">
            Border
        </span>
    </div>

    <!-- task-2026-05-16-ea56d3: @click.stop — see ElementStyleEditorTypography.vue -->
    <!-- LE redesign (frame 1c) — Apply-to side scope + Style/Width/Color lead as
         soft-pill segmented / swatches; a single control writes to the scoped
         side via computed routing (activeStyleModel/activeWidthModel/active
         color) over the existing per-side data + watchers. Rare styles, an
         exact-px slider and the latent border-image fold into More options.
         Radius is intentionally NOT here — the Rounded corners panel owns it. -->
    <div v-if="showBorder" @click.stop>

        <!-- Apply to — which side the controls below affect (state only). -->
        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Apply to</label>
            <div class="mw-segmented mw-ese-seg">
                <span v-for="p in borderPositionOptions" :key="p.key" class="mw-segmented__cell"
                      :class="{ 'active': borderPosition === p.key, 'is-active': borderPosition === p.key }"
                      role="button" tabindex="0"
                      :aria-pressed="borderPosition === p.key ? 'true' : 'false'"
                      @click="borderPosition = p.key"
                      @keydown.enter.prevent="borderPosition = p.key"
                      @keydown.space.prevent="borderPosition = p.key">{{ p.value }}</span>
            </div>
        </div>

        <!-- Style — primary line styles (rare ones under More options). -->
        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Style</label>
            <div class="mw-segmented mw-ese-seg">
                <span v-for="s in borderStylePrimary" :key="s.key" class="mw-segmented__cell"
                      :class="{ 'active': activeStyleValue === s.key, 'is-active': activeStyleValue === s.key }"
                      role="button" tabindex="0"
                      :aria-pressed="activeStyleValue === s.key ? 'true' : 'false'"
                      @click="setActiveStyle(s.key)"
                      @keydown.enter.prevent="setActiveStyle(s.key)"
                      @keydown.space.prevent="setActiveStyle(s.key)">{{ s.value }}</span>
            </div>
        </div>

        <!-- Width — preset widths (exact px slider under More options). -->
        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Width</label>
            <div class="mw-segmented mw-ese-seg">
                <span v-for="w in borderWidthPresets" :key="w.key" class="mw-segmented__cell"
                      :class="{ 'active': Number(activeWidthValue) === w.px, 'is-active': Number(activeWidthValue) === w.px }"
                      role="button" tabindex="0"
                      :aria-pressed="Number(activeWidthValue) === w.px ? 'true' : 'false'"
                      @click="setActiveWidth(w.px)"
                      @keydown.enter.prevent="setActiveWidth(w.px)"
                      @keydown.space.prevent="setActiveWidth(w.px)">{{ w.value }}</span>
            </div>
        </div>

        <!-- Color — palette swatches + Custom (MW picker), scoped to the side. -->
        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Color</label>
            <div class="mw-ese-swatches">
                <button v-for="sw in colorSwatches" :key="sw" type="button" class="mw-ese-swatch"
                        :class="{ 'is-active': isColorActive(sw) }"
                        :style="{ backgroundColor: sw }" :title="sw" :aria-label="'Color ' + sw"
                        @click="setActiveColor(sw)"></button>
                <span class="mw-ese-swatches__spacer"></span>
                <button type="button" class="mw-ese-custom-link" @click="openCustomColor($event)">Custom</button>
            </div>
        </div>

        <details class="mw-typography-advanced">
            <summary class="cursor-pointer text-xs opacity-70 hover:opacity-100 py-2">
                More options
            </summary>

            <DropdownSmall v-model="activeStyleModel" :options="borderStylesMore" label="More border styles"/>

            <SliderSmall label="Exact width" v-model="activeWidthModel" :min="0" :max="30" :step="1"></SliderSmall>

            <ImagePicker label="Border image" v-model="borderImage" v-bind:file="borderImageUrl"
                         @change="handleBorderImageChange"/>
        </details>

    </div>

</template>

<script>
mw.require('css_parser.js');
import Input from '../../components/Form/Input.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "./components/FontPicker.vue";
import ColorPicker from "./components/ColorPicker.vue";
import DropdownSmall from "./components/DropdownSmall.vue";
import SliderSmall from "./components/SliderSmall.vue";
import ImagePicker from './components/ImagePicker.vue';

import Slider from '@vueform/slider';

export default {

    components: {Dropdown, Input, FontPicker, ColorPicker, Slider, DropdownSmall, SliderSmall, ImagePicker},

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

            // LE redesign — primary (segmented) line styles; the 5 rare styles
            // stay reachable via the "More border styles" dropdown under More.
            'borderStylePrimary': [
                {"key": "none", "value": "None"},
                {"key": "solid", "value": "Solid"},
                {"key": "dashed", "value": "Dashed"},
                {"key": "dotted", "value": "Dotted"},
            ],
            'borderStylesMore': [
                {"key": "double", "value": "Double"},
                {"key": "groove", "value": "Groove"},
                {"key": "ridge", "value": "Ridge"},
                {"key": "inset", "value": "Inset"},
                {"key": "outset", "value": "Outset"},
            ],
            'borderWidthPresets': [
                {"key": "none", "value": "None", "px": 0},
                {"key": "s", "value": "S", "px": 1},
                {"key": "m", "value": "M", "px": 3},
                {"key": "l", "value": "L", "px": 5},
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

    computed: {
        // LE redesign — the "Apply to" side scope. '' = all; else 'Top'/'Right'/
        // 'Bottom'/'Left' so the single Style/Width/Color controls route to the
        // matching per-side data prop (which already has an apply watcher).
        _sideSuffix: function () {
            var p = this.borderPosition;
            if (!p || p === 'all') return '';
            return p.charAt(0).toUpperCase() + p.slice(1);
        },
        activeStyleValue: function () { return this['borderStyle' + this._sideSuffix]; },
        activeWidthValue: function () { return this['borderSize' + this._sideSuffix]; },
        activeColorValue: function () { return this['borderColor' + this._sideSuffix]; },
        // v-model proxies for the More-options dropdown + slider.
        activeStyleModel: {
            get: function () { return this['borderStyle' + this._sideSuffix]; },
            set: function (v) { this['borderStyle' + this._sideSuffix] = v; },
        },
        activeWidthModel: {
            get: function () { return this['borderSize' + this._sideSuffix]; },
            set: function (v) { this['borderSize' + this._sideSuffix] = v; },
        },
        // Recommended swatches from the SAME MW color-palette service.
        colorSwatches: function () {
            // eslint-disable-next-line no-unused-vars
            var _dep = this.activeNode;
            try {
                var mgr = mw.top().app.templateSettings
                    && mw.top().app.templateSettings.colorPaletteManager;
                if (mgr && mgr.getColors) {
                    var colors = mgr.getColors() || [];
                    var seen = {};
                    var filtered = colors.filter(function (c) {
                        if (!c || typeof c !== 'string') return false;
                        if (!/^#([0-9a-fA-F]{3,8})$/.test(c)) return false;
                        var low = c.toLowerCase();
                        if (seen[low]) return false;
                        seen[low] = true;
                        return true;
                    });
                    if (filtered.length) return filtered.slice(0, 6);
                }
            } catch (e) { /* fall through */ }
            return ['#182433', '#6b6b64', '#f0a06a', '#d98c4a', '#ffffff'];
        },
    },

    methods: {
        toggleBorder: function () {
            this.showBorder = !this.showBorder;
            this.emitter.emit('element-style-editor-show', 'border');
        },
        // LE redesign — segmented/swatch setters route to the scoped side.
        setActiveStyle: function (key) { this['borderStyle' + this._sideSuffix] = key; },
        setActiveWidth: function (px) { this['borderSize' + this._sideSuffix] = px; },
        setActiveColor: function (hex) { this['borderColor' + this._sideSuffix] = hex; },
        isColorActive: function (sw) {
            var v = this.activeColorValue;
            if (!v) return false;
            return String(v).replace(/\s/g, '').toLowerCase()
                === String(sw).replace(/\s/g, '').toLowerCase();
        },
        openCustomColor: function (event) {
            var el = event && event.currentTarget ? event.currentTarget : null;
            var current = this.activeColorValue || '#182433';
            var self = this;
            var picker = (typeof mw !== 'undefined' && mw.app && mw.app.colorPicker)
                ? mw.app.colorPicker
                : ((typeof mw !== 'undefined' && mw.top && mw.top().app && mw.top().app.colorPicker)
                    ? mw.top().app.colorPicker : null);
            if (picker && picker.openColorPicker) {
                picker.openColorPicker(current, function (color) { self.setActiveColor(color); }, el);
            }
        },
        handleBorderImageChange: function (url) {
            if (url && url !== '' && url !== 'none' && url !== 'inherit' && url !== 'initial') {
                this.borderImageUrl = url;
            } else {
                this.borderImageUrl = '';
            }
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
