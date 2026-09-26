<template>

    <div class="d-flex">
        <!-- task-2026-05-22-cd4d21 / AI-915 — replace meaningless filled-circle
             with swatch icon (heroicon-o-swatch equivalent, stroke-based). -->
        <svg fill="none" height="24" width="24" viewBox="0 0 24 24" stroke="currentColor"
             stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"
             xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            <path d="M9.53 16.122a3 3 0 0 0-5.78 1.128 2.25 2.25 0 0 1-2.4 2.245 4.5 4.5 0 0 0 8.4-2.245c0-.399-.078-.78-.22-1.128Zm0 0a15.998 15.998 0 0 0 3.097-1.293M12 14.25l.293-1.048a4.5 4.5 0 0 1 3.003-3.005l.78-.26L18.187 6.375a2.25 2.25 0 0 1 3.183 3.183l-3.674 3.674a4.5 4.5 0 0 1-2.003 1.139l-1.041.294a15.998 15.998 0 0 1-1.293 3.097M12 14.25c0-1.243.438-2.383 1.165-3.265M6.75 21.75a2.25 2.25 0 1 1 0-4.5 2.25 2.25 0 0 1 0 4.5Z"/>
        </svg>
        <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showBackground }">
            Background
        </span>
    </div>

    <!-- task-2026-05-16-ea56d3: @click.stop — see ElementStyleEditorTypography.vue -->
    <!-- LE redesign (frame 1c) — Color (swatch row + None + Custom) and Image
         lead; the image-shaping controls (Size, Position) appear only when an
         image is set; Repeat / Crop area / Blend mode fold into More options. -->
    <div v-if="showBackground" @click.stop>

        <!-- Color — None (bg-transparent) + palette swatches + Custom (MW picker). -->
        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Color</label>
            <div class="mw-ese-swatches">
                <button type="button" class="mw-ese-swatch mw-ese-swatch--none"
                        :class="{ 'is-active': isNoneActive }"
                        aria-label="No background color" title="None"
                        @click="selectNoBackground"></button>
                <button v-for="sw in colorSwatches" :key="sw" type="button"
                        class="mw-ese-swatch"
                        :class="{ 'is-active': isColorActive(sw) }"
                        :style="{ backgroundColor: sw }"
                        :aria-label="'Color ' + sw"
                        :title="sw"
                        @click="selectColor(sw)"></button>
                <span class="mw-ese-swatches__spacer"></span>
                <button type="button" class="mw-ese-custom-link" @click="openCustomColor($event)">Custom</button>
            </div>
        </div>

        <ImagePicker label="Image" v-model="backgroundImage" v-bind:file="backgroundImageUrl"
                     @change="handleBackgroundImageChange"/>

        <!-- Size — segmented (image only). -->
        <div class="form-control-live-edit-label-wrapper" v-if="hasBackgroundImage">
            <label class="live-edit-label">Size</label>
            <div class="mw-segmented mw-ese-seg">
                <span v-for="s in backgroundSizeOptions" :key="s.key" class="mw-segmented__cell"
                      :class="{ 'active': backgroundSize === s.key, 'is-active': backgroundSize === s.key }"
                      role="button" tabindex="0"
                      :aria-pressed="backgroundSize === s.key ? 'true' : 'false'"
                      @click="backgroundSize = s.key"
                      @keydown.enter.prevent="backgroundSize = s.key"
                      @keydown.space.prevent="backgroundSize = s.key">{{ s.value }}</span>
            </div>
        </div>

        <!-- Position — 3×3 anchor grid + None (image only). -->
        <div class="form-control-live-edit-label-wrapper" v-if="hasBackgroundImage">
            <label class="live-edit-label">Position</label>
            <div class="mw-ese-position">
                <div class="mw-ese-position-grid">
                    <button v-for="p in backgroundPositionAnchors" :key="p.key" type="button"
                            class="mw-ese-position-grid__dot"
                            :class="{ 'is-active': backgroundPosition === p.key }"
                            :aria-label="p.value" :title="p.value"
                            @click="backgroundPosition = p.key"></button>
                </div>
                <button type="button" class="mw-ese-custom-link"
                        :class="{ 'is-active': !backgroundPosition }"
                        @click="backgroundPosition = null">None</button>
            </div>
        </div>

        <details class="mw-typography-advanced">
            <summary class="cursor-pointer text-xs opacity-70 hover:opacity-100 py-2">
                More options
            </summary>

            <DropdownSmall v-model="backgroundRepeat" :options="backgroundRepeatOptions" :label="'Repeat'"/>

            <DropdownSmall v-model="backgroundClip" :options="backgroundClipOptions"
                           :label="'Crop area'"/>

            <DropdownSmall v-model="mixBlendMode" :options="mixBlendModeOptions" :label="'Blend mode'"/>
        </details>


    </div>
</template>

