<template>

    <ElementStyleEditorActiveNode></ElementStyleEditorActiveNode>

    <!-- AI-63 / TICKET-NN (cycle-76 2026-05-08): aria-live announce
         region. Style changes had zero feedback for screen-reader
         users — the visual update is instant but AT users got
         nothing. polite=announce after the SR finishes its current
         utterance (vs assertive which interrupts). atomic=true so
         the whole message replays even if only part of the text
         changed. visually-hidden to keep sighted users unaffected —
         purely an AT signal channel. -->
    <div role="status"
         aria-live="polite"
         aria-atomic="true"
         class="visually-hidden mw-ese-announce"
         data-mw-ese-announce>
        {{ ariaAnnouncement }}
    </div>

    <div v-if="!selectedElement" class="mb-4">
        <div class="alert alert-primary">Please select an element to edit</div>
    </div>

    <div class="mb-4" :class="{'style-editor-disabled': !selectedElement}">
        <div class="d-flex flex-column">

            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isTypographyActive}" v-show="showTypography" @click="toggleTypography" role="button" tabindex="0" :aria-expanded="isTypographyActive" @keydown.enter.prevent="toggleTypography" @keydown.space.prevent="toggleTypography">
                <ElementStyleEditorTypography ref="typographyComp"></ElementStyleEditorTypography>
            </div>

            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isBackgroundActive}" v-show="showBackground" @click="toggleBackground" role="button" tabindex="0" :aria-expanded="isBackgroundActive" @keydown.enter.prevent="toggleBackground" @keydown.space.prevent="toggleBackground">
                <ElementStyleEditorBackground ref="backgroundComp"></ElementStyleEditorBackground>
            </div>

            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isSpacingActive}" v-show="showSpacing" @click="toggleSpacing" role="button" tabindex="0" :aria-expanded="isSpacingActive" @keydown.enter.prevent="toggleSpacing" @keydown.space.prevent="toggleSpacing">
                <ElementStyleEditorSpacing ref="spacingComp"></ElementStyleEditorSpacing>
            </div>

            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isContainerActive}" v-show="showContainer" @click="toggleContainer" role="button" tabindex="0" :aria-expanded="isContainerActive" @keydown.enter.prevent="toggleContainer" @keydown.space.prevent="toggleContainer">
                <ElementStyleEditorContainer ref="containerComp"></ElementStyleEditorContainer>
            </div>

            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isGridActive}" v-show="showGrid" @click="toggleGrid" role="button" tabindex="0" :aria-expanded="isGridActive" @keydown.enter.prevent="toggleGrid" @keydown.space.prevent="toggleGrid">
                <ElementStyleEditorGrid ref="gridComp"></ElementStyleEditorGrid>
            </div>

            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isBorderActive}" v-show="showBorder" @click="toggleBorder" role="button" tabindex="0" :aria-expanded="isBorderActive" @keydown.enter.prevent="toggleBorder" @keydown.space.prevent="toggleBorder">
                <ElementStyleEditorBorder ref="borderComp"></ElementStyleEditorBorder>
            </div>

            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isRoundedCornersActive}" v-show="showRoundedCorners" @click="toggleRoundedCorners" role="button" tabindex="0" :aria-expanded="isRoundedCornersActive" @keydown.enter.prevent="toggleRoundedCorners" @keydown.space.prevent="toggleRoundedCorners">
                <ElementStyleEditorRoundedCorners ref="roundedCornersComp"></ElementStyleEditorRoundedCorners>
            </div>

            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isAnimationsActive}" v-show="showAnimations" @click="toggleAnimations" role="button" tabindex="0" :aria-expanded="isAnimationsActive" @keydown.enter.prevent="toggleAnimations" @keydown.space.prevent="toggleAnimations">
                <ElementStyleEditorAnimations ref="animationsComp"></ElementStyleEditorAnimations>
            </div>

            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isShadowActive}" v-show="showClassApplier" @click="toggleShadow" role="button" tabindex="0" :aria-expanded="isShadowActive" @keydown.enter.prevent="toggleShadow" @keydown.space.prevent="toggleShadow">
                <ElementStyleEditorShadow ref="shadowComp"></ElementStyleEditorShadow>
            </div>

            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isClassApplierActive}" v-show="showClassApplier" @click="toggleClassApplier" role="button" tabindex="0" :aria-expanded="isClassApplierActive" @keydown.enter.prevent="toggleClassApplier" @keydown.space.prevent="toggleClassApplier">
                <ElementStyleEditorClassApplier ref="classApplierComp"></ElementStyleEditorClassApplier>
            </div>
            <!--
            <div class="mb-1 element-style-editor-toggle-wrapper" :class="{'active': isPositionActive}" v-show="showPosition" @click="togglePosition" role="button" tabindex="0" :aria-expanded="isPositionActive" @keydown.enter.prevent="togglePosition" @keydown.space.prevent="togglePosition">
                            <ElementStyleEditorPosition></ElementStyleEditorPosition>
                        </div>-->

            <div class="element-style-editor-toggle-wrapper" :class="{'active': isListStyleEditorActive}" v-show="showListStyleEditor" @click="toggleListStyleEditor" role="button" tabindex="0" :aria-expanded="isListStyleEditorActive" @keydown.enter.prevent="toggleListStyleEditor" @keydown.space.prevent="toggleListStyleEditor">
                <ElementStyleEditorUlOlListStyleEditor></ElementStyleEditorUlOlListStyleEditor>
            </div>

            <div class="element-style-editor-toggle-wrapper" :class="{'active': isLayoutSettingsActive}" v-show="showLayoutSettings" @click="toggleLayoutSettings" role="button" tabindex="0" :aria-expanded="isLayoutSettingsActive" @keydown.enter.prevent="toggleLayoutSettings" @keydown.space.prevent="toggleLayoutSettings">
                <ElementStyleEditorLayoutSettings></ElementStyleEditorLayoutSettings>
            </div>
            <div class="element-style-editor-toggle-wrapper" :class="{'active': isPredefinedStylesApplierSettingsActive}" v-show="showPredefinedStylesApplierSettings" @click="togglePredefinedStylesApplier" role="button" tabindex="0" :aria-expanded="isPredefinedStylesApplierSettingsActive" @keydown.enter.prevent="togglePredefinedStylesApplier" @keydown.space.prevent="togglePredefinedStylesApplier">
                <ElementStyleEditorPredefinesStylesApplier></ElementStyleEditorPredefinesStylesApplier>
            </div>

            <div class="element-style-editor-toggle-wrapper" :class="{'active': isAiChatSettingsActive}" v-show="showAiChatSettings" @click="toggleAiChatSettings" role="button" tabindex="0" :aria-expanded="isAiChatSettingsActive" @keydown.enter.prevent="toggleAiChatSettings" @keydown.space.prevent="toggleAiChatSettings">
                <ElementStyleEditorAiChat></ElementStyleEditorAiChat>
            </div>

        </div>
    </div>


