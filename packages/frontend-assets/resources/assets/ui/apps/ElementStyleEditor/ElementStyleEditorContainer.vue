<template>
    <div v-if="hasContainer">

        <div class="d-flex">
            <svg fill="currentColor" height="24" width="24" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="currentColor" d="M19,17H5V7H19M19,5H5A2,2 0 0,0 3,7V17A2,2 0 0,0 5,19H19A2,2 0 0,0 21,17V7C21,5.89 20.1,5 19,5Z"></path>
            </svg>

            <span class="mw-admin-action-links mw-adm-liveedit-tabs ms-3" :class="{'active': showContainer }">
                Container
            </span>
        </div>

        <!-- task-2026-05-16-ea56d3: @click.stop — see ElementStyleEditorTypography.vue -->
        <!-- LE redesign (frame 1c) — the lone Container/Fluid radio pair becomes a
             Width segmented, joined by two class-based container primaries reusing
             shipped components (SpaceAround, an Align segmented). Responsive Max
             width folds into More options (Fixed only). Background is intentionally
             NOT here — a dedicated Background section owns it. -->
        <div v-if="showContainer" @click.stop id="field-conatiner-type">

            <!-- Width — Fixed (container) vs Full (container-fluid). -->
            <div class="form-control-live-edit-label-wrapper">
                <label class="live-edit-label">Width</label>
                <div class="mw-segmented mw-ese-seg">
                    <span v-for="w in widthPresets" :key="w.key" class="mw-segmented__cell"
                          :class="{ 'active': isWidthActive(w.key), 'is-active': isWidthActive(w.key) }"
                          role="button" tabindex="0"
                          :aria-pressed="isWidthActive(w.key) ? 'true' : 'false'"
                          @click="setWidth(w.key)"
                          @keydown.enter.prevent="setWidth(w.key)"
                          @keydown.space.prevent="setWidth(w.key)">{{ w.label }}</span>
                </div>
            </div>

            <!-- Space around — Bootstrap p-*/m-* classes on the container node. -->
            <SpaceAround label="Space around" :value="spaceValue" :mode="spaceMode"
                         @update="onSpaceUpdate" @update:mode="onSpaceModeChange"/>

            <!-- Align content — text-start/center/end (RTL-aware logical classes). -->
            <div class="form-control-live-edit-label-wrapper">
                <label class="live-edit-label">Align content</label>
                <div class="mw-segmented mw-ese-seg">
                    <span v-for="a in alignOptions" :key="a.key" class="mw-segmented__cell"
                          :class="{ 'active': alignClass === a.key, 'is-active': alignClass === a.key }"
                          role="button" tabindex="0"
                          :aria-label="a.label"
                          :aria-pressed="alignClass === a.key ? 'true' : 'false'"
                          @click="setAlign(a.key)"
                          @keydown.enter.prevent="setAlign(a.key)"
                          @keydown.space.prevent="setAlign(a.key)">{{ a.label }}</span>
                </div>
            </div>

            <details class="mw-typography-advanced">
                <summary class="cursor-pointer text-xs opacity-70 hover:opacity-100 py-2">
                    More options
                </summary>
                <DropdownSmall v-if="containerType !== 'container-fluid'"
                               :options="maxWidthOptions" v-model="maxWidthModel" label="Max width"/>
            </details>

        </div>

    </div>

</template>


<script>

import DropdownSmall from './components/DropdownSmall.vue';
import SpaceAround from './components/SpaceAround.vue';


