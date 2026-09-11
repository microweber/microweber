<template>
    <div>
        <!-- LE redesign (frame 1c) — Depth is a segmented applying fixed inline
             text-shadow strings (text-shadow has no Bootstrap utility), plus a
             Color swatch row. Fine H/V/Blur sliders + the full preset grid fold
             into More options. -->
        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Depth</label>
            <div class="mw-segmented mw-ese-seg">
                <span v-for="d in textDepthPresets" :key="d.key" class="mw-segmented__cell"
                      :class="{ 'active': activeTextDepth === d.key, 'is-active': activeTextDepth === d.key }"
                      role="button" tabindex="0"
                      :aria-pressed="activeTextDepth === d.key ? 'true' : 'false'"
                      @click="setTextDepth(d)"
                      @keydown.enter.prevent="setTextDepth(d)"
                      @keydown.space.prevent="setTextDepth(d)">{{ d.label }}</span>
            </div>
        </div>

        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Color</label>
            <div class="mw-ese-swatches">
                <button v-for="sw in colorSwatches" :key="sw" type="button" class="mw-ese-swatch"
                        :class="{ 'is-active': isColorActive(sw) }"
                        :style="{ backgroundColor: sw }" :title="sw" :aria-label="'Shadow color ' + sw"
                        @click="setTextColor(sw)"></button>
                <span class="mw-ese-swatches__spacer"></span>
                <button type="button" class="mw-ese-custom-link" @click="openCustomColor($event)">Custom</button>
            </div>
        </div>

        <details class="mw-typography-advanced">
            <summary class="cursor-pointer text-xs opacity-70 hover:opacity-100 py-2">
                More options
            </summary>
            <div class="text-shadow-options">
                <!-- fine numeric control (active once a shadow is set) -->
                <div v-if="selectedShadow === 'custom' || activeTextDepth && activeTextDepth !== 'none'" class="form-group">
                    <SliderSmall label="Horizontal offset" v-model="textShadowOptions.horizontalLength" :min="-300"
                                 :max="300" :step="1" :default="0"></SliderSmall>
                    <SliderSmall label="Vertical offset" v-model="textShadowOptions.verticalLength" :min="-300"
                                 :max="300" :step="1" :default="0"></SliderSmall>
                    <SliderSmall label="Blur" v-model="textShadowOptions.blurRadius" :min="0" :max="30"
                                 :step="1" :default="0"></SliderSmall>
                </div>

                <!-- Predefined Shadows -->
                <PredefinedTextShadowsSelect :predefinedShadows="predefinedShadows"
                                             :selectedShadow="selectedShadow"
                                             @update:selectedShadow="handleShadowChange"/>
            </div>
        </details>
    </div>
</template>

<script>
import ColorPicker from "./components/ColorPicker.vue";
import SliderSmall from "./components/SliderSmall.vue";
import PredefinedTextShadowsSelect from "./components/PredefinedTextShadowsSelect.vue";

