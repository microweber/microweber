<template>

    <ElementStyleEditorActiveNode></ElementStyleEditorActiveNode>


    <div v-if="!selectedElement" class="mb-4">
        <div class="alert alert-primary">Please select an element to edit</div>
    </div>

    <div class="mb-4" :class="{'style-editor-disabled': !selectedElement}">
        <div class="d-flex flex-column">

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showTypography">
                <ElementStyleEditorTypography></ElementStyleEditorTypography>
            </div>

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showBackground">
                <ElementStyleEditorBackground></ElementStyleEditorBackground>
            </div>

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showSpacing">
                <ElementStyleEditorSpacing></ElementStyleEditorSpacing>
            </div>

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showContainer">
                <ElementStyleEditorContainer></ElementStyleEditorContainer>
            </div>

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showGrid">
                <ElementStyleEditorGrid></ElementStyleEditorGrid>
            </div>

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showBorder">
                <ElementStyleEditorBorder></ElementStyleEditorBorder>
            </div>

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showRoundedCorners">
                <ElementStyleEditorRoundedCorners></ElementStyleEditorRoundedCorners>
            </div>

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showAnimations">
                <ElementStyleEditorAnimations></ElementStyleEditorAnimations>
            </div>

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showClassApplier">
                <ElementStyleEditorShadow></ElementStyleEditorShadow>
            </div>

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showClassApplier">
                <ElementStyleEditorClassApplier></ElementStyleEditorClassApplier>
            </div>


            <!--
            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showPosition">
                            <ElementStyleEditorPosition></ElementStyleEditorPosition>
                        </div>-->

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showListStyleEditor">
                <ElementStyleEditorUlOlListStyleEditor></ElementStyleEditorUlOlListStyleEditor>
            </div>




            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showLayoutSettings">
                <ElementStyleEditorLayoutSettings></ElementStyleEditorLayoutSettings>
            </div>
            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showPredefinedStylesApplierSettings">
                <ElementStyleEditorPredefinesStylesApplier></ElementStyleEditorPredefinesStylesApplier>
            </div>

            <div class="mb-4 element-style-editor-toggle-wrapper" v-show="showAiChatSettings">
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
        }
    },

    methods: {

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


