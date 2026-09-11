<template>

    <div class="d-flex">

        <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg"
             xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 24 24"
             style="enable-background:new 0 0 24 24;" xml:space="preserve" aria-hidden="true">
            <path
                d="M19,19h2v2h-2V19 M19,17h2v-2h-2V17 M3,13h2v-2H3V13 M3,17h2v-2H3V17 M3,9h2V7H3V9 M3,5h2V3H3V5 M7,5h2V3H7V5 M15,21h2v-2  h-2V21 M11,21h2v-2h-2V21 M15,21h2v-2h-2V21 M7,21h2v-2H7V21 M3,21h2v-2H3V21 M21,8c0-2.8-2.2-5-5-5h-5v2h5c1.7,0,3,1.3,3,3v5h2V8z"></path>
        </svg>

        <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showRoundedCorners }">
            Rounded corners
        </span>
    </div>

    <!-- task-2026-05-16-ea56d3: @click.stop — see ElementStyleEditorTypography.vue -->
    <!-- LE redesign (frame 1c) — the ~90% action (pick a common roundness) is a
         single segmented row applying REAL Bootstrap rounded-* classes
         (rounded-0/1/2/4/pill). The full 12-step preset scale, per-corner editor
         and a uniform px slider survive verbatim under More options. -->
    <div :style="'display: ' + (showRoundedCorners ? '' : 'none') " @click.stop>

        <!-- Radius — Bootstrap rounded-* class presets. -->
        <div class="form-control-live-edit-label-wrapper">
            <label class="live-edit-label">Radius</label>
            <div class="mw-segmented mw-ese-seg">
                <span v-for="r in radiusPresets" :key="r.key" class="mw-segmented__cell"
                      :class="{ 'active': isRadiusActive(r.cls), 'is-active': isRadiusActive(r.cls) }"
                      role="button" tabindex="0"
                      :aria-pressed="isRadiusActive(r.cls) ? 'true' : 'false'"
                      @click="setRadiusPreset(r.cls)"
                      @keydown.enter.prevent="setRadiusPreset(r.cls)"
                      @keydown.space.prevent="setRadiusPreset(r.cls)">{{ r.label }}</span>
            </div>
        </div>

        <details class="mw-typography-advanced">
            <summary class="cursor-pointer text-xs opacity-70 hover:opacity-100 py-2">
                More options
            </summary>

            <!-- Link corners — Off reveals the per-corner editor + stops syncing. -->
            <div class="form-control-live-edit-label-wrapper mw-ese-visibility__row">
                <label class="live-edit-label">Link corners</label>
                <button type="button"
                        class="mw-tool-btn mw-tool-btn--toggle mw-ese-visibility__toggle"
                        :class="{ 'is-active': linkCorners }"
                        :aria-pressed="linkCorners ? 'true' : 'false'"
                        @click="linkCorners = !linkCorners">{{ linkCorners ? 'On' : 'Off' }}</button>
            </div>

            <!-- Uniform radius (px) — fine fallback; clears the active preset. -->
            <SliderSmall v-if="linkCorners" label="Uniform radius" v-model="uniformRadius"
                         :min="0" :max="60" :step="1"></SliderSmall>

            <!-- task-2026-05-16-5fe1f9 / AI-687 (ESE 1.4 MwField) — predefined
                 border-radius selector on the .mw-tool-field primitive. Legacy
                 classes kept for back-compat (external scripts target the old
                 hooks). -->
            <div class="form-control-live-edit-label-wrapper my-4 mw-tool-field">
                <label class="live-edit-label mw-tool-field__label" for="borderRadiusSelect">Radius scale</label>
                <span class="mw-tool-field__control">
                    <select class="form-control-live-edit-input form-select" id="borderRadiusSelect"
                            v-model="selectedBorderRadius"
                            @change="applyPredefinedRadius">
                        <option v-for="(radius, key) in predefinedBorderRadiusValues" :key="key" :value="radius.value">{{
                                radius.label
                            }}
                        </option>
                    </select>
                </span>
            </div>

            <!-- Per-corner editor — revealed when Link corners = Off. -->
            <div v-if="!linkCorners" class="d-flex flex-column gap-3">
                <BorderRadius v-model="borderRadius"></BorderRadius>
            </div>
        </details>
    </div>
</template>

<script>
import BorderRadius from "./components/BorderRadius.vue";
import SliderSmall from "./components/SliderSmall.vue";

