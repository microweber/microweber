<template>
    <div v-if="hasGrid">


        <div class="d-flex">
            <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 version="1.1" width="24" height="24" viewBox="0 0 24 24" aria-hidden="true">
                <path
                    d="M4,2H20A2,2 0 0,1 22,4V20A2,2 0 0,1 20,22H4C2.92,22 2,21.1 2,20V4A2,2 0 0,1 4,2M4,4V11H11V4H4M4,20H11V13H4V20M20,20V13H13V20H20M20,4H13V11H20V4Z"></path>
            </svg>

            <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showGridSettings }">
                Grid
            </span>
        </div>

        <!-- task-2026-05-16-ea56d3: @click.stop — see ElementStyleEditorTypography.vue -->
        <!-- LE redesign (frame 1c) — three near-identical 12-row dropdowns collapse
             to a Screen breakpoint segmented (each cell captions its stored width
             so the responsive overview survives) + one Width segmented that rebinds
             to the active breakpoint. Full 1–12 granularity + clear stay in the
             Exact columns dropdown under More options. -->
        <div v-if="showGridSettings" @click.stop>

            <!-- Screen — pick the breakpoint; caption shows each device's width. -->
            <div class="form-control-live-edit-label-wrapper">
                <label class="live-edit-label">Screen</label>
                <div class="mw-segmented mw-ese-seg">
                    <span v-for="d in gridDevices" :key="d.key"
                          class="mw-segmented__cell mw-ese-screen-cell"
                          :class="{ 'active': activeBreakpoint === d.key, 'is-active': activeBreakpoint === d.key }"
                          role="button" tabindex="0"
                          :aria-label="d.label + ' width ' + deviceCaption(d.key)"
                          :aria-pressed="activeBreakpoint === d.key ? 'true' : 'false'"
                          @click="activeBreakpoint = d.key"
                          @keydown.enter.prevent="activeBreakpoint = d.key"
                          @keydown.space.prevent="activeBreakpoint = d.key">
                        <i class="mdi mw-grid-device-icon" :class="d.icon"></i>
                        <span class="mw-ese-screen-cell__cap">{{ deviceCaption(d.key) }}</span>
                    </span>
                </div>
            </div>

            <!-- Width — Bootstrap col-{bp}-{n} class for the active breakpoint. -->
            <div class="form-control-live-edit-label-wrapper">
                <label class="live-edit-label">Width</label>
                <div class="mw-segmented mw-ese-seg">
                    <span v-for="w in widthPresets" :key="w.key"
                          class="mw-segmented__cell"
                          :class="{ 'active': isWidthActive(w.n), 'is-active': isWidthActive(w.n) }"
                          role="button" tabindex="0"
                          :aria-label="w.label"
                          :aria-pressed="isWidthActive(w.n) ? 'true' : 'false'"
                          @click="setActiveColNum(w.n)"
                          @keydown.enter.prevent="setActiveColNum(w.n)"
                          @keydown.space.prevent="setActiveColNum(w.n)">{{ w.label }}</span>
                </div>
            </div>

            <details class="mw-typography-advanced">
                <summary class="cursor-pointer text-xs opacity-70 hover:opacity-100 py-2">
                    More options
                </summary>
                <DropdownSmall :options="exactColOptions" v-model="exactColModel" label="Exact columns"/>
            </details>
        </div>
    </div>
</template>

<script>
import DropdownSmall from './components/DropdownSmall.vue';

