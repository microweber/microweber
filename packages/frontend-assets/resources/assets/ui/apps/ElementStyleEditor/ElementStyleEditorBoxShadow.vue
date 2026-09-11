<template>
    <div>
        <!-- LE redesign (frame 1c) — Depth is a soft-pill segmented applying REAL
             Bootstrap shadow-* classes (None/S/M/L → shadow-none/-sm/shadow/-lg),
             mirroring the verified rounded-* class pattern. The full preset grid
             + custom options fold into More options. -->
        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Depth</label>
            <div class="mw-segmented mw-ese-seg">
                <span v-for="d in depthPresets" :key="d.key" class="mw-segmented__cell"
                      :class="{ 'active': isDepthActive(d.cls), 'is-active': isDepthActive(d.cls) }"
                      role="button" tabindex="0"
                      :aria-pressed="isDepthActive(d.cls) ? 'true' : 'false'"
                      @click="setDepthPreset(d.cls)"
                      @keydown.enter.prevent="setDepthPreset(d.cls)"
                      @keydown.space.prevent="setDepthPreset(d.cls)">{{ d.label }}</span>
            </div>
        </div>

        <!-- Color — palette swatches + Custom. Choosing a color switches to inline
             mode: it strips the shadow-* class and rebuilds the shadow from the
             current Depth step's canonical offset/blur (resolves class/inline
             ambiguity). -->
        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Color</label>
            <div class="mw-ese-swatches">
                <button v-for="sw in colorSwatches" :key="sw" type="button" class="mw-ese-swatch"
                        :class="{ 'is-active': isColorActive(sw) }"
                        :style="{ backgroundColor: sw }" :title="sw" :aria-label="'Shadow color ' + sw"
                        @click="setShadowColor(sw)"></button>
                <span class="mw-ese-swatches__spacer"></span>
                <button type="button" class="mw-ese-custom-link" @click="openCustomColor($event)">Custom</button>
            </div>
        </div>

        <details class="mw-typography-advanced">
            <summary class="cursor-pointer text-xs opacity-70 hover:opacity-100 py-2">
                More options
            </summary>

        <!-- Custom shadow — any change forces inline mode (seeded from the active
             Depth step) so the numeric shadow and the class never fight. -->
        <SliderSmall label="Horizontal offset" v-model="customShadow.h" :min="-300" :max="300" :step="1" @change="onCustomShadowChange"/>
        <SliderSmall label="Vertical offset" v-model="customShadow.v" :min="-300" :max="300" :step="1" @change="onCustomShadowChange"/>
        <SliderSmall label="Blur" v-model="customShadow.blur" :min="0" :max="30" :step="1" @change="onCustomShadowChange"/>
        <SliderSmall label="Spread" v-model="customShadow.spread" :min="0" :max="30" :step="1" @change="onCustomShadowChange"/>
        <div class="form-control-live-edit-label-wrapper mw-ese-visibility__row">
            <label class="live-edit-label">Inset</label>
            <button type="button"
                    class="mw-tool-btn mw-tool-btn--toggle mw-ese-visibility__toggle"
                    :class="{ 'is-active': customShadow.inset }"
                    :aria-pressed="customShadow.inset ? 'true' : 'false'"
                    @click="toggleInset">{{ customShadow.inset ? 'On' : 'Off' }}</button>
        </div>

        <div class="box-shadow-options">
            <PredefinedBoxShadowsSelect :predefinedShadows="predefinedShadows"
                                        :selectedShadow="selectedShadow"
                                        @update:selectedShadow="handleShadowChange"/>
            <div class="form-group" v-if="canCustomizeBoxShadowOptions">


                <div v-for="(boxShadowOptionsGroup, index) in boxShadowOptionsGroups" :key="index">
                    <BoxShadowOptionsGroup :boxShadowOptionsGroup="boxShadowOptionsGroup" :index="index"
                                           @changeOptions="handleBoxShadowOptionsChange"/>
                </div>


                <!--                <div v-for="(boxShadowOptionsGroup, index) in boxShadowOptionsGroups" :key="index">
                                    <ColorPicker v-model="boxShadowOptionsGroup.shadowColor"
                                                 v-bind:color=boxShadowOptionsGroup.shadowColor
                                                 :label="'Color'"
                                                 @change="handleBoxShadowColorChange(boxShadowOptionsGroup.shadowColor, index)"/>
                                    <SliderSmall label="Horizontal Shadow Length" v-model="boxShadowOptionsGroup.horizontalLength"
                                                 :min="-300"
                                                 :max="300" :step="1"></SliderSmall>
                                    <SliderSmall label="Vertical Shadow Length" v-model="boxShadowOptionsGroup.verticalLength"
                                                 :min="-300" :max="300" :step="1"></SliderSmall>
                                    <SliderSmall label="Blur Radius" v-model="boxShadowOptionsGroup.blurRadius" :min="0" :max="30"
                                                 :step="1"></SliderSmall>
                                    <SliderSmall label="Spread Radius" v-model="boxShadowOptionsGroup.spreadRadius" :min="0" :max="30"
                                                 :step="1"></SliderSmall>
                                    <div class="form-group">
                                        <div class="form-check">
                                            <label for="inset"> Inset</label>
                                            <input style="margin-inline: -15px 8px;"
                                                   type="checkbox"
                                                   id="inset"
                                                   class="form-check-input"
                                                   v-model="boxShadowOptionsGroup.inset"
                                                   @change="applyBoxShadow"
                                            />
                                        </div>
                                    </div>-->
            </div>


            <!--            <div class="form-group" v-if="canCustomizeBoxShadowOptions">


                            <SliderSmall label="Horizontal Shadow Length" v-model="boxShadowOptions.horizontalLength" :min="-300"
                                         :max="300" :step="1"></SliderSmall>
                            <SliderSmall label="Vertical Shadow Length" v-model="boxShadowOptions.verticalLength" :min="-300"
                                         :max="300" :step="1"></SliderSmall>
                            <SliderSmall label="Blur Radius" v-model="boxShadowOptions.blurRadius" :min="0" :max="30"
                                         :step="1"></SliderSmall>
                            <SliderSmall label="Spread Radius" v-model="boxShadowOptions.spreadRadius" :min="0" :max="30"
                                         :step="1"></SliderSmall>


                            <ColorPicker v-model="boxShadowOptions.shadowColor" v-bind:color=boxShadowOptions.shadowColor
                                         :label="'Color'"
                                         @change="handleBoxShadowColorChange"/>

                        </div>


                        <div class="form-group" v-if="canCustomizeBoxShadowOptions">
                            <div class="form-check">
                                <label for="inset"> Inset</label>
                                <input style="margin-inline: -15px 8px;"
                                       type="checkbox"
                                       id="inset"
                                       class="form-check-input"
                                       v-model="boxShadowOptions.inset"
                                       @change="applyBoxShadow"
                                />

                            </div>
                        </div>-->


        </div>
        </details>
    </div>