export default {
    components: {DropdownSmall, SpaceAround},
    data() {
        return {
            'showContainer': false,
            'activeContainerNode': null,
            'isReady': false,
            'hasContainer': false,
            'containerType': null,

            // LE redesign — segmented Width + class-based Align + Space around,
            // with responsive Max width under More.
            'widthPresets': [
                {key: 'container', label: 'Fixed'},
                {key: 'container-fluid', label: 'Full'},
            ],
            'alignOptions': [
                {key: 'text-start', label: 'Left'},
                {key: 'text-center', label: 'Center'},
                {key: 'text-end', label: 'Right'},
            ],
            'maxWidthOptions': [
                {key: 'container', value: 'Default'},
                {key: 'container-sm', value: 'Small'},
                {key: 'container-md', value: 'Medium'},
                {key: 'container-lg', value: 'Large'},
                {key: 'container-xl', value: 'X-Large'},
                {key: 'container-xxl', value: 'XX-Large'},
            ],
            'maxWidthClass': 'container',
            'alignClass': null,
            'spaceValue': null,
            'spaceMode': 'padding',
        };
    },

    computed: {
        // v-model proxy for the Max width dropdown.
        maxWidthModel: {
            get: function () { return this.maxWidthClass || 'container'; },
            set: function (v) { this.setMaxWidth(v); },
        },
    },

    methods: {
        toggleContainer: function () {
            this.showContainer = !this.showContainer;
            this.emitter.emit('element-style-editor-show', 'container');
        },
        resetAllProperties: function () {
            this.hasContainer = null;
            this.containerType = null;
            this.maxWidthClass = 'container';
            this.alignClass = null;
            this.spaceValue = null;
            this.spaceMode = 'padding';
        },

        // LE redesign — strip the entire container-* family before re-applying.
        _stripContainerFamily: function (node) {
            if (!node) return;
            var re = /^container(-(sm|md|lg|xl|xxl|fluid))?$/;
            Array.from(node.classList).forEach(function (c) { if (re.test(c)) node.classList.remove(c); });
        },
        _applyContainerClass: function (cls) {
            var node = this.activeContainerNode;
            if (!node) return;
            this._stripContainerFamily(node);
            if (cls) node.classList.add(cls);
            try { mw.top().app.registerChange(node); } catch (e) { /* noop */ }
        },
        isWidthActive: function (key) {
            if (key === 'container-fluid') return this.containerType === 'container-fluid';
            return this.containerType !== 'container-fluid';
        },
        setWidth: function (key) {
            if (key === 'container-fluid') {
                this.containerType = 'container-fluid';
                this._applyContainerClass('container-fluid');
            } else {
                this.containerType = 'container';
                this._applyContainerClass(this.maxWidthClass || 'container');
            }
        },
        setMaxWidth: function (cls) {
            this.maxWidthClass = cls;
            this.containerType = 'container';
            this._applyContainerClass(cls);
        },
        // Align content — Bootstrap text-align utility classes on the container.
        _stripAlignClasses: function (node) {
            if (!node) return;
            ['text-start', 'text-center', 'text-end'].forEach(function (c) { node.classList.remove(c); });
        },
        setAlign: function (cls) {
            var node = this.activeContainerNode;
            if (!node) return;
            this._stripAlignClasses(node);
            if (cls) node.classList.add(cls);
            this.alignClass = cls;
            try { mw.top().app.registerChange(node); } catch (e) { /* noop */ }
        },
        // Space around — p-*/m-* classes (px<->step: 0->0,8->2,16->3,48->5).
        _pxToStep: function (px) {
            var map = {0: '0', 8: '2', 16: '3', 48: '5', 4: '1', 24: '4'};
            return map[px] != null ? map[px] : '3';
        },
        _stepToPx: function (step) {
            var map = {'0': 0, '1': 4, '2': 8, '3': 16, '4': 24, '5': 48};
            return map[step] != null ? map[step] : null;
        },
        onSpaceUpdate: function (payload) {
            var px = payload && typeof payload === 'object' ? payload.px : payload;
            var mode = payload && payload.mode ? payload.mode : this.spaceMode;
            this.spaceMode = mode;
            this.spaceValue = px;
            var node = this.activeContainerNode;
            if (!node) return;
            var prefix = mode === 'margin' ? 'm' : 'p';
            var re = new RegExp('^' + prefix + '-[0-5]$');
            Array.from(node.classList).forEach(function (c) { if (re.test(c)) node.classList.remove(c); });
            node.classList.add(prefix + '-' + this._pxToStep(px));
            try { mw.top().app.registerChange(node); } catch (e) { /* noop */ }
        },
        onSpaceModeChange: function (mode) {
            this.spaceMode = mode;
            this.spaceValue = this._readSpace(this.activeContainerNode, mode);
        },
        _readSpace: function (node, mode) {
            if (!node) return null;
            var prefix = mode === 'margin' ? 'm' : 'p';
            var re = new RegExp('^' + prefix + '-([0-5])$');
            var self = this, found = null;
            try { Array.from(node.classList).forEach(function (c) { var m = re.exec(c); if (m) found = self._stepToPx(m[1]); }); } catch (e) {}
            return found;
        },

        populateStyleEditor: function (node) {
            if (node && node && node.nodeType === 1) {
                this.isReady = false;
                this.resetAllProperties();
                var containerNode = mw.tools.firstParentOrCurrentWithAnyOfClasses(node, ['container', 'container-fluid']);

                if (containerNode && containerNode.parentNode &&  mw.tools.isEditable(containerNode.parentNode)) {
                        this.hasContainer = true;
                        this.activeContainerNode = containerNode;
                        this.populateCssContainerForNode(containerNode);
                }



                setTimeout(() => {
                    this.isReady = true;
                }, 100);
            }
        },

        populateCssContainerForNode: function (node) {

            if (node.classList && node.classList.contains('container-fluid')) {
                this.containerType = 'container-fluid';
            } else {
                this.containerType = 'container';
                // detect the specific fixed variant for the Max width dropdown
                var self = this;
                var found = 'container';
                ['container-sm', 'container-md', 'container-lg', 'container-xl', 'container-xxl'].forEach(function (c) {
                    if (node.classList.contains(c)) found = c;
                });
                this.maxWidthClass = found;
            }

            // LE redesign — reflect current align + space classes.
            var align = null;
            ['text-start', 'text-center', 'text-end'].forEach(function (c) {
                if (node.classList.contains(c)) align = c;
            });
            this.alignClass = align;
            this.spaceValue = this._readSpace(node, this.spaceMode);
        },


        applyClassToActiveContainerNode: function (val) {
            if (!this.isReady) {
                return;
            }
            if (this.activeContainerNode) {



                if (val === 'container-fluid') {
                    mw.top().app.dispatch('mw.elementStyleEditor.removeClassFromNode', {
                        node: this.activeContainerNode,
                        class: 'container'
                    });
                    mw.top().app.dispatch('mw.elementStyleEditor.addClassToNode', {
                        node: this.activeContainerNode,
                        class: 'container-fluid'
                    });
                } else {
                    mw.top().app.dispatch('mw.elementStyleEditor.removeClassFromNode', {
                        node: this.activeContainerNode,
                        class: 'container-fluid'
                    });
                    mw.top().app.dispatch('mw.elementStyleEditor.addClassToNode', {
                        node: this.activeContainerNode,
                        class: 'container'
                    });
                }

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
            if (elementStyleEditorShow !== 'container') {
                this.showContainer = false;
            }
        });


        // mw.top().app.on('mw.elementStyleEditor.selectNode', (element) => {
        //     this.populateStyleEditor(element)
        // });
    },

    watch: {
        '$root.selectedElement': {
            handler: function (element) {
                if(element) {
                    this.populateStyleEditor(element);
                }
            },
            deep: true
        },
        // LE redesign — Width/Max width now apply explicitly via setWidth/setMaxWidth
        // (family-strip), so containerType is pure state (no apply-on-watch).

    },


}
</script>