export default {
    components: {DropdownSmall},
    data() {
        return {
            activeGridNode: null,
            showGridSettings: false,
            isReady: false,
            hasGrid: false,

            colOptionsDesktop: [
                // {key: null, value: 'None'},
                {key: 'col-lg-1', value: 'col-lg-1'},
                {key: 'col-lg-2', value: 'col-lg-2'},
                {key: 'col-lg-3', value: 'col-lg-3'},
                {key: 'col-lg-4', value: 'col-lg-4'},
                {key: 'col-lg-5', value: 'col-lg-5'},
                {key: 'col-lg-6', value: 'col-lg-6'},
                {key: 'col-lg-7', value: 'col-lg-7'},
                {key: 'col-lg-8', value: 'col-lg-8'},
                {key: 'col-lg-9', value: 'col-lg-9'},
                {key: 'col-lg-10', value: 'col-lg-10'},
                {key: 'col-lg-11', value: 'col-lg-11'},
                {key: 'col-lg-12', value: 'col-lg-12'},
            ],

            colOptionsTablet: [
                // {key: null, value: 'None'},
                {key: 'col-md-1', value: 'col-md-1'},
                {key: 'col-md-2', value: 'col-md-2'},
                {key: 'col-md-3', value: 'col-md-3'},
                {key: 'col-md-4', value: 'col-md-4'},
                {key: 'col-md-5', value: 'col-md-5'},
                {key: 'col-md-6', value: 'col-md-6'},
                {key: 'col-md-7', value: 'col-md-7'},
                {key: 'col-md-8', value: 'col-md-8'},
                {key: 'col-md-9', value: 'col-md-9'},
                {key: 'col-md-10', value: 'col-md-10'},
                {key: 'col-md-11', value: 'col-md-11'},
                {key: 'col-md-12', value: 'col-md-12'},
            ],

            colOptionsMobile: [
                // {key: null, value: 'None'},
                {key: 'col-sm-1', value: 'col-sm-1'},
                {key: 'col-sm-2', value: 'col-sm-2'},
                {key: 'col-sm-3', value: 'col-sm-3'},
                {key: 'col-sm-4', value: 'col-sm-4'},
                {key: 'col-sm-5', value: 'col-sm-5'},
                {key: 'col-sm-6', value: 'col-sm-6'},
                {key: 'col-sm-7', value: 'col-sm-7'},
                {key: 'col-sm-8', value: 'col-sm-8'},
                {key: 'col-sm-9', value: 'col-sm-9'},
                {key: 'col-sm-10', value: 'col-sm-10'},
                {key: 'col-sm-11', value: 'col-sm-11'},
                {key: 'col-sm-12', value: 'col-sm-12'},
            ],

            selectedColDesktop: '',
            selectedColTablet: '',
            selectedColMobile: '',

            // LE redesign — breakpoint selector + width presets.
            activeBreakpoint: 'desktop',
            gridDevices: [
                {key: 'desktop', label: 'Desktop', icon: 'mdi-monitor'},
                {key: 'tablet', label: 'Tablet', icon: 'mdi-tablet'},
                {key: 'mobile', label: 'Mobile', icon: 'mdi-cellphone'},
            ],
            widthPresets: [
                {key: 'full', label: 'Full', n: 12},
                {key: 'half', label: '½', n: 6},
                {key: 'third', label: '⅓', n: 4},
                {key: 'twothird', label: '⅔', n: 8},
                {key: 'quarter', label: '¼', n: 3},
                {key: 'threequarter', label: '¾', n: 9},
            ],
            exactColOptions: [
                {key: '', value: 'None'},
                {key: 1, value: '1'}, {key: 2, value: '2'}, {key: 3, value: '3'},
                {key: 4, value: '4'}, {key: 5, value: '5'}, {key: 6, value: '6'},
                {key: 7, value: '7'}, {key: 8, value: '8'}, {key: 9, value: '9'},
                {key: 10, value: '10'}, {key: 11, value: '11'}, {key: 12, value: '12'},
            ],
        };
    },

    computed: {
        // LE redesign — Bootstrap breakpoint prefix + the data key for the
        // active Screen; a single Width control rebinds to whichever is active.
        _bpPrefix: function () {
            return {desktop: 'lg', tablet: 'md', mobile: 'sm'}[this.activeBreakpoint];
        },
        _activeSelectedKey: function () {
            return {
                desktop: 'selectedColDesktop',
                tablet: 'selectedColTablet',
                mobile: 'selectedColMobile',
            }[this.activeBreakpoint];
        },
        activeColNum: function () {
            var v = this[this._activeSelectedKey];
            var m = v && /col-(?:lg|md|sm)-(\d+)/.exec(v);
            return m ? parseInt(m[1], 10) : null;
        },
        // v-model proxy for the Exact columns dropdown.
        exactColModel: {
            get: function () { return this.activeColNum || ''; },
            set: function (v) { this.setActiveColNum(v ? parseInt(v, 10) : null); },
        },
    },

    methods: {
        // LE redesign — set/read the col-{bp}-{n} class for the active Screen.
        setActiveColNum: function (n) {
            var key = this._activeSelectedKey;
            this[key] = n ? ('col-' + this._bpPrefix + '-' + n) : '';
        },
        isWidthActive: function (n) {
            return this.activeColNum === n;
        },
        deviceCaption: function (device) {
            var k = 'selectedCol' + device.charAt(0).toUpperCase() + device.slice(1);
            var v = this[k];
            var m = v && /col-(?:lg|md|sm)-(\d+)/.exec(v);
            if (!m) return '—';
            var n = parseInt(m[1], 10);
            var frac = {12: 'Full', 9: '¾', 8: '⅔', 6: '½', 4: '⅓', 3: '¼'};
            return frac[n] || (n + '/12');
        },
        toggleGridSettings: function () {
            this.showGridSettings = !this.showGridSettings;
            this.emitter.emit('element-style-editor-show', 'grid');
        },
        resetAllProperties: function () {
            this.hasGrid = null;
        },

        populateStyleEditor: function (node) {
            if (node && node.nodeType === 1) {
                this.isReady = false;
                this.resetAllProperties();

                var hasGridParent = mw.top().app.liveEdit.liveEditHelpers.targetGetFirstColElement(node);
                if (hasGridParent) {
                    node = hasGridParent;
                }


                // Check for grid classes in the active node
                for (const gridOption of this.colOptionsDesktop) {
                    if (node.classList.contains(gridOption.key)) {
                        this.hasGrid = true;
                        this.activeGridNode = node;
                        // Set the selected class name for Desktop breakpoint
                        this.selectedColDesktop = gridOption.key;
                        break;
                    }
                }
                for (const gridOption of this.colOptionsTablet) {
                    if (node.classList.contains(gridOption.key)) {
                        this.hasGrid = true;
                        this.activeGridNode = node;
                        // Set the selected class name for Tablet breakpoint
                        this.selectedColTablet = gridOption.key;
                        break;
                    }
                }
                for (const gridOption of this.colOptionsMobile) {
                    if (node.classList.contains(gridOption.key)) {
                        this.hasGrid = true;
                        this.activeGridNode = node;
                        // Set the selected class name for Mobile breakpoint
                        this.selectedColMobile = gridOption.key;
                        break;
                    }
                }


                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },

        applyClassToActiveGridNode: function (val) {
            if (!this.isReady) {
                return;
            }
            if (this.activeGridNode) {

                var vals = [this.selectedColMobile, this.selectedColTablet, this.selectedColDesktop];
                // Apply the selected grid class for the corresponding breakpoint
                const allGridOption = [...this.colOptionsDesktop, ...this.colOptionsTablet, ...this.colOptionsMobile];
                if (allGridOption) {
                    //remove all classes
                    for (const gridOption of allGridOption) {
                        this.activeGridNode.classList.remove(gridOption.key);
                    }
                    // add vals (skip empty — classList.add('') throws, and an
                    // empty breakpoint simply means "no explicit width there")
                    for (const val of vals) {
                        if (val) {
                            this.activeGridNode.classList.add(val);
                        }
                    }

                }

                if (mw.top().app) {
                    mw.top().app.registerChangedState(this.activeGridNode);
                }
            }
        },
    },

    mounted() {
        // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
        //   this.populateStyleEditor(element);
        // });

        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (this.$root.selectedElement) {
                this.populateStyleEditor(this.$root.selectedElement);
            }
        });

        this.emitter.on("element-style-editor-show", elementStyleEditorShow => {
            if (elementStyleEditorShow !== 'grid') {
                this.showGridSettings = false;
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


        selectedColDesktop: function (newValue, oldValue) {
            this.applyClassToActiveGridNode(newValue);
        },

        selectedColTablet: function (newValue, oldValue) {
            this.applyClassToActiveGridNode(newValue);
        },

        selectedColMobile: function (newValue, oldValue) {
            this.applyClassToActiveGridNode(newValue);
        },
    },
};
</script>

<style scoped>
.mw-grid-device-icon {
    font-size: 20px;
}

/* LE redesign — Screen cell stacks a device icon over a tiny width caption so
   the 3-device responsive overview survives inside one segmented row. */
.mw-ese-screen-cell {
    flex-direction: column;
    gap: 2px;
    padding-top: 5px;
    padding-bottom: 5px;
    line-height: 1.1;
}
.mw-ese-screen-cell .mw-grid-device-icon {
    font-size: 16px;
}
.mw-ese-screen-cell__cap {
    font-size: 10px;
    font-weight: 500;
    opacity: 0.7;
}
</style>