</template>

<script>

import ColorPicker from "./components/ColorPicker.vue";

import SliderSmall from "./components/SliderSmall.vue";
import PredefinedBoxShadowsSelect from "./components/PredefinedBoxShadowsSelect.vue";
import BoxShadowOptionsGroup from "./components/BoxShadowOptionsGroup.vue";

export default {
    components: {ColorPicker, SliderSmall, PredefinedBoxShadowsSelect, BoxShadowOptionsGroup},

    data() {
        var predefinedShadows = mw.top().app.templateSettings.getPredefinedBoxShadows();

        return {
            'activeNode': null,
            'isReady': false,
            // LE redesign — primary Depth presets → Bootstrap shadow-* classes.
            'depthPresets': [
                {key: 'none', label: 'None', cls: 'shadow-none'},
                {key: 's', label: 'S', cls: 'shadow-sm'},
                {key: 'm', label: 'M', cls: 'shadow'},
                {key: 'l', label: 'L', cls: 'shadow-lg'},
            ],
            'activeDepthClass': null,
            // LE redesign — inline custom shadow model + canonical per-depth seeds.
            'customShadow': {h: 0, v: 4, blur: 8, spread: 0, color: '', inset: false},
            'depthCanonical': {
                'shadow-sm': {h: 0, v: 2, blur: 4},
                'shadow': {h: 0, v: 4, blur: 8},
                'shadow-lg': {h: 0, v: 10, blur: 20},
            },
            selectedShadow: '',
            canCustomizeBoxShadowOptions: false,
            boxShadowOptions: {
                horizontalLength: '',
                verticalLength: '',
                blurRadius: '',
                spreadRadius: '',
                shadowColor: '',
                inset: '',
            },
            boxShadowOptionsGroups: {
                0: {
                    horizontalLength: '',
                    verticalLength: '',
                    blurRadius: '',
                    spreadRadius: '',
                    shadowColor: '',
                    inset: '',
                }
            },
            predefinedShadows: predefinedShadows
        };
    },

    mounted() {
        // LE redesign — this subpanel mounts lazily (v-if on the Shadow section),
        // so it can miss the already-set selection (the emitter event fires before
        // this listener registers). Populate immediately if an element is selected.
        if (this.$root.selectedElement) {
            this.populateStyleEditor(this.$root.selectedElement);
        }

        this.emitter.on("element-style-editor-show", () => {
            if (this.$root.selectedElement) {
                this.populateStyleEditor(this.$root.selectedElement);
            }
        });


        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'showBoxShadowOptions') {
                this.showBoxShadowOptions = false;
            } else {
                this.showBoxShadowOptions = true;
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


        boxShadowOptions: {
            handler: function (newVal, oldVal) {
                this.applyBoxShadow();
            },
            deep: true,
        },
    },
    computed: {
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
        // LE redesign — custom inline shadow (with the class→inline transition).
        isColorActive: function (sw) {
            var v = this.customShadow.color;
            if (!v) return false;
            return String(v).replace(/\s/g, '').toLowerCase() === String(sw).replace(/\s/g, '').toLowerCase();
        },
        _seedFromDepth: function () {
            var canon = this.depthCanonical[this.activeDepthClass];
            if (canon) {
                this.customShadow.h = canon.h;
                this.customShadow.v = canon.v;
                this.customShadow.blur = canon.blur;
            }
        },
        _activateInline: function () {
            // once the numeric shadow is edited, the utility class must go so the
            // inline #id rule isn't fighting it.
            if (this.activeDepthClass) {
                this._seedFromDepth();
                if (this.activeNode) this._stripShadowClasses(this.activeNode);
                this.activeDepthClass = null;
            }
            this.selectedShadow = 'custom';
        },
        _buildInline: function () {
            var s = this.customShadow;
            var color = s.color || 'rgba(0,0,0,0.2)';
            return (s.inset ? 'inset ' : '') + s.h + 'px ' + s.v + 'px ' + s.blur + 'px ' + s.spread + 'px ' + color;
        },
        applyCustomShadow: function () {
            var node = this.activeNode || (this.$root && this.$root.selectedElement) || null;
            if (!node) return;
            this.activeNode = node;
            this.$root.applyPropertyToActiveNode(node, 'boxShadow', this._buildInline());
            try { mw.top().app.registerChange(node); } catch (e) { /* noop */ }
        },
        setShadowColor: function (hex) {
            this._activateInline();
            this.customShadow.color = hex;
            this.applyCustomShadow();
        },
        openCustomColor: function (event) {
            var el = event && event.currentTarget ? event.currentTarget : null;
            var current = this.customShadow.color || '#182433';
            var self = this;
            var picker = (typeof mw !== 'undefined' && mw.app && mw.app.colorPicker)
                ? mw.app.colorPicker
                : ((typeof mw !== 'undefined' && mw.top && mw.top().app && mw.top().app.colorPicker)
                    ? mw.top().app.colorPicker : null);
            if (picker && picker.openColorPicker) {
                picker.openColorPicker(current, function (color) { self.setShadowColor(color); }, el);
            }
        },
        onCustomShadowChange: function () {
            this._activateInline();
            this.applyCustomShadow();
        },
        toggleInset: function () {
            this.customShadow.inset = !this.customShadow.inset;
            this._activateInline();
            this.applyCustomShadow();
        },
        applyPropertyToActiveNode: function (prop, val) {
            if (!this.isReady) {
                return;
            }

            if (this.activeNode) {
                this.$root.applyPropertyToActiveNode(this.activeNode, prop, val);
            }
        },
        // LE redesign — class-based Depth presets (mirrors rounded-* pattern).
        _stripShadowClasses: function (node) {
            if (!node) return;
            var re = /^shadow(-(sm|lg|none))?$/;
            Array.from(node.classList).forEach(function (c) { if (re.test(c)) node.classList.remove(c); });
        },
        _detectShadowClass: function (node) {
            if (!node) return null;
            var found = null;
            this.depthPresets.forEach(function (d) {
                try { if (node.classList.contains(d.cls)) found = d.cls; } catch (e) {}
            });
            return found;
        },
        isDepthActive: function (cls) {
            return this.activeDepthClass === cls;
        },
        setDepthPreset: function (cls) {
            // fall back to the root selection if this lazily-mounted subpanel
            // hasn't populated yet.
            var node = this.activeNode || (this.$root && this.$root.selectedElement) || null;
            if (!node) return;
            this.activeNode = node;
            this._stripShadowClasses(node);
            if (cls) node.classList.add(cls);
            // Clear any inline box-shadow so the class governs (a #id inline rule
            // with !important would otherwise outrank the utility class).
            this.applyPropertyToActiveNode('boxShadow', '');
            this.activeDepthClass = cls;
            this.selectedShadow = '';
            try { mw.top().app.registerChange(node); } catch (e) { /* noop */ }
        },
        handleBoxShadowOptionsChange(updatedOptions, index) {
            this.boxShadowOptionsGroups[index] = updatedOptions;

        },
        handleBoxShadowColorChange(color, index) {
            if (typeof (color) != 'string') {
                return;
            }
            //   this.boxShadowOptions.shadowColor = color;
            this.boxShadowOptionsGroups[index].shadowColor = color;
            this.applyBoxShadow();
        },

        toggleBoxShadow: function () {
            this.showBoxShadowOptions = !this.showBoxShadowOptions;
            this.emitter.emit('element-style-editor-show', 'boxShadowOptions');
        },

        resetAllProperties: function () {
            this.selectedShadow = '';
            this.customShadow = {h: 0, v: 4, blur: 8, spread: 0, color: '', inset: false};
            this.boxShadowOptions = {
                horizontalLength: '',
                verticalLength: '',
                blurRadius: '',
                spreadRadius: '',
                shadowColor: '',
                inset: '',
            }

        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                // LE redesign — reflect the current shadow-* class in the Depth
                // segmented (reactive; classList mutation isn't reactive).
                this.activeDepthClass = this._detectShadowClass(node);

                this.populateCssBoxShadow(css);


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },
        populateCssBoxShadow: function (css) {
            if (!css || !css.get) return;
            if (!this.activeNode || !this.activeNode.style) return;


            var boxShadowVal = getComputedStyle(this.activeNode)
            boxShadowVal = boxShadowVal.getPropertyValue('box-shadow');


            if (boxShadowVal === '' || boxShadowVal === 'none' || boxShadowVal === 'initial' || boxShadowVal === 'unset' || boxShadowVal === 'inherit' || boxShadowVal === '0px 0px 0px 0px rgba(0,0,0,0)') {
                this.selectedShadow = '';
                return;
            } else {
                if (this.predefinedShadows.some(shadow => shadow.value === boxShadowVal)) {
                    this.selectedShadow = boxShadowVal;
                } else {
                    this.selectedShadow = 'custom';
                    // LE redesign — seed the custom sliders/color from the inline value.
                    try {
                        var parsed = this.parseShadowValues(boxShadowVal);
                        if (parsed && parsed[0]) {
                            var p = parsed[0];
                            var num = function (s) { return parseInt(String(s).replace(/px|em|%/g, ''), 10) || 0; };
                            this.customShadow.h = num(p.horizontalLength);
                            this.customShadow.v = num(p.verticalLength);
                            this.customShadow.blur = num(p.blurRadius);
                            this.customShadow.spread = num(p.spreadRadius);
                            this.customShadow.color = p.shadowColor || '';
                            this.customShadow.inset = !!p.inset;
                        }
                    } catch (e) { /* keep defaults */ }
                }
                //this.selectedShadow = boxShadowVal;
                //this.selectedShadow = boxShadowVal;
                //check if in predefined
                // if (this.predefinedShadows.some(shadow => shadow.value === boxShadowVal)) {
                //     this.selectedShadow = boxShadowVal;
                // } else if (this.predefinedShadows.some(
                //     shadow => shadow.value.replace('0px', '') === boxShadowVal || shadow.value.replace('0px 0px 0px 0px', 'none') === boxShadowVal
                // )) {
                //     this.selectedShadow = boxShadowVal;
                // } else {
                //     this.selectedShadow = 'custom';
                // }
            }

            // var result = css.get.boxShadow();
            //
            // for (let i in result) {
            //     if (typeof result[i] === 'number') {
            //         result[i] = `${result[i]}`;
            //     }
            // }
            //
            // var resultCss = '';
            //
            // if (result.offsetX) {
            //     resultCss += result.offsetX.replace('px', '') + 'px ';
            //     // Remove "px" from the size
            //     this.boxShadowOptions.horizontalLength = result.offsetX.replace('px', '');
            // }
            // if (result.offsetY) {
            //     resultCss += ' ' + result.offsetY.replace('px', '') + 'px ';
            //     // Remove "px" from the size
            //     this.boxShadowOptions.verticalLength = result.offsetY.replace('px', '');
            // }
            // if (result.blurRadius) {
            //     resultCss += ' ' + result.blurRadius.replace('px', '') + 'px ';
            //     // Remove "px" from the size
            //     this.boxShadowOptions.blurRadius = result.blurRadius.replace('px', '');
            // }
            // if (result.spreadRadius) {
            //     resultCss += ' ' + result.spreadRadius.replace('px', '') + 'px ';
            //     // Remove "px" from the size
            //     this.boxShadowOptions.spreadRadius = result.spreadRadius.replace('px', '');
            // }
            // if (result.color) {
            //     resultCss += ' ' + result.color;
            //     this.boxShadowOptions.shadowColor = result.color;
            // }
            // if (result.inset) {
            //     if (result.inset === true) {
            //         resultCss += ' ' + 'inset';
            //     }
            //     this.boxShadowOptions.inset = result.inset;
            // }
            // var resultCssTrim = resultCss.replace(/\s+/g, ' '); // replace double space
            //
            // resultCssTrim = resultCssTrim.trim();
            //
            // if (this.predefinedShadows.some(shadow => shadow.value === resultCssTrim)) {
            //     this.selectedShadow = resultCssTrim;
            // } else if (resultCssTrim === '' || resultCssTrim === 'none' || resultCssTrim === 'initial' || resultCssTrim === 'unset' || resultCssTrim === 'inherit' || resultCssTrim === '0px 0px 0px 0px rgba(0,0,0,0)') {
            //     this.selectedShadow = '';
            // } else {
            //     this.selectedShadow = 'custom';
            // }

        },
        handleShadowChange(selectedShadow) {
            if (!this.isReady) {
                return;
            }
            this.selectedShadow = selectedShadow;

            if (this.selectedShadow === '') {
                this.resetAllProperties();
                this.applyBoxShadow();
                return;
            }
            if (this.selectedShadow === 'custom') {
                return;
            }

            // this.applyPropertyToActiveNode('boxShadow', selectedShadow);

            // this.canCustomizeBoxShadowOptions = false;
            //  this.canCustomizeBoxShadowOptions = 1;
            var selected = this.selectedShadow;
            var parseShadowValues = this.parseShadowValues(selected);

            this.applyPropertyToActiveNode('boxShadow', selectedShadow);

            if (this.canCustomizeBoxShadowOptions) {
                if (parseShadowValues.length > 0) {
                    this.boxShadowOptionsGroups = parseShadowValues;
                }
            }
            //
            //
            // //if more than 1 shadow
            // if (parseShadowValues.length > 1) {
            //
            //     this.canCustomizeBoxShadowOptions = false;
            //     return;
            // }
            //
            //
            // if(parseShadowValues[0]){
            //     this.canCustomizeBoxShadowOptions = true;
            //     this.boxShadowOptions.horizontalLength = parseShadowValues[0].horizontalLength.replace(/px/g, '');;
            //     this.boxShadowOptions.verticalLength = parseShadowValues[0].verticalLength.replace(/px/g, '');;
            //     this.boxShadowOptions.blurRadius = parseShadowValues[0].blurRadius.replace(/px/g, '');;
            //     this.boxShadowOptions.spreadRadius = parseShadowValues[0].spreadRadius.replace(/px/g, '');;
            //     this.boxShadowOptions.shadowColor = parseShadowValues[0].shadowColor;
            //     this.boxShadowOptions.inset = parseShadowValues[0].inset;
            //
            //     this.applyBoxShadow();
            // }


        },

        parseShadowValues(shadowString) {
            var shadowValues = mw.top().app.templateSettings.cssBoxShadowsParser.parseBoxShadowValues(shadowString);

            return shadowValues;
        },


        applyBoxShadow() {

            if (!this.isReady) {
                return;
            }

            const {
                horizontalLength,
                verticalLength,
                blurRadius,
                spreadRadius,
                shadowColor,
                inset,
            } = this.boxShadowOptions;


            //  const boxShadowValue = `${inset ? 'inset ' : ''}${horizontalLength} ${verticalLength} ${blurRadius} ${spreadRadius} ${shadowColor}`;
            const boxShadowValue = `${inset ? 'inset ' : ''}${horizontalLength}px ${verticalLength}px ${blurRadius}px ${spreadRadius}px ${shadowColor}`;

            // You can emit this value or perform other actions based on your requirements
            this.applyPropertyToActiveNode('boxShadow', boxShadowValue);
        },
    },
};
</script>