</template>

<script>
import ElementStyleEditorActiveNode from './ElementStyleEditorActiveNode.vue';
import ElementStyleEditorTypography from './ElementStyleEditorTypography.vue';
import ElementStyleEditorSpacing from './ElementStyleEditorSpacing.vue';
import ElementStyleEditorBackground from './ElementStyleEditorBackground.vue';
import ElementStyleEditorBorder from './ElementStyleEditorBorder.vue';
import ElementStyleEditorElementSelector from './ElementStyleEditorElementSelector.vue';
import ElementStyleEditorContainer from './ElementStyleEditorContainer.vue';
import ElementStyleEditorAnimations from './ElementStyleEditorAnimations.vue';
import ElementStyleEditorGrid from './ElementStyleEditorGrid.vue';
import ElementStyleEditorClassApplier from './ElementStyleEditorClassApplier.vue';
import ElementStyleEditorRoundedCorners from "./ElementStyleEditorRoundedCorners.vue";
import ElementStyleEditorShadow from "./ElementStyleEditorShadow.vue";
import ElementStyleEditorLayoutSettings from "./ElementStyleEditorLayoutSettings.vue";
import ElementStyleEditorPosition from "./ElementStyleEditorPosition.vue";
import ElementStyleEditorUlOlListStyleEditor from "./ElementStyleEditorUlOlListStyleEditor.vue";
import ElementStyleEditorPredefinesStylesApplier from "./ElementStyleEditorPredefinesStylesApplier.vue";
import ElementStyleEditorAiChat from "./ElementStyleEditorAiChat.vue";

