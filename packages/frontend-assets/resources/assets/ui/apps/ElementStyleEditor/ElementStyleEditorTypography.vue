<template>

    <div class="d-flex">
        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
             style="enable-background:new 0 0 24 24;" xml:space="preserve" aria-hidden="true">
            <path
                d="M21,7V3h-4v1H7V3H3v4h1v10H3v4h4v-1h10v1h4v-4h-1V7H21 M18,4h2v2h-2V4 M4,4h2v2H4V4 M6,20H4v-2h2V20 M20,20h-2v-2h2V20   M18,17h-1v1H7v-1H6V7h1V6h10v1h1V17 M16,8v2h-3v6h-2v-6H8V8H16z"></path>
        </svg>

        <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showTypography }">
            Typography
        </span>
    </div>
    <!-- task-2026-05-16-ea56d3: @click.stop prevents inner-control
         clicks (Align icons, sliders, color picker, etc.) from
         bubbling up to the wrapper's @click="toggleTypography" in
         ElementStyleEditorApp.vue and inadvertently closing this
         accordion when the user interacts with a field. -->
    <div v-if="showTypography" @click.stop>
        <div>

            <!-- LE redesign (frame 1c) — curated primary controls in order:
                 Level (headings) · Size · Align · Color · Space around.
                 Everything else moves under "More options". -->

            <!-- Level (H1-H4) — headings only; changes the element tag. -->
            <div v-if="isHeading" class="form-control-live-edit-label-wrapper">
                <label class="live-edit-label">Level</label>
                <div class="mw-segmented mw-ese-seg">
                    <span v-for="lv in ['h1','h2','h3','h4']" :key="lv"
                          class="mw-segmented__cell"
                          :class="{ 'active': currentTag === lv, 'is-active': currentTag === lv }"
                          role="button" tabindex="0"
                          :aria-label="'Level ' + lv.toUpperCase()"
                          :aria-pressed="currentTag === lv ? 'true' : 'false'"
                          @click="setLevel(lv)"
                          @keydown.enter.prevent="setLevel(lv)" @keydown.space.prevent="setLevel(lv)">
                        {{ lv.toUpperCase() }}
                    </span>
                </div>
            </div>

            <!-- Size S/M/L/XL segmented (exact px under More options). -->
            <div class="form-control-live-edit-label-wrapper mw-ese-size">
                <label class="live-edit-label">Size</label>
                <div class="mw-segmented mw-ese-size__seg">
                    <span v-for="s in sizePresets" :key="s.key"
                          class="mw-segmented__cell mw-ese-size__cell"
                          :class="{ 'active': isSizeActive(s.px), 'is-active': isSizeActive(s.px) }"
                          role="button" tabindex="0"
                          :aria-label="'Size ' + s.label"
                          :aria-pressed="isSizeActive(s.px) ? 'true' : 'false'"
                          @click="setSizePreset(s.px)"
                          @keydown.enter.prevent="setSizePreset(s.px)"
                          @keydown.space.prevent="setSizePreset(s.px)">
                        {{ s.label }}
                    </span>
                </div>
            </div>

            <!-- Align -->
            <Align :textAlign="textAlign" @update:textAlign="setTextAlignment"/>

            <!-- Color — preset swatch row + Custom (native picker). -->
            <div class="form-control-live-edit-label-wrapper">
                <label class="live-edit-label">Color</label>
                <div class="mw-ese-swatches">
                    <button v-for="sw in colorSwatches" :key="sw" type="button"
                            class="mw-ese-swatch"
                            :class="{ 'is-active': isColorActive(sw) }"
                            :style="{ backgroundColor: sw }"
                            :aria-label="'Color ' + sw"
                            :title="sw"
                            @click="color = sw"></button>
                    <span class="mw-ese-swatches__spacer"></span>
                    <button type="button" class="mw-ese-custom-link" @click="openCustomColor">Custom</button>
                    <input ref="customColorInput" type="color" class="mw-ese-color-native"
                           v-model="color" tabindex="-1" aria-hidden="true"/>
                </div>
            </div>

            <!-- Space around (margin) None/S/M/L segmented. -->
            <div class="form-control-live-edit-label-wrapper">
                <label class="live-edit-label">Space around</label>
                <div class="mw-segmented mw-ese-seg">
                    <span v-for="sp in spacePresets" :key="sp.key"
                          class="mw-segmented__cell"
                          :class="{ 'active': isSpaceActive(sp.px), 'is-active': isSpaceActive(sp.px) }"
                          role="button" tabindex="0"
                          :aria-label="'Space around ' + sp.label"
                          :aria-pressed="isSpaceActive(sp.px) ? 'true' : 'false'"
                          @click="setSpaceAround(sp.px)"
                          @keydown.enter.prevent="setSpaceAround(sp.px)" @keydown.space.prevent="setSpaceAround(sp.px)">
                        {{ sp.label }}
                    </span>
                </div>
            </div>


            <details class="mw-typography-advanced">
                <summary class="cursor-pointer text-xs opacity-70 hover:opacity-100 py-2">
                    More options
                </summary>

                <SliderSmall
                    label="Exact size (px)"
                    v-model="fontSize"
                    :min="0"
                    :max="100"
                    :step="1"
                ></SliderSmall>

                <div class="my-4">
                    <FontPicker v-model="fontFamily" v-bind:value=fontFamily @change="handleFontChange" :label="'Family'"/>
                </div>

                <DropdownSmall v-model="fontWeight" :options="fontWeightOptions" :label="'Boldness'"/>
                <DropdownSmall v-model="textTransform" :options="textTransformOptions" :label="'Letter case'"/>
                <!--
                  task-2026-05-05-854d66 (QW3) — Italic toggle (binary state as a
                  toggle button, not a select). task-2026-05-16-f69d54 migrated it
                  to the MwToolButton toggle primitive; .mw-italic-toggle kept for
                  back-compat. Moved under "More options" for the frame-1c curation.
                -->
                <div class="form-control-live-edit-label-wrapper my-4 d-flex justify-content-between align-items-center">
                    <label class="live-edit-label">Italic</label>
                    <button
                        type="button"
                        class="mw-italic-toggle mw-tool-btn mw-tool-btn--toggle"
                        :class="{ 'active': fontStyle === 'italic', 'is-active': fontStyle === 'italic' }"
                        :aria-pressed="fontStyle === 'italic'"
                        title="Italic"
                        @click="fontStyle = (fontStyle === 'italic' ? 'normal' : 'italic')">
                        <em>I</em>
                    </button>
                </div>

                <SliderSmall
                    label="Line height"
                    v-model="lineHeight"
                    :min="0"
                    :max="100"
                    :step="1"
                ></SliderSmall>

                <SliderSmall
                    label="Space between letters"
                    v-model="letterSpacing"
                    :min="1"
                    :max="100"
                    :step="1"
                ></SliderSmall>

                <SliderSmall
                    label="Space between words"
                    v-model="wordSpacing"
                    :min="1"
                    :max="100"
                    :step="1"
                ></SliderSmall>

                <DropdownSmall v-model="textWritingMode" :options="textWritingModeOptions" :label="'Writing direction'"/>

                <div v-if="textWritingMode !== 'horizontal-tb' && textWritingMode !== ''">
                    <DropdownSmall v-model="textOrientation" :options="textOrientationOptions" :label="'Text orientation'"/>
                </div>
            </details>



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
    computed: {
        // LE redesign (frame 1c) — Level control visibility + active tag.
        isHeading: function () {
            return this.activeNode ? /^h[1-6]$/i.test(this.activeNode.tagName || '') : false;
        },
        currentTag: function () {
            return this.activeNode ? (this.activeNode.tagName || '').toLowerCase() : '';
        },
    },
    data() {
        return {
            'showTypography': false,
            'activeNode': null,
            'isReady': false,
            // LE redesign (frame 1c) — Size preset scale (px).
            'sizePresets': [
                {"key": "s", "label": "S", "px": 20},
                {"key": "m", "label": "M", "px": 28},
                {"key": "l", "label": "L", "px": 40},
                {"key": "xl", "label": "XL", "px": 56},
            ],
            // LE redesign (frame 1c) — Color preset swatches + Space-around scale.
            'colorSwatches': ['#182433', '#6b6b64', '#f0a06a', '#d98c4a', '#ffffff'],
            'spacePresets': [
                {"key": "none", "label": "None", "px": 0},
                {"key": "s", "label": "S", "px": 8},
                {"key": "m", "label": "M", "px": 16},
                {"key": "l", "label": "L", "px": 32},
            ],
            'spaceAround': null,
            'textTransformOptions': [
                {"key": 'none', "value": "None"},
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

            ],

            "textOrientationOptions": [
                {"key": "normal", "value": ""},
                {"key": "initial", "value": "Initial"},
                {"key": "inherit", "value": "Inherit"},
                {"key": "mixed", "value": "Mixed"},
                {"key": "upright", "value": "Upright"},
                {"key": "sideways", "value": "Sideways"},
                {"key": "sideways-right", "value": "Sideways Right"}

            ],
            "textWritingModeOptions": [
                {"key": "normal", "value": ""},
                {"key": "horizontal-tb", "value": "Horizontal"},
                {"key": "vertical-rl", "value": "Vertical"},
                // {"key": "vertical-lr", "value": "Vertical Reversed"},
                // {"key": "sideways-rl", "value": "Sideways"},
                // {"key": "sideways-lr", "value": "Sideways Reversed"},

            ],
            'textAlign': null,
            'fontSize': null,
            'fontWeight': 'normal',
            'fontStyle': 'normal',
            'lineHeight': null,
            'fontFamily': null,
            'letterSpacing': null,
            'wordSpacing': null,

            'color': null,
            'textTransform': 'none',
            'textOrientation': null,
            'textWritingMode': null,
            'textDecorationIsBold': null,
            'textDecorationIsItalic': null,
            'textDecorationIsUnderline': null,
            'textDecorationIsStrikethrough': null,
        };
    },

    methods: {
        toggleTypography() {
            this.showTypography = !this.showTypography;
            this.emitter.emit('element-style-editor-show', 'typography');
        },
        resetAllProperties: function () {
            this.fontSize = null;
            this.fontWeight = 'normal';
            this.fontStyle = 'normal';
            this.lineHeight = null;
            this.fontFamily = null;
            this.color = null;
            this.textTransform = 'none';
            this.textDecorationIsBold = null;
            this.textDecorationIsItalic = null;
            this.textDecorationIsUnderline = null;
            this.textDecorationIsStrikethrough = null;
            this.textOrientation = null;
            this.letterSpacing = null;
            this.wordSpacing = null;
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
                this.populateLetterSpacing(css);
                this.populateWordSpacing(css);
                this.populateTextOrientation(css);
                this.populateTextWritingMode(css)


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },

        populateLetterSpacing: function (css) {
            if (!css || !css.get) return;

            var letterSpacing = css.get.letterSpacing();
            this.letterSpacing = letterSpacing;
        },
        populateTextOrientation: function (css) {
            if (!css || !css.get) return;
            var orientation = css.get.textOrientation();
            this.textOrientation = orientation;
        },

        populateWordSpacing: function (css) {
            if (!css || !css.get) return;

            var letterSpacing = css.get.wordSpacing();
            this.wordSpacing = letterSpacing;
        },
        populateTextWritingMode: function (css) {
            if (!css || !css.get) return;
            var writingMode = css.get.textWritingMode();
            this.textWritingMode = writingMode;
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
        // LE redesign (frame 1c) — Size segmented presets.
        setSizePreset: function (px) {
            this.fontSize = px;
        },
        isSizeActive: function (px) {
            return parseInt(this.fontSize, 10) === px;
        },
        // LE redesign (frame 1c) — Level (heading tag swap).
        setLevel: function (tag) {
            const el = this.activeNode;
            if (!el || !/^h[1-6]$/i.test(el.tagName) || el.tagName.toLowerCase() === tag) return;
            const doc = el.ownerDocument;
            const newEl = doc.createElement(tag);
            Array.from(el.attributes).forEach((a) => {
                try { newEl.setAttribute(a.name, a.value); } catch (e) { /* noop */ }
            });
            newEl.innerHTML = el.innerHTML;
            el.replaceWith(newEl);
            this.activeNode = newEl;
            try { mw.top().app.registerChange(newEl); } catch (e) { /* noop */ }
            try { mw.top().app.dispatch('mw.elementStyleEditor.selectNode', newEl); } catch (e) { /* noop */ }
        },
        // LE redesign (frame 1c) — Color swatches + Custom.
        isColorActive: function (sw) {
            if (!this.color) return false;
            return String(this.color).replace(/\s/g, '').toLowerCase()
                === String(sw).replace(/\s/g, '').toLowerCase();
        },
        openCustomColor: function () {
            const inp = this.$refs.customColorInput;
            if (inp && inp.click) inp.click();
        },
        // LE redesign (frame 1c) — Space around (margin) presets.
        setSpaceAround: function (px) {
            this.spaceAround = px;
            this.applyPropertyToActiveNode('margin', px + 'px');
        },
        isSpaceActive: function (px) {
            return this.spaceAround === px;
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
            if (elementStyleEditorShow !== 'typography') {
                this.showTypography = false;
            }
        });

        // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
        //     this.populateStyleEditor(element)
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


        letterSpacing: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('letterSpacing', newValue + 'px');
        },

        wordSpacing: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('wordSpacing', newValue + 'px');
        },

        textOrientation: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('textOrientation', newValue);
        },

        textWritingMode: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('writingMode', newValue);
        },
    },


}
</script>