export default {
    components: {BorderRadius, SliderSmall},
    data() {
        return {
            'showRoundedCorners': false,
            'activeNode': null,
            'isReady': false,
            // LE redesign — primary Radius presets apply REAL Bootstrap classes
            // (present in the Templates/Big build). rounded-3/rounded-5 exist too
            // but 5 pills is the sweet spot; the dropdown scale covers the rest.
            'radiusPresets': [
                {key: 'none', label: 'None', cls: 'rounded-0'},
                {key: 's', label: 'S', cls: 'rounded-1'},
                {key: 'm', label: 'M', cls: 'rounded-2'},
                {key: 'l', label: 'L', cls: 'rounded-4'},
                {key: 'full', label: 'Full', cls: 'rounded-pill'},
            ],
            'activeRadiusClass': null,
            'linkCorners': true,
            'uniformRadius': 0,
            'borderRadius': {
                borderTopLeftRadius: '',
                borderTopRightRadius: '',
                borderBottomLeftRadius: '',
                borderBottomRightRadius: '',
            },
            selectedBorderRadius: "",
            predefinedBorderRadiusValues: [
                {label: "None", value: "rounded-none", borderRadius: {tl: 0, tr: 0, bl: 0, br: 0}},
                {label: "Small", value: "rounded-sm", borderRadius: {tl: 2, tr: 2, bl: 2, br: 2}},
                {label: "Regular", value: "rounded", borderRadius: {tl: 4, tr: 4, bl: 4, br: 4}},
                {label: "Medium", value: "rounded-md", borderRadius: {tl: 6, tr: 6, bl: 6, br: 6}},
                {label: "Large", value: "rounded-lg", borderRadius: {tl: 8, tr: 8, bl: 8, br: 8}},
                {label: "Extra Large", value: "rounded-xl", borderRadius: {tl: 12, tr: 12, bl: 12, br: 12}},
                {label: "2XL", value: "rounded-2xl", borderRadius: {tl: 16, tr: 16, bl: 16, br: 16}},
                {label: "3XL", value: "rounded-3xl", borderRadius: {tl: 24, tr: 24, bl: 24, br: 24}},
                {label: "4XL", value: "rounded-4xl", borderRadius: {tl: 34, tr: 34, bl: 34, br: 34}},
                {label: "5XL", value: "rounded-5xl", borderRadius: {tl: 42, tr: 42, bl: 42, br: 42}},
                {label: "6XL", value: "rounded-6xl", borderRadius: {tl: 50, tr: 50, bl: 50, br: 50}},
                {label: "Full", value: "rounded-full", borderRadius: {tl: 9999, tr: 9999, bl: 9999, br: 9999}},


            ]

        };
    },
    methods: {
        toggleRoundedCorners: function () {
            this.showRoundedCorners = !this.showRoundedCorners;
            this.emitter.emit('element-style-editor-show', 'roundedCorners');
        },

        // LE redesign — strip any Bootstrap rounded-* class so a fresh preset (or
        // a px override) governs cleanly.
        _stripRoundedClasses: function (node) {
            if (!node) return;
            var re = /^rounded(-(0|1|2|3|4|5|sm|md|lg|xl|pill|circle))?$/;
            Array.from(node.classList).forEach(function (c) { if (re.test(c)) node.classList.remove(c); });
        },
        _detectRadiusClass: function (node) {
            if (!node) return null;
            var found = null;
            this.radiusPresets.forEach(function (p) {
                try { if (node.classList.contains(p.cls)) found = p.cls; } catch (e) {}
            });
            return found;
        },
        isRadiusActive: function (cls) {
            return this.activeRadiusClass === cls;
        },
        setRadiusPreset: function (cls) {
            var node = this.activeNode;
            if (!node) return;
            this._stripRoundedClasses(node);
            if (cls) node.classList.add(cls);
            // Clear any px radius so the class governs (a #id px rule with
            // !important would otherwise outrank the utility class).
            this.applyPropertyToActiveNode('border-radius', '');
            this.applyPropertyToActiveNode('border-top-left-radius', '');
            this.applyPropertyToActiveNode('border-top-right-radius', '');
            this.applyPropertyToActiveNode('border-bottom-left-radius', '');
            this.applyPropertyToActiveNode('border-bottom-right-radius', '');
            this.activeRadiusClass = cls;
            this.selectedBorderRadius = '';
            try { mw.top().app.registerChange(node); } catch (e) { /* noop */ }
        },

        applyPredefinedRadius() {
            // dropdown scale writes px — strip the class preset so they don't fight
            if (this.activeNode) this._stripRoundedClasses(this.activeNode);
            this.activeRadiusClass = null;
            const selectedRadius = this.predefinedBorderRadiusValues.find(radius => radius.value === this.selectedBorderRadius);

            if (selectedRadius) {
                /// this.borderRadius = selectedRadius.borderRadius;
                const borderRadius = selectedRadius.borderRadius;
                this.applyPropertyToActiveNode('border-top-left-radius', `${borderRadius.tl}px`);
                this.applyPropertyToActiveNode('border-top-right-radius', `${borderRadius.tr}px`);
                this.applyPropertyToActiveNode('border-bottom-left-radius', `${borderRadius.bl}px`);
                this.applyPropertyToActiveNode('border-bottom-right-radius', `${borderRadius.br}px`);

                this.populateStyleEditor(this.activeNode)
            }
        },

        resetAllProperties: function () {

            this.borderRadius = {
                borderTopLeftRadius: '',
                borderTopRightRadius: '',
                borderBottomLeftRadius: '',
                borderBottomRightRadius: '',
            }

        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                var css = mw.CSSParser(node);
                this.isReady = false;
                this.resetAllProperties();
                this.activeNode = node;

                // LE redesign — reflect the current Bootstrap rounded-* class in
                // the segmented (reactive; classList changes aren't reactive).
                this.activeRadiusClass = this._detectRadiusClass(node);

                this.populateCssBorderRadius(css);


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },

        populateCssBorderRadius: function (css) {
            if (!css || !css.get) return;
            var borderRadius = css.get.radius(true);

            this.borderTopLeftRadius = borderRadius.tl;
            this.borderTopRightRadius = borderRadius.tr;
            this.borderBottomRightRadius = borderRadius.br;
            this.borderBottomLeftRadius = borderRadius.bl;

            this.borderRadius = {
                borderTopLeftRadius: borderRadius.tl,
                borderTopRightRadius: borderRadius.tr,
                borderBottomLeftRadius: borderRadius.bl,
                borderBottomRightRadius: borderRadius.br,
            }


            // Check if the border-radius is predefined
            const foundRadius = this.predefinedBorderRadiusValues.find((radius) =>
                parseInt(radius.borderRadius.tl) === parseInt(this.borderTopLeftRadius) &&
                parseInt(radius.borderRadius.tr) === parseInt(this.borderTopRightRadius) &&
                parseInt(radius.borderRadius.bl) === parseInt(this.borderBottomLeftRadius) &&
                parseInt(radius.borderRadius.br) === parseInt(this.borderBottomRightRadius)
            );
            if (foundRadius) {
                this.selectedBorderRadius = foundRadius.value;
            } else {
                this.selectedBorderRadius = "custom";
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

    },
    mounted() {

        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (this.$root.selectedElement) {
                this.populateStyleEditor(this.$root.selectedElement);
            }
        });


        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'roundedCorners') {
                this.showRoundedCorners = false;
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
        borderRadius: function (newValue, oldValue) {


            var borderRadiusValue = '';
            if (newValue.borderTopLeftRadius) {
                borderRadiusValue += newValue.borderTopLeftRadius + 'px ';
            } else {
                borderRadiusValue += '0px ';
            }
            if (newValue.borderTopRightRadius) {
                borderRadiusValue += newValue.borderTopRightRadius + 'px ';
            } else {
                borderRadiusValue += '0px ';
            }
            if (newValue.borderBottomRightRadius) {
                borderRadiusValue += newValue.borderBottomRightRadius + 'px ';
            } else {
                borderRadiusValue += '0px ';
            }
            if (newValue.borderBottomLeftRadius) {
                borderRadiusValue += newValue.borderBottomLeftRadius + 'px ';
            } else {
                borderRadiusValue += '0px ';
            }

            this.applyPropertyToActiveNode('border-radius', borderRadiusValue);


        },
        // LE redesign — uniform px slider overrides the class preset on all corners.
        uniformRadius: function (newValue) {
            if (!this.isReady) return;
            var node = this.activeNode;
            if (node) this._stripRoundedClasses(node);
            this.activeRadiusClass = null;
            this.applyPropertyToActiveNode('border-radius', newValue + 'px');
            try { if (node) mw.top().app.registerChange(node); } catch (e) { /* noop */ }
        },
    },
};
</script>