export default {
    components: {
        ElementStyleEditorAiChat,
        ElementStyleEditorActiveNode,
        ElementStyleEditorBackground,
        ElementStyleEditorRoundedCorners,
        ElementStyleEditorElementSelector,
        ElementStyleEditorTypography,
        ElementStyleEditorSpacing,
        ElementStyleEditorBorder,
        ElementStyleEditorContainer,
        ElementStyleEditorGrid,
        ElementStyleEditorAnimations,
        ElementStyleEditorShadow,
        ElementStyleEditorClassApplier,
        ElementStyleEditorLayoutSettings,
        ElementStyleEditorPosition,
        ElementStyleEditorUlOlListStyleEditor,
        ElementStyleEditorPredefinesStylesApplier,
    },

    data() {
        return {
            selectedElement: null,
            selectedLayout: null,
            showElementSelector: true,
            showBackground: true,
            showTypography: true,
            showSpacing: true,
            showBorder: true,
            showContainer: true,
            showGrid: true,
            showAnimations: true,
            showClassApplier: true,
            showRoundedCorners: true,
            showPosition: true,
            showListStyleEditor: true,
            showLayoutSettings: true,
            showImageSettings: true,
            showPredefinedStylesApplierSettings: true,
            showAiChatSettings: true,

            // Active states for when the user has clicked to open
            isTypographyActive: false,
            isBackgroundActive: false,
            isSpacingActive: false,
            isBorderActive: false,
            isContainerActive: false,
            isGridActive: false,
            isAnimationsActive: false,
            isClassApplierActive: false,
            isShadowActive: false,
            isRoundedCornersActive: false,
            isPositionActive: false,
            isListStyleEditorActive: false,
            isLayoutSettingsActive: false,
            isPredefinedStylesApplierSettingsActive: false,
            isAiChatSettingsActive: false,

            // AI-63 / TICKET-NN (cycle-76 2026-05-08): announce
            // panel-open transitions to screen readers via the
            // aria-live region above. Updated by announceAria()
            // helper in methods{}. Empty string on first paint so
            // the region doesn't fire on mount.
            ariaAnnouncement: '',
        }
    },

    methods: {
        // AI-63 / TICKET-NN (cycle-76 2026-05-08): push a message
        // into the aria-live region. Two-step write (clear, then
        // set on next tick) so the SR re-announces even when the
        // user opens the same panel twice in a row — without the
        // clear, identical consecutive text wouldn't trigger a
        // re-announce on most engines.
        announceAria(message) {
            const text = typeof message === 'string' ? message.trim() : '';
            this.ariaAnnouncement = '';
            this.$nextTick(() => {
                this.ariaAnnouncement = text;
            });
        },

        // Reset all active states
        resetActiveStates() {
            this.isTypographyActive = false;
            this.isBackgroundActive = false;
            this.isSpacingActive = false;
            this.isBorderActive = false;
            this.isContainerActive = false;
            this.isGridActive = false;
            this.isAnimationsActive = false;
            this.isClassApplierActive = false;
            this.isShadowActive = false;
            this.isRoundedCornersActive = false;
            this.isPositionActive = false;
            this.isListStyleEditorActive = false;
            this.isLayoutSettingsActive = false;
            this.isPredefinedStylesApplierSettingsActive = false;
            this.isAiChatSettingsActive = false;
        },

        /**
         * task-2026-05-05-96635c — close the local `showXxx` state
         * inside every child component. The wrapper-level click sets
         * the parent's `isXxxActive` for active-row styling, but
         * each child component owns a SEPARATE local `showXxx`
         * boolean that controls whether its expanded panel content
         * renders. Without resetting those, opening one panel and
         * then clicking another would leave the first panel's
         * content rendered (it never gets a `showXxx = false`),
         * producing the bug the user reported: clicking Border
         * showed Rounded Corners content because the child
         * component's local toggle state was stale.
         */
        closeAllChildPanels(except) {
            const refs = ['typographyComp', 'backgroundComp', 'spacingComp', 'containerComp',
                'gridComp', 'borderComp', 'roundedCornersComp', 'animationsComp',
                'shadowComp', 'classApplierComp'];
            refs.forEach(refName => {
                if (refName === except) return;
                const child = this.$refs[refName];
                if (!child) return;
                // Each child has a different `showXxx` field name —
                // probe the known set rather than maintaining a map.
                ['showTypography', 'showBackground', 'showSpacing', 'showContainer',
                 'showGrid', 'showBorder', 'showRoundedCorners', 'showAnimations',
                 'showShadow', 'showClassApplier'].forEach(field => {
                    if (field in child) child[field] = false;
                });
            });
        },

        // Methods to toggle active states
        toggleTypography() {
            this.resetActiveStates();
            this.announceAria('Typography panel opened');
            this.isTypographyActive = true;
            this.closeAllChildPanels('typographyComp');
            const c = this.$refs.typographyComp; if (c && 'showTypography' in c) c.showTypography = true;
        },

        toggleBackground() {
            this.resetActiveStates();
            this.announceAria('Background panel opened');
            this.isBackgroundActive = true;
            this.closeAllChildPanels('backgroundComp');
            const c = this.$refs.backgroundComp; if (c && 'showBackground' in c) c.showBackground = true;
        },

        toggleSpacing() {
            this.resetActiveStates();
            this.announceAria('Spacing panel opened');
            this.isSpacingActive = true;
            this.closeAllChildPanels('spacingComp');
            const c = this.$refs.spacingComp; if (c && 'showSpacing' in c) c.showSpacing = true;
        },

        toggleContainer() {
            this.resetActiveStates();
            this.announceAria('Container panel opened');
            this.isContainerActive = true;
            this.closeAllChildPanels('containerComp');
            const c = this.$refs.containerComp; if (c && 'showContainer' in c) c.showContainer = true;
        },

        toggleGrid() {
            this.resetActiveStates();
            this.announceAria('Grid panel opened');
            this.isGridActive = true;
            this.closeAllChildPanels('gridComp');
            const c = this.$refs.gridComp; if (c && 'showGrid' in c) c.showGrid = true;
        },

        toggleBorder() {
            this.resetActiveStates();
            this.announceAria('Border panel opened');
            this.isBorderActive = true;
            this.closeAllChildPanels('borderComp');
            const c = this.$refs.borderComp; if (c && 'showBorder' in c) c.showBorder = true;
        },

        toggleRoundedCorners() {
            this.resetActiveStates();
            this.announceAria('Rounded corners panel opened');
            this.isRoundedCornersActive = true;
            this.closeAllChildPanels('roundedCornersComp');
            const c = this.$refs.roundedCornersComp; if (c && 'showRoundedCorners' in c) c.showRoundedCorners = true;
        },

        toggleAnimations() {
            this.resetActiveStates();
            this.announceAria('Animations panel opened');
            this.isAnimationsActive = true;
            this.closeAllChildPanels('animationsComp');
            const c = this.$refs.animationsComp; if (c && 'showAnimations' in c) c.showAnimations = true;
        },

        toggleShadow() {
            this.resetActiveStates();
            this.announceAria('Shadow panel opened');
            this.isShadowActive = true;
            this.closeAllChildPanels('shadowComp');
            const c = this.$refs.shadowComp; if (c && 'showShadow' in c) c.showShadow = true;
        },

        toggleClassApplier() {
            this.resetActiveStates();
            this.announceAria('CSS class applier opened');
            this.isClassApplierActive = true;
            this.closeAllChildPanels('classApplierComp');
            const c = this.$refs.classApplierComp; if (c && 'showClassApplier' in c) c.showClassApplier = true;
        },

        togglePosition() {
            this.resetActiveStates();
            this.announceAria('Position panel opened');
            this.isPositionActive = true;
        },

        toggleListStyleEditor() {
            this.resetActiveStates();
            this.announceAria('List style editor opened');
            this.isListStyleEditorActive = true;
        },

        toggleLayoutSettings() {
            this.resetActiveStates();
            this.announceAria('Layout settings opened');
            this.isLayoutSettingsActive = true;
        },

        togglePredefinedStylesApplier() {
            this.resetActiveStates();
            this.announceAria('Predefined styles applier opened');
            this.isPredefinedStylesApplierSettingsActive = true;
        },

        toggleAiChatSettings() {
            this.resetActiveStates();
            this.announceAria('AI chat settings opened');
            this.isAiChatSettingsActive = true;
        },

        applyPropertyToActiveNode(activeNode, prop, val) {


            mw.top().app.dispatch('mw.elementStyleEditor.applyCssPropertyToNode', {
                node: activeNode,
                prop: prop,
                val: val
            });
        },

    },
    mounted() {
        var ActiveSelector = false;
        var ActiveNode = false;
        var OverlayNode = false;
        var targetWindow = mw.top().app.canvas.getWindow();
        if (targetWindow) {
            var targetMw = targetWindow.mw;

        }
        var specialCases = function (property, value) {
            if (!property) return;
            if (property.includes('col-')) {
                scColumns(property, value)
                return true;
            } else if (OverlayNode && property === 'overlay-color') {
                OverlayNode.style.backgroundColor = value;
                mw.top().app.registerChange(OverlayNode);
                return true;
            } else if (OverlayNode && property === 'overlay-blend-mode') {
                OverlayNode.style.mixBlendMode = value;
                mw.top().app.registerChange(OverlayNode);
                return true;
            }

        }

        $(document).ready(function () {

            window.ActiveNode = mw.top().app.liveEdit.getSelectedNode();
            if (ActiveNode) {
                selectNodeInElementStyleEditorApp(ActiveNode);
            }


            window.document.addEventListener('refreshSelectedElement', function (e) {

                ActiveNode = mw.top().app.liveEdit.getSelectedNode();
                if (ActiveNode) {
                    selectNodeInElementStyleEditorApp(ActiveNode);
                }

            });

            mw.top().app.on('cssEditorSelectElementBySelector', function (selector) {
                var canvasDocument = mw.top().app.canvas.getDocument();

                if (selector) {
                    ActiveNode = canvasDocument.querySelector(selector);
                    if (!ActiveNode) {

                        var newEl = $.jseldom(selector);

                        var holder = canvasDocument.querySelector('#mw-non-existing-temp-element-holder');
                        if (!holder) {
                            holder = canvasDocument.createElement('div');
                            holder.id = 'mw-non-existing-temp-element-holder';
                            holder.style.display = 'none';
                            canvasDocument.body.append(holder);
                        }
                        if (newEl) {
                            holder = canvasDocument.getElementById('mw-non-existing-temp-element-holder');
                            holder.append(newEl[0]);
                        }
                        ActiveNode = canvasDocument.querySelector(selector);

                    }
                    ActiveSelector = selector;
                    selectNodeInElementStyleEditorApp(ActiveNode);

                }
            });
        });


        mw.top().app.on('mw.elementStyleEditor.addClassToNode', function (data) {
            // (data.class, data.node);
            if (data.node) {
                data.node.classList.add(data.class);
                mw.top().app.registerChange(data.node);
            }
        });

        mw.top().app.on('mw.elementStyleEditor.removeClassFromNode', function (data) {
            if (data.node) {
                data.node.classList.remove(data.class);
                mw.top().app.registerChange(data.node);
            }
        });


        mw.top().app.on('mw.elementStyleEditor.applyCssPropertyToNode', function (data) {
            output(data.prop, data.val, data.node);
        });


        var output = function (property, value, ActiveNode) {


//console.log('CSSoutput', property, value, ActiveNode)


            var mwTarget = targetMw;
            if (ActiveNode) {

                if (!specialCases(property, value)) {
                    let prop = property.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
                    // if(prop.indexOf('webkit') === 0) {
                    //     prop = `-${prop}`;
                    // }
                    if (ActiveSelector) {
                        mw.top().app.cssEditor.setPropertyForSelector(ActiveSelector, prop, value)
                    }

                    if (ActiveNode.style && ActiveNode.style[prop]) {
                        ActiveNode.style[prop] = '';
                    }
                    mw.top().app.cssEditor.temp(ActiveNode, prop, value)
                }
                mw.top().app.registerChange(ActiveNode);

                if (mw.top().app.liveEdit) {
                    mw.top().app.liveEdit.handles.get('interactionHandle').hide()

                    mw.top().app.liveEdit.handles.reposition();
                }
            }

        };
        var output = function (property, value, ActiveNode) {


//console.log('CSSoutput', property, value, ActiveNode)


            if (ActiveNode) {

                if (!specialCases(property, value)) {
                    let prop = property.replace(/([a-z])([A-Z])/g, '$1-$2').toLowerCase();
                    // if(prop.indexOf('webkit') === 0) {
                    //     prop = `-${prop}`;
                    // }
                    if (ActiveSelector) {
                        mw.top().app.cssEditor.setPropertyForSelector(ActiveSelector, prop, value)
                    }

                    if (ActiveNode.style && ActiveNode.style[prop]) {
                        ActiveNode.style[prop] = '';
                    }
                    mw.top().app.cssEditor.temp(ActiveNode, prop, value)
                }
                mw.top().app.registerChange(ActiveNode);

                if (mw.top().app.liveEdit) {
                    mw.top().app.liveEdit.handles.get('interactionHandle').hide()

                    mw.top().app.liveEdit.handles.reposition();
                }
            }

        };
        mw.top().app.on('mw.elementStyleEditor.applyCssPropertyToNode', function (data) {


            output(data.prop, data.val, data.node);
        });


        mw.top().tools.iframeAutoHeight(frameElement)
        mw.top().app.on('cssEditorSettings', (settings) => {





            if (settings.fieldSettings.components) {

                this.showBackground = false;
                this.showTypography = false;
                this.showSpacing = false;
                this.showBorder = false;
                this.showElementSelector = false;
                this.showContainer = false;
                this.showAnimations = false;
                this.showClassApplier = false;
                this.showPosition = false;

                this.showListStyleEditor = false;
                this.showLayoutSettings = false;
                this.showAiChatSettings = false;


                if (settings.fieldSettings.components.includes('background')) {
                    this.showBackground = true;
                }
                if (settings.fieldSettings.components.includes('elementSelector')) {
                    this.showElementSelector = true;
                }
                if (settings.fieldSettings.components.includes('typography')) {
                    this.showTypography = true;
                }
                if (settings.fieldSettings.components.includes('spacing')) {
                    this.showSpacing = true;
                }

                if (settings.fieldSettings.components.includes('border')) {
                    this.showBorder = true;
                }
                if (settings.fieldSettings.components.includes('container')) {
                    this.showContainer = true;
                }
                if (settings.fieldSettings.components.includes('grid')) {
                    this.showGrid = true;
                }

                if (settings.fieldSettings.components.includes('animations')) {
                    this.showAnimations = true;
                }

                if (settings.fieldSettings.components.includes('roundedCorners')) {
                    this.showRoundedCorners = true;
                }
                if (settings.fieldSettings.components.includes('position')) {
                    this.showPosition = true;
                }

                if (settings.fieldSettings.components.includes('classes')) {
                    this.showClassApplier = true;
                }

                if (settings.fieldSettings.components.includes('listStyleEditor')) {
                    this.showListStyleEditor = true;
                }

                if (settings.fieldSettings.components.includes('layoutSettings')) {
                    this.showLayoutSettings = true;
                }

                if (settings.fieldSettings.components.includes('imageSettings')) {
                    this.showImageSettings = true;
                }

                if (settings.fieldSettings.components.includes('predefinedStylesApplierSettings')) {
                    this.showPredefinedStylesApplierSettings = true;
                }


                if (settings.fieldSettings.components.includes('aiChat')) {



                    this.showAiChatSettings = true;
                }

            }
        });

    },


}
</script>