<script>
import Input from '../../components/Form/Input.vue';
import ImagePicker from './components/ImagePicker.vue';
import Dropdown from '../../components/Form/Dropdown.vue';
import FontPicker from "./components/FontPicker.vue";
import ColorPicker from "./components/ColorPicker.vue";
import DropdownSmall from "./components/DropdownSmall.vue";
import Slider from '@vueform/slider';
import FilePicker from "../../components/Form/FilePicker.vue";

export default {

    components: {ColorPicker, FontPicker, Dropdown, Input, Slider, FilePicker, ImagePicker, DropdownSmall},

    data() {
        return {
            'showBackground': false,
            'backgroundPositionOptions': [
                {key: null, value: "None"},
                {key: "0% 0%", value: "Left Top"},
                {key: "50% 0%", value: "Center Top"},
                {key: "100% 0%", value: "Right Top"},
                {key: "0% 50%", value: "Left Center"},
                {key: "50% 50%", value: "Center Center"},
                {key: "100% 50%", value: "Right Center"},
                {key: "0% 100%", value: "Left Bottom"},
                {key: "50% 100%", value: "Center Bottom"},
                {key: "100% 100%", value: "Right Bottom"}
            ],
            'backgroundRepeatOptions': [
                {key: null, value: "None"},
                {key: "repeat", value: "Repeat"},
                {key: "no-repeat", value: "No Repeat"},
                {key: "repeat-x", value: "Repeat Horizontally"},
                {key: "repeat-y", value: "Repeat Vertically"}
            ],
            'backgroundClipOptions': [
                {key: null, value: "None"},
                {key: "border-box", value: "Border Box"},
                {key: "content-box", value: "Content Box"},
                {key: "text", value: "Text"}
            ],
            'backgroundSizeOptions': [
                {key: "auto", value: "Auto"},
                {key: "contain", value: "Fit"},
                {key: "cover", value: "Cover"},
                {key: "100% 100%", value: "Scale"}
            ],

            'mixBlendModeOptions': [
                {key: "normal", value: "Normal"},
                {key: "multiply", value: "Multiply"},
                {key: "screen", value: "Screen"},
                {key: "overlay", value: "Overlay"},
                {key: "darken", value: "Darken"},
                {key: "lighten", value: "Lighten"},
                {key: "color-dodge", value: "Color Dodge"},
                {key: "color-burn", value: "Color Burn"},
                {key: "hard-light", value: "Hard Light"},
                {key: "soft-light", value: "Soft Light"},
                {key: "difference", value: "Difference"},
                {key: "exclusion", value: "Exclusion"},
                {key: "hue", value: "Hue"},
                {key: "saturation", value: "Saturation"},
                {key: "color", value: "Color"},
                {key: "luminosity", value: "Luminosity"},
                {key: "plus-darker", value: "Plus Darker"},
                {key: "plus-lighter", value: "Plus Lighter"},
            ],

            'activeNode': null,
            'isReady': false,
            'backgroundImage': null,
            'backgroundColor': null,
            'backgroundPosition': null,
            'backgroundRepeat': null,
            'backgroundSize': 'auto',
            'backgroundImageUrl': null,
            'backgroundClip': null,
            'mixBlendMode': null,
        };
    },

    computed: {
        // LE redesign — image-shaping controls (Size/Position) show only when a
        // background image is set.
        hasBackgroundImage: function () {
            var u = this.backgroundImageUrl;
            return !!(u && u !== '' && u !== 'none' && u !== 'inherit' && u !== 'initial');
        },
        // 9 real anchor points for the 3×3 position grid (drops the null "None").
        backgroundPositionAnchors: function () {
            return this.backgroundPositionOptions.filter(function (o) { return o.key !== null; });
        },
        // None (bg-transparent) is active when the class is present and no color set.
        isNoneActive: function () {
            // eslint-disable-next-line no-unused-vars
            var _dep = this.activeNode;
            var hasClass = false;
            try { hasClass = !!(this.activeNode && this.activeNode.classList.contains('bg-transparent')); } catch (e) {}
            return hasClass && !this.backgroundColor;
        },
        // LE redesign — recommended swatches from the SAME service the MW color
        // picker uses (site colors + palette memory); refreshes per selection.
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
                        // Accept hex AND rgb()/rgba()/hsl()/hsla(): a custom
                        // colour picked via the MW picker is often stored in an
                        // rgb() form, and a hex-only test silently dropped it so
                        // the picked colour never appeared as a suggestion swatch.
                        if (!/^#([0-9a-fA-F]{3,8})$/.test(c)
                            && !/^rgba?\(/i.test(c)
                            && !/^hsla?\(/i.test(c)) return false;
                        var low = c.replace(/\s+/g, '').toLowerCase();
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
        // LE redesign (frame 1c) — Color swatches + None + Custom.
        isColorActive: function (sw) {
            if (!this.backgroundColor) return false;
            return String(this.backgroundColor).replace(/\s/g, '').toLowerCase()
                === String(sw).replace(/\s/g, '').toLowerCase();
        },
        selectColor: function (sw) {
            // strip the bg-transparent None affordance (its !important would outrank)
            try { if (this.activeNode) this.activeNode.classList.remove('bg-transparent'); } catch (e) {}
            this.backgroundColor = sw; // watcher applies backgroundColor
            try { if (this.activeNode) mw.top().app.registerChange(this.activeNode); } catch (e) {}
        },
        selectNoBackground: function () {
            var node = this.activeNode;
            if (node) {
                try { node.classList.add('bg-transparent'); } catch (e) {}
            }
            this.backgroundColor = null; // watcher clears the applied color
            this.applyPropertyToActiveNode('backgroundColor', '');
            try { if (node) mw.top().app.registerChange(node); } catch (e) {}
        },
        // Open the Microweber color picker anchored to the Custom button; the
        // callback routes through selectColor (strips bg-transparent + applies).
        openCustomColor: function (event) {
            var el = event && event.currentTarget ? event.currentTarget : null;
            var current = this.backgroundColor || '#ffffff';
            var self = this;
            var picker = (typeof mw !== 'undefined' && mw.app && mw.app.colorPicker)
                ? mw.app.colorPicker
                : ((typeof mw !== 'undefined' && mw.top && mw.top().app && mw.top().app.colorPicker)
                    ? mw.top().app.colorPicker : null);
            if (picker && picker.openColorPicker) {
                picker.openColorPicker(current, function (color) { self.selectColor(color); }, el);
            }
        },

        toggleBackground: function () {
            // this.showBackground = !this.showBackground;
            //    this.emitter.emit('element-style-editor-show', 'background');
            if (!this.showBackground) {
                this.emitter.emit('element-style-editor-show', 'background');
            } else {
                this.emitter.emit('element-style-editor-show', 'none');
            }
        },

        resetAllProperties: function () {
            this.backgroundImage = null;
            this.backgroundImageUrl = null;
            this.backgroundColor = null;
            this.backgroundPosition = null;
            this.backgroundRepeat = null;
            this.backgroundSize = 'auto';
            this.backgroundClip = null;
            this.mixBlendMode = null;
        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                this.populateCssBackground(css);
                this.populateCssMixBlendMode(css);


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },
        populateCssBackground: function (css) {
            if (!css || !css.get) return;
            var bg = css.get.background();

            if (bg.image) {
                if (bg.image.indexOf('url(') !== -1) {
                    this.backgroundImageUrl = bg.image.replace('url(', '').replace(')', '');
                    //also replace "
                    this.backgroundImageUrl = this.backgroundImageUrl.replace(/\"/g, "");
                }
            }

            if (bg.size) {
                this.backgroundSize = bg.size;
            } else {
                this.backgroundSize = 'auto';
            }

            this.backgroundImage = bg.image;
            this.backgroundColor = bg.color;
            this.backgroundPosition = bg.position;
            this.backgroundRepeat = bg.repeat;
            this.backgroundClip = bg.clip;
        },

        populateCssMixBlendMode: function (css) {
            if (!css || !css.get) return;
            var bg = css.get.mixBlendMode();
            if (bg.mixBlendMode) {
                this.mixBlendMode = bg.mixBlendMode;
            }
        },

        handleBackgroundColorChange: function (color) {

            if (typeof (color) != 'string') {
                return;
            }
            this.backgroundColor = color
        },
        handleBackgroundImageChange: function (url) {
            var urlVal = url;
            if (url && url != '' && url != 'none' && url != 'inherit' && url != 'initial') {
                //check if contain url(
                this.backgroundImageUrl = url;
                if (url.indexOf('url(') === -1) {
                    urlVal = 'url(' + url + ')';
                }
            } else {
                this.backgroundImageUrl = '';
            }
            if (urlVal == null) {
                urlVal = 'none';
            }
            this.backgroundImage = urlVal;
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
            if (elementStyleEditorShow !== 'background') {
                this.showBackground = false;
            } else {
                this.showBackground = true;

            }

        });

        // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
        //
        //     this.populateStyleEditor(element)
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


        // Background-related property watchers
        backgroundImage: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('backgroundImage', newValue);
        },
        backgroundClip: function (newValue, oldValue) {
            if (newValue == 'text') {
                this.applyPropertyToActiveNode('backgroundClip', 'text');
                this.applyPropertyToActiveNode('-webkitBackgroundClip', 'text');
                this.applyPropertyToActiveNode('color', 'rgba(0,0,0,0)');
            } else {
                this.applyPropertyToActiveNode('backgroundClip', newValue);
                this.applyPropertyToActiveNode('-webkitBackgroundClip', newValue);
                this.applyPropertyToActiveNode('color', '');

            }
        },
        backgroundColor: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('backgroundColor', newValue);
        },
        backgroundPosition: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('backgroundPosition', newValue);
        },
        backgroundRepeat: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('backgroundRepeat', newValue);
        },
        backgroundSize: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('backgroundSize', newValue);
        },
        mixBlendMode: function (newValue, oldValue) {
            this.applyPropertyToActiveNode('mixBlendMode', newValue);
        },
    },
}
</script>