export default {
    components: {ColorPicker, SliderSmall, PredefinedTextShadowsSelect},

    data() {
        var predefinedShadows = mw.top().app.templateSettings.getPredefinedTextShadows();

        return {
            activeNode: null,
            isReady: false,
            selectedShadow: '',
            predefinedShadows: predefinedShadows,
            textShadowOptions: {
                horizontalLength: "",
                verticalLength: "",
                blurRadius: "",
                shadowColor: "",
            },
            // LE redesign — fixed inline Depth strings for text-shadow.
            textDepthPresets: [
                {key: 'none', label: 'None', h: 0, v: 0, blur: 0},
                {key: 's', label: 'S', h: 1, v: 1, blur: 2},
                {key: 'm', label: 'M', h: 2, v: 2, blur: 4},
                {key: 'l', label: 'L', h: 3, v: 3, blur: 6},
            ],
        };
    },

    computed: {
        activeTextDepth: function () {
            var h = parseInt(this.textShadowOptions.horizontalLength, 10) || 0;
            var v = parseInt(this.textShadowOptions.verticalLength, 10) || 0;
            var b = parseInt(this.textShadowOptions.blurRadius, 10) || 0;
            if (!h && !v && !b) return this.selectedShadow === '' ? 'none' : null;
            var f = this.textDepthPresets.find(function (p) {
                return p.key !== 'none' && p.h === h && p.v === v && p.blur === b;
            });
            return f ? f.key : null;
        },
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

    mounted() {
        // LE redesign — lazily mounted under the Shadow section; populate now if an
        // element is already selected (the show event fired before this listener).
        if (this.$root.selectedElement) {
            this.populateStyleEditor(this.$root.selectedElement);
        }

        this.emitter.on("element-style-editor-show", () => {
            if (this.$root.selectedElement) {
                this.populateStyleEditor(this.$root.selectedElement);
            }
        });

        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'showTextShadowOptions') {
                this.showTextShadowOptions = false;
            } else {
                this.showTextShadowOptions = true;
            }
        });
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
        textShadowOptions: {
            handler: function (newVal, oldVal) {
                if (this.selectedShadow === 'custom') {
                    this.applyTextShadow();
                }
            },
            deep: true,
        },
    },

    methods: {
        applyPropertyToActiveNode: function (prop, val) {
            if (!this.isReady) {
                return;
            }

            if (this.activeNode) {
                this.$root.applyPropertyToActiveNode(this.activeNode, prop, val);
            }
        },

        handleTextShadowColorChange(color) {
            if (typeof color !== "string") {
                return;
            }
            this.textShadowOptions.shadowColor = color;
        },

        // LE redesign — Depth segmented applies a fixed inline text-shadow string.
        setTextDepth: function (preset) {
            this.activeNode = this.activeNode || (this.$root && this.$root.selectedElement) || null;
            if (preset.key === 'none') {
                this.selectedShadow = '';
                this.resetAllProperties();
                this.applyPropertyToActiveNode('textShadow', '');
                return;
            }
            this.selectedShadow = 'custom';
            this.textShadowOptions.horizontalLength = preset.h;
            this.textShadowOptions.verticalLength = preset.v;
            this.textShadowOptions.blurRadius = preset.blur;
            if (!this.textShadowOptions.shadowColor) {
                this.textShadowOptions.shadowColor = 'rgba(0,0,0,0.2)';
            }
            this.applyTextShadow();
        },
        setTextColor: function (hex) {
            this.activeNode = this.activeNode || (this.$root && this.$root.selectedElement) || null;
            this.selectedShadow = 'custom';
            this.textShadowOptions.shadowColor = hex;
            // seed a small offset if none set, so the color is visible
            var h = parseInt(this.textShadowOptions.horizontalLength, 10) || 0;
            var v = parseInt(this.textShadowOptions.verticalLength, 10) || 0;
            var b = parseInt(this.textShadowOptions.blurRadius, 10) || 0;
            if (!h && !v && !b) {
                this.textShadowOptions.horizontalLength = 1;
                this.textShadowOptions.verticalLength = 1;
                this.textShadowOptions.blurRadius = 2;
            }
            this.applyTextShadow();
        },
        isColorActive: function (sw) {
            var v = this.textShadowOptions.shadowColor;
            if (!v) return false;
            return String(v).replace(/\s/g, '').toLowerCase() === String(sw).replace(/\s/g, '').toLowerCase();
        },
        openCustomColor: function (event) {
            var el = event && event.currentTarget ? event.currentTarget : null;
            var current = this.textShadowOptions.shadowColor || '#182433';
            var self = this;
            var picker = (typeof mw !== 'undefined' && mw.app && mw.app.colorPicker)
                ? mw.app.colorPicker
                : ((typeof mw !== 'undefined' && mw.top && mw.top().app && mw.top().app.colorPicker)
                    ? mw.top().app.colorPicker : null);
            if (picker && picker.openColorPicker) {
                picker.openColorPicker(current, function (color) { self.setTextColor(color); }, el);
            }
        },

        handleShadowChange(selectedShadow) {
            if (!this.isReady) {
                return;
            }

            this.selectedShadow = selectedShadow;

            if (this.selectedShadow === '') {
                this.resetAllProperties();
                this.applyPropertyToActiveNode("textShadow", "");
                return;
            }

            if (this.selectedShadow === 'custom') {
                // Parse current shadow if exists to populate custom options
                if (this.activeNode) {
                    var textShadowVal = getComputedStyle(this.activeNode).getPropertyValue('text-shadow');
                    if (textShadowVal && textShadowVal !== 'none') {
                        this.parseTextShadowValues(textShadowVal);
                    }
                }
                return;
            }

            this.applyPropertyToActiveNode("textShadow", this.selectedShadow);
        },

        resetAllProperties: function () {
            this.textShadowOptions = {
                horizontalLength: "",
                verticalLength: "",
                blurRadius: "",
                shadowColor: "",
            };
        },

        populateStyleEditor: function (node) {
            if (node && node.nodeType === 1) {
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                this.populateCssTextShadow();

                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },

        populateCssTextShadow: function () {
            if (!this.activeNode || !this.activeNode.style) return;

            var textShadowVal = getComputedStyle(this.activeNode);
            textShadowVal = textShadowVal.getPropertyValue('text-shadow');

            if (textShadowVal === '' || textShadowVal === 'none' || textShadowVal === 'initial' || textShadowVal === 'unset' || textShadowVal === 'inherit') {
                this.selectedShadow = '';
                return;
            } else {
                if (this.predefinedShadows.some(shadow => shadow.value === textShadowVal)) {
                    this.selectedShadow = textShadowVal;
                } else {
                    this.selectedShadow = 'custom';
                    this.parseTextShadowValues(textShadowVal);
                }
            }
        },

        parseTextShadowValues(shadowString) {
            // Parse text shadow string to extract individual values
            var parts = shadowString.trim().split(/\s+/);

            if (parts.length >= 3) {
                this.textShadowOptions.horizontalLength = parts[0].replace('px', '');
                this.textShadowOptions.verticalLength = parts[1].replace('px', '');
                this.textShadowOptions.blurRadius = parts[2].replace('px', '');

                // Extract color (can be rgb, rgba, hex, or named color)
                var colorMatch = shadowString.match(/(rgb\([^)]+\)|rgba\([^)]+\)|#[a-fA-F0-9]{3,6}|[a-zA-Z]+)/);
                if (colorMatch) {
                    this.textShadowOptions.shadowColor = colorMatch[0];
                }
            }
        },

        applyTextShadow() {
            if (!this.isReady) {
                return;
            }

            const {
                horizontalLength,
                verticalLength,
                blurRadius,
                shadowColor,
            } = this.textShadowOptions;

            const textShadowValue = `${horizontalLength || 0}px ${verticalLength || 0}px ${blurRadius || 0}px ${shadowColor || 'rgba(0,0,0,0.2)'}`;
            this.applyPropertyToActiveNode("textShadow", textShadowValue);
        },
    },
};
</script>
