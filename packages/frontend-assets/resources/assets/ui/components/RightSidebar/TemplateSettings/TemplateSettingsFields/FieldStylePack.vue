<template>
    <div v-if="!this.isSingleSettingMode">
        <label v-if="setting.title" class="live-edit-label">{{ setting.title }}</label>
        <div v-if="setting.description" class="mt-1">
            <small>{{ setting.description }}</small>
        </div>
    </div>
    <div class="mt-2">
        <!-- Back button that appears only when style pack opener is expanded -->
        <FieldBackButton
            v-if="isStylePackOpenerMode && stylePacksExpanded && !isSingleSettingMode"
            :button-text="'Back to styles'"
            :current-path="'/'"
            :show-button="true"
            @go-back="collapseStylePacks"
        />

        <!-- Iframe wrapper for rendering elements with canvas styles - always visible now -->
        <div ref="iframeContainer" class="iframe-wrapper"></div>
    </div>
</template>

<script>
import FieldBackButton from './FieldBackButton.vue';

export default {
    inject: ['templateSettings'],
    components: {
        FieldBackButton
    },
    props: {
        setting: {
            type: Object,
            required: true
        },
        selectorToApply: {
            type: String,
            default: ''
        },
        rootSelector: {
            type: String,
            default: ''
        },
        isSingleSettingMode: {
            type: Boolean,
            default: false
        }
    },
    computed: {
        isLayoutMode() {
            return this.templateSettings && this.templateSettings.applyMode === 'layout';
        },

        activeLayoutId() {
            return this.templateSettings && this.isLayoutMode ? this.templateSettings.activeLayoutId : null;
        },

        // Get display format from setting or default to 'block'
        previewElementsFormat() {
            return this.setting.previewElementsFormat || 'block';
        },

        // Check if stylePackOpener mode is enabled
        isStylePackOpenerMode() {
            return this.setting.previewElementsMode === 'stylePackOpener' &&
                Array.isArray(this.setting.previewElementsStyleProperties) &&
                this.setting.previewElementsStyleProperties.length > 0;
        }
    },
    data() {
        // Set stylePacksExpanded to true by default if this is the Predefined styles selection
        const isPredefinedStyles = this.setting.title === "Predefined styles selection";

        // Auto-expand if in single setting mode and is a style pack opener
        const autoExpand = this.isSingleSettingMode &&
            this.setting.previewElementsMode === 'stylePackOpener';

        console.log('FieldStylePack data initialization:', {
            title: this.setting.title,
            previewElementsMode: this.setting.previewElementsMode,
            isSingleSettingMode: this.isSingleSettingMode,
            isPredefinedStyles,
            autoExpand,
            willExpand: isPredefinedStyles || autoExpand
        });

        return {
            iframe: null,
            isDarkMode: window.mw?.top()?.app?.theme?.isDark() || false,
            fontCallbacks: [],
            currentStylePack: null,
            previousStylePack: null,
            fontsLoaded: false,
            fontsToLoad: [],
            stylePacksExpanded: isPredefinedStyles || autoExpand,
            uniqueId: 'style-pack-' + Math.random().toString(36).substr(2, 9),
            selectedStylePackProperties: null,
            loadingStylePackIndex: null,
            lastContentHash: null,
            activeStylePackIndex: null, // Add this to track active style pack
        }
    },
    watch: {
        // Watch for changes in layout mode
        isLayoutMode(newVal, oldVal) {
            // Only update if actually changed to avoid unnecessary reloads
            if (newVal !== oldVal) {
                console.log('Layout mode changed from', oldVal, 'to', newVal, '- refreshing style pack previews');
                this.$nextTick(() => {
                    // Clear the content hash to force a refresh
                    this.lastContentHash = null;
                    // Refresh the iframe content to update previews with new mode variables
                    this.updateIframeContent();
                    // Re-inject canvas styles for the new mode
                    this.injectCanvasStyles();


                    window.mw.top().app.dispatch('stylePackGlobalReload', {
                        reason: 'layoutModeChanged',
                        sourceComponentId: this.uniqueId,
                        activeLayoutId: this.activeLayoutId
                    });


                });
            }
        },

        // Watch for changes in active layout ID
        activeLayoutId(newVal, oldVal) {
            // Only update if actually changed to avoid unnecessary reloads
            if (newVal !== oldVal) {
                console.log('Active layout ID changed from', oldVal, 'to', newVal, '- refreshing style pack previews');
                this.$nextTick(() => {
                    // Clear the content hash to force a refresh
                    this.lastContentHash = null;
                    // Refresh the iframe content to update previews with new layout variables
                    this.updateIframeContent();
                    // Re-inject canvas styles for the new layout
                    this.injectCanvasStyles();

                    window.mw.top().app.dispatch('stylePackGlobalReload', {
                        reason: 'layoutModeChanged',
                        sourceComponentId: this.uniqueId,
                        activeLayoutId: this.activeLayoutId
                    });
                });
            }
        },

        // Watch for changes in expanded state and emit event
        stylePacksExpanded(newVal) {
            this.$emit('style-pack-expanded-state', {
                id: this.uniqueId,
                isExpanded: newVal
            });
        },

        // Watch for changes in single setting mode
        isSingleSettingMode(newValue) {
            if (newValue && this.setting.previewElementsMode === 'stylePackOpener' && !this.stylePacksExpanded) {
                console.log('Auto-expanding style pack in single setting mode');
                this.stylePacksExpanded = true;
                this.$nextTick(() => {
                    this.updateIframeContent();
                    // Emit expanded state
                    this.$emit('style-pack-expanded-state', {
                        id: this.uniqueId,
                        isExpanded: true
                    });
                });
            }
        }
    },
    mounted() {
        console.log('FieldStylePack mounted with setting:', this.setting);

        // First scan and load fonts
        this.scanAndLoadFonts();

        if (window.mw?.top()?.app?.theme) {
            this.isDarkMode = window.mw.top().app.theme.isDark();

            window.mw.top().app.theme.on('change', (isDark) => {
                this.isDarkMode = window.mw.top().app.theme.isDark();
            });
        }

        // Setup event listeners
        setTimeout(() => {
            this.initIframeWrapper();
            this.setupFontChangeListener();
            this.setupCssReloadListener();
            this.setupStylePackGlobalReloadListener();
            this.setupLayoutElementSelectionListener();
        }, 100);
    },
    beforeUnmount() {
        // Clean up event listeners
        if (window.mw?.top()?.app) {
            window.mw.top().app.off('fontsManagerSelectedFont');
            window.mw.top().app.off('setPropertyForSelector');
            window.mw.top().app.off('stylePackGlobalReload');
            window.mw.top().app.off('layoutElementSelected');
            window.mw.top().app.off('layoutChanged');
        }

        if (window.mw?.top()?.app?.canvas) {
            window.mw.top().app.canvas.off('liveEditCanvasLoaded');
            window.mw.top().app.canvas.off('reloadCustomCssDone');
        }
    },
    methods: {
        // Scan for font-family properties and load them
        scanAndLoadFonts() {
            if (!this.setting.fieldSettings || !this.setting.fieldSettings.styleProperties) {
                console.log('No style properties to scan for fonts');
                return;
            }

            if (this.fontsLoaded && this.fontsToLoad.length > 0) {
                console.log('Fonts already loaded, skipping scan');
                return;
            }

            const fontFamilyProperties = [];
            const fontManager = window.mw?.top()?.app?.fontManager;

            if (!fontManager) {
                console.warn('Font manager not available');
                return;
            }

            // Scan all style packs for font-family properties
            this.setting.fieldSettings.styleProperties.forEach(stylePack => {
                if (!stylePack.properties) return;

                Object.entries(stylePack.properties).forEach(([key, value]) => {
                    if (key.endsWith('-font-family')) {
                        fontFamilyProperties.push(value);
                    }
                });
            });

            // Parse and load each font family
            this.fontsToLoad = [];
            fontFamilyProperties.forEach(fontFamilyStr => {
                if (fontManager.parseFontFamilies) {
                    const fontFamilies = fontManager.parseFontFamilies(fontFamilyStr);
                    fontFamilies.forEach(family => {
                        if (family && !this.fontsToLoad.includes(family)) {
                            if (fontManager.isGenericFontFamily(family)) {
                                return;
                            }
                            this.fontsToLoad.push(family);
                        }
                    });
                }
            });

            // Load each font in the parent window
            this.fontsToLoad.forEach(family => {
                fontManager.loadNewFontTemp(family);
            });

            this.fontsLoaded = true;
        },

        applyStylePack(stylePack, stylePackIndex = null) {
            // Set loading state for this style pack
            this.loadingStylePackIndex = stylePackIndex;

            // Set active style pack index
            this.activeStylePackIndex = stylePackIndex;

            // Update iframe to show loading state
            this.updateIframeContent();

            // Use setTimeout to ensure the loading state is visible before processing
            setTimeout(() => {
                // After updating the opener, collapse the style packs ONLY if NOT in single setting mode
                if (this.isStylePackOpenerMode && this.stylePacksExpanded && !this.isSingleSettingMode) {
                    this.collapseStylePacks();
                }

                // Unset properties from the previous style pack before applying the new one
                if (this.previousStylePack && this.previousStylePack.properties) {
                    const selector = this.selectorToApply || this.rootSelector;
                    const propertiesToUnset = {};

                    // Create an object with empty values for all previous properties
                    Object.keys(this.previousStylePack.properties).forEach(property => {
                        propertiesToUnset[property] = '';
                    });

                    // Unset all properties from the previous style pack
                    if (Object.keys(propertiesToUnset).length > 0) {
                        window.mw.top().app.cssEditor.setPropertyForSelectorBulk(
                            selector,
                            propertiesToUnset,
                            false,
                            false
                        );
                    }
                }

                // Store the current style pack as previous before applying the new one
                this.previousStylePack = this.currentStylePack;

                if (stylePack.properties) {
                    const updates = [];
                    Object.keys(stylePack.properties).forEach(property => {
                        updates.push({
                            selector: this.selectorToApply || this.rootSelector,
                            property: property,
                            value: stylePack.properties[property]
                        });
                    });

                    if (updates.length > 0) {
                        this.$emit('batch-update', updates);
                    }
                }

                // Store the selected style pack properties for the opener display
                this.selectedStylePackProperties = {...stylePack.properties};

                // Update previewElementsStyleProperties for the opener preview
                if (
                    this.isStylePackOpenerMode &&
                    this.setting.previewElementsStyleProperties &&
                    this.setting.previewElementsStyleProperties.length > 0
                ) {
                    // Update the opener preview with selected style properties
                    this.setting.previewElementsStyleProperties[0].properties = {...stylePack.properties};

                    // Also update the label if available
                    if (stylePack.label && this.setting.previewElementsStyleProperties[0]) {
                        this.setting.previewElementsStyleProperties[0].label = stylePack.label;
                    }
                }

                // Update the current style pack
                this.currentStylePack = stylePack;

                // Clear loading state after processing
                this.loadingStylePackIndex = null;
                this.updateIframeContent();

                // Emit global event to reload all other style pack preview components
                console.log('Emitting global style pack reload event');
                if (window.mw?.top()?.app) {
                    if (!this.isSingleSettingMode) {
                        window.mw.top().app.dispatch('stylePackGlobalReload', {
                            reason: 'applyStylePack',
                            sourceComponentId: this.uniqueId,
                            appliedStylePack: stylePack,
                            selector: this.selectorToApply || this.rootSelector
                        });
                    }
                }

                this.$emit('style-pack-applied', {
                    selector: this.selectorToApply,
                    stylePack: stylePack
                });
            }, 50);
        },

        // Setup font change listener
        setupFontChangeListener() {
            if (window.mw?.top()?.app) {
                window.mw.top().app.on('fontsManagerSelectedFont', (e) => {
                    if (typeof e.fontFamily !== 'undefined') {
                        // Add newly selected font to our list
                        if (!this.fontsToLoad.includes(e.fontFamily)) {
                            this.fontsToLoad.push(e.fontFamily);
                        }

                        // Inject the new font and refresh the iframe
                        this.injectFontsIntoIframe();
                        this.injectCanvasStyles();
                        this.updateIframeContent();

                        console.log('Font changed:', e.fontFamily);
                    }
                });
            }
        },

        // Setup CSS reload listener
        setupCssReloadListener() {
            if (window.mw?.top()?.app?.canvas) {
                window.mw.top().app.canvas.on('liveEditCanvasLoaded', () => {
                    // Re-inject fonts when canvas is loaded
                    this.injectFontsIntoIframe();
                    this.injectCanvasStyles();
                    // Clear content hash to force refresh with new variables
                    this.lastContentHash = null;
                    this.updateIframeContent();
                    console.log('Page changed, refreshing style pack preview');
                });

                window.mw.top().app.canvas.on('reloadCustomCssDone', () => {
                    // Re-inject fonts when CSS is reloaded
                    this.injectFontsIntoIframe();
                    this.injectCanvasStyles();
                    // Clear content hash to force refresh with new variables
                    this.lastContentHash = null;
                    this.updateIframeContent();
                    console.log('CSS reloaded, refreshing style pack preview');
                });
            }

            // Listen for CSS property changes to refresh style pack previews
            if (window.mw?.top()?.app) {
                window.mw.top().app.on('setPropertyForSelector', (data) => {
                    // Only refresh if the property change affects CSS variables
                    if (data.property && data.property.startsWith('--')) {
                        console.log('CSS variable changed:', data.property, 'refreshing style pack preview');
                        // Clear content hash to force refresh with new variables
                        this.lastContentHash = null;
                        this.$nextTick(() => {
                            this.updateIframeContent();
                        });
                    }
                });
            }
        },

        // Setup global reload listener for style packs
        setupStylePackGlobalReloadListener() {
            if (window.mw?.top()?.app) {
                window.mw.top().app.on('stylePackGlobalReload', (eventData) => {
                    console.log('Global style pack reload triggered', eventData);

                    // Skip reload if this is the source component that triggered the event
                    if (eventData && eventData.sourceComponentId === this.uniqueId) {
                        console.log('Skipping reload for source component', this.uniqueId);
                        return;
                    }



                    // Clear content hash to force refresh when style pack is applied globally
                    // This ensures the iframe updates with new CSS variables from the applied style pack
                    this.lastContentHash = null;

                    // Only reload if fonts or canvas styles actually need updating
                    if (eventData && (
                        eventData.reason === 'fontChange'
                        || eventData.reason === 'applyStylePack'
                        || eventData.reason === 'layoutModeChanged'
                        || eventData.reason === 'cssReload'
                    )) {
                        // Re-scan and load fonts
                        this.scanAndLoadFonts();
                        // Re-inject fonts and update iframe content
                        this.injectFontsIntoIframe();
                        this.injectCanvasStyles();
                    }



                    // Always update iframe content for other events
                    this.updateIframeContent();
                });
            }
        },

        // Setup layout element selection listener
        setupLayoutElementSelectionListener() {
            if (window.mw?.top()?.app) {
                // Listen for layout element selection changes
                window.mw.top().app.on('layoutElementSelected', (data) => {
                    if (data && data.layoutId && data.layoutId !== this.activeLayoutId) {
                        console.log('Layout element selected:', data.layoutId, 'refreshing style pack preview');
                        // Clear content hash to force refresh with new layout variables
                        this.lastContentHash = null;
                        this.$nextTick(() => {
                            this.updateIframeContent();
                        });
                    }
                });

                // Listen for layout changes or additions
                window.mw.top().app.on('layoutChanged', (data) => {
                    console.log('Layout changed event received, refreshing style pack preview');
                    // Clear content hash to force refresh
                    this.lastContentHash = null;
                    this.$nextTick(() => {
                        this.updateIframeContent();
                    });
                });
            }
        },

        // Method to expand style packs
        expandStylePacks() {
            this.stylePacksExpanded = true;
            this.updateIframeContent();

            // Emit event when expanded state changes
            this.$emit('style-pack-expanded-state', {
                id: this.uniqueId,
                isExpanded: this.stylePacksExpanded
            });
        },

        // Method to collapse style packs
        collapseStylePacks() {
            this.stylePacksExpanded = false;
            this.updateIframeContent();

            // Emit event when expanded state changes
            this.$emit('style-pack-expanded-state', {
                id: this.uniqueId,
                isExpanded: this.stylePacksExpanded
            });
        },

        // New method to inject fonts into iframe
        injectFontsIntoIframe() {
            if (!this.iframe || !this.iframe.contentDocument || !this.fontsToLoad.length) return;

            const fontManager = window.mw?.top()?.app?.fontManager;
            if (!fontManager) return;

            const iframeDoc = this.iframe.contentDocument;
            const iframeHead = iframeDoc.head;

            console.log('Injecting fonts into iframe:', this.fontsToLoad);

            this.fontsToLoad.forEach(family => {
                const fontUrl = fontManager.getFontUrl(family);
                if (!fontUrl) return;

                // Create a unique ID for this font link
                const fontId = 'font-' + family.replace(/[^a-zA-Z0-9]/g, '');
                const preloadId = 'preload-' + fontId;

                // Skip if already added (check both preload and final font link)
                if (iframeDoc.getElementById(fontId) || iframeDoc.getElementById(preloadId)) return;

                // Create preload link for faster loading
                const preloadLink = iframeDoc.createElement('link');
                preloadLink.id = preloadId;
                preloadLink.rel = 'preload';
                preloadLink.href = fontUrl;
                preloadLink.as = 'style';
                preloadLink.setAttribute("referrerpolicy", "no-referrer");
                preloadLink.setAttribute("crossorigin", "anonymous");
                preloadLink.setAttribute("data-noprefix", "1");

                // Async stylesheet loading
                preloadLink.onload = function () {
                    const link = iframeDoc.createElement('link');
                    link.id = fontId;
                    link.rel = 'stylesheet';
                    link.href = fontUrl;
                    link.setAttribute("referrerpolicy", "no-referrer");
                    link.setAttribute("crossorigin", "anonymous");
                    link.setAttribute("data-noprefix", "1");
                    iframeHead.appendChild(link);
                    console.log('Async font loaded into iframe:', family, fontUrl);
                };

                // Fallback for browsers that don't support preload
                preloadLink.onerror = function () {
                    const link = iframeDoc.createElement('link');
                    link.id = fontId;
                    link.rel = 'stylesheet';
                    link.href = fontUrl;
                    link.setAttribute("referrerpolicy", "no-referrer");
                    link.setAttribute("crossorigin", "anonymous");
                    link.setAttribute("data-noprefix", "1");
                    iframeHead.appendChild(link);
                    console.log('Fallback font loaded into iframe:', family, fontUrl);
                };

                iframeHead.appendChild(preloadLink);
                console.log('Preloading font into iframe:', family, fontUrl);
            });
        },

        injectCanvasStyles() {
            if (!this.iframe || !this.iframe.contentWindow || !this.iframe.contentDocument) return;

            try {
                const canvasDocument = window.mw?.top()?.app?.canvas?.getDocument();
                const canvasWindow = window.mw?.top()?.app?.canvas?.getWindow();
                if (!canvasDocument) return;

                const iframeDoc = this.iframe.contentDocument;
                const iframeHead = iframeDoc.head;

                // Get all stylesheets from canvas
                const sheets = canvasDocument.querySelectorAll('[rel="stylesheet"],style,[type="text/css"]');

                sheets.forEach((sheet, index) => {
                    try {
                        if (sheet.tagName === 'LINK' && sheet.href) {
                            // Create unique ID for this stylesheet
                            const styleId = 'canvas-style-' + index;
                            const preloadId = 'preload-canvas-style-' + index;

                            // Skip if already added
                            if (iframeDoc.getElementById(styleId)) return;

                            // Create preload link for faster loading
                            const preloadLink = iframeDoc.createElement('link');
                            preloadLink.id = preloadId;
                            preloadLink.rel = 'preload';
                            preloadLink.href = sheet.href;
                            preloadLink.as = 'style';
                            preloadLink.type = 'text/css';

                            // Async stylesheet loading
                            preloadLink.onload = function () {
                                // Convert preload to stylesheet once loaded
                                const link = iframeDoc.createElement('link');
                                link.id = styleId;
                                link.rel = 'stylesheet';
                                link.href = sheet.href;
                                link.type = 'text/css';
                                iframeHead.appendChild(link);
                            };

                            // Fallback for browsers that don't support preload
                            preloadLink.onerror = function () {
                                const link = iframeDoc.createElement('link');
                                link.id = styleId;
                                link.rel = 'stylesheet';
                                link.href = sheet.href;
                                link.type = 'text/css';
                                iframeHead.appendChild(link);
                            };

                            iframeHead.appendChild(preloadLink);
                        } else if (sheet.tagName === 'STYLE') {
                            // Copy inline styles immediately (no async needed for inline styles)
                            const styleId = 'canvas-inline-style-' + index;

                            // Skip if already added
                            if (iframeDoc.getElementById(styleId)) return;

                            const style = iframeDoc.createElement('style');
                            style.id = styleId;
                            style.type = 'text/css';
                            style.textContent = sheet.textContent;
                            iframeHead.appendChild(style);
                        }
                    } catch (error) {
                        console.warn('Could not inject stylesheet:', error);
                    }
                });

                console.log('Injected', sheets.length, 'stylesheets into iframe');
            } catch (error) {
                console.error('Error injecting canvas styles:', error);
            }
        },

        // New method to toggle style packs expansion
        toggleStylePacksExpansion() {
            this.stylePacksExpanded = !this.stylePacksExpanded;
            this.updateIframeContent();

            // Emit event when expanded state changes with unique ID
            this.$emit('style-pack-expanded-state', {
                id: this.uniqueId,
                isExpanded: this.stylePacksExpanded
            });
        },

        // New method to create opener element
        createOpenerElement(iframeDoc) {
            const openerDiv = iframeDoc.createElement('div');
            openerDiv.className = 'style-pack-opener';
            openerDiv.onclick = () => this.toggleStylePacksExpansion();

            const innerDiv = iframeDoc.createElement('div');
            innerDiv.className = 'd-flex flex-column';

            // Create preview elements
            const previewDiv = iframeDoc.createElement('div');
            previewDiv.className = `preview-display-${this.previewElementsFormat} cursor-pointer style-pack-preview main`;

            if (this.setting.previewElements && this.setting.previewElements.length > 0) {
                // Use actual preview elements from setting
                this.setting.previewElements.forEach(preview => {
                    const previewElement = iframeDoc.createElement('div');
                    previewElement.className = 'style-preview-element';

                    const component = iframeDoc.createElement(preview.tag || 'div');
                    component.className = `preview-component main ${preview.class || ''}`;
                    component.textContent = preview.label || '';

                    const attrs = preview.attributes || {};
                    Object.keys(attrs).forEach(attr => {
                        component.setAttribute(attr, attrs[attr]);
                    });

                    // Apply the style properties from previewElementsStyleProperties to the component
                    if (this.setting.previewElementsStyleProperties &&
                        this.setting.previewElementsStyleProperties.length > 0 &&
                        this.setting.previewElementsStyleProperties[0].properties) {

                        const styleProps = this.setting.previewElementsStyleProperties[0].properties;

                        // Use the special opener method to apply current variables (for the opener only)
                        this.applyCurrentVariablesToOpener(openerDiv, {properties: styleProps});

                        // Apply font properties based on element type
                        this.applyFontProperties(component, styleProps, preview.tag);
                    }

                    previewElement.appendChild(component);
                    previewDiv.appendChild(previewElement);
                });
            }

            innerDiv.appendChild(previewDiv);

            // Add label if available from previewElementsStyleProperties
            if (this.setting.previewElementsStyleProperties &&
                this.setting.previewElementsStyleProperties[0] &&
                this.setting.previewElementsStyleProperties[0].label) {

                const labelDiv = iframeDoc.createElement('div');
                labelDiv.className = 'form-control-live-edit-label-wrapper predefined-styles-names';

                const label = iframeDoc.createElement('label');
                label.textContent = this.setting.previewElementsStyleProperties[0].label;
                label.className = 'live-edit-label';

                labelDiv.appendChild(label);
                innerDiv.appendChild(labelDiv);
            }

            openerDiv.appendChild(innerDiv);
            return openerDiv;
        },

        applyFontProperties(component, styleProps, tag) {
            if (tag === 'h1' || tag === 'h2' || tag === 'h3' ||
                tag === 'h4' || tag === 'h5' || tag === 'h6') {
                // Apply heading font styles
                if (styleProps['--mw-body-font-family']) {
                    component.style.fontFamily = styleProps['--mw-body-font-family'];
                }
                if (styleProps['--mw-heading-font-weight']) {
                    component.style.fontWeight = styleProps['--mw-heading-font-weight'];
                }
                if (styleProps['--mw-heading-font-size']) {
                    component.style.fontSize = styleProps['--mw-heading-font-size'];
                }
            } else if (tag === 'p' || tag === 'div' || tag === 'span') {
                // Apply paragraph font styles
                if (styleProps['--mw-body-font-family']) {
                    component.style.fontFamily = styleProps['--mw-body-font-family'];
                }
                if (styleProps['--mw-paragraph-font-weight']) {
                    component.style.fontWeight = styleProps['--mw-paragraph-font-weight'];
                }
                if (styleProps['--mw-paragraph-font-size']) {
                    component.style.fontSize = styleProps['--mw-paragraph-font-size'];
                }
            }
        },

        updateIframeContent() {
            if (!this.iframe || !this.iframe.contentDocument) return;

            const iframeDoc = this.iframe.contentDocument;
            const previewContent = iframeDoc.getElementById('preview-content');

            if (!previewContent) return;

            // Avoid unnecessary DOM manipulation if content hasn't changed
            const currentContentHash = JSON.stringify({
                isStylePackOpenerMode: this.isStylePackOpenerMode,
                stylePacksExpanded: this.stylePacksExpanded,
                isLayoutMode: this.isLayoutMode,
                activeLayoutId: this.activeLayoutId,
                loadingStylePackIndex: this.loadingStylePackIndex,
                currentStylePack: this.currentStylePack?.label || null,
                selectedStylePackProperties: this.selectedStylePackProperties,
                settingsCount: this.setting.fieldSettings?.styleProperties?.length || 0,
                // Include variables hash for opener mode
                openerVariablesHash: this.isStylePackOpenerMode && this.setting.previewElementsStyleProperties?.[0]?.properties ?
                    Object.keys(this.getCurrentCssVariables()).filter(prop =>
                        this.setting.previewElementsStyleProperties[0].properties[prop]
                    ).sort().join(',') : '' + Object.keys(this.setting.selectedStylePackProperties).join(',')
            });

            if (this.lastContentHash === currentContentHash) {
                console.log('Skipping iframe update - content unchanged');
                return;
            }

            this.lastContentHash = currentContentHash;

            // Clear existing content
            previewContent.innerHTML = '';

            // Create a wrapper div for layout mode
            let contentWrapper = previewContent;
            if (this.isLayoutMode && this.activeLayoutId && this.activeLayoutId !== 'None') {
                const layoutWrapper = iframeDoc.createElement('div');
                layoutWrapper.id = this.activeLayoutId;
                layoutWrapper.className = 'layout-wrapper';
                previewContent.appendChild(layoutWrapper);
                contentWrapper = layoutWrapper;
            }

            // Handle stylePackOpener mode
            if (this.isStylePackOpenerMode) {
                // Create a container for all style packs
                const stylePackContainer = iframeDoc.createElement('div');
                stylePackContainer.className = 'style-pack-container';

                if (this.stylePacksExpanded) {
                    stylePackContainer.classList.add('expanded');
                }
                contentWrapper.appendChild(stylePackContainer);

                // Add the opener element if not expanded
                if (!this.stylePacksExpanded) {
                    const openerElement = this.createOpenerElement(iframeDoc);
                    stylePackContainer.appendChild(openerElement);
                }

                // Render all style packs (they will be hidden by CSS if not expanded)
                if (this.setting.fieldSettings && this.setting.fieldSettings.styleProperties) {
                    this.setting.fieldSettings.styleProperties.forEach((stylePack, index) => {
                        const stylePackElement = this.createStylePackElement(stylePack, index, iframeDoc);
                        stylePackContainer.appendChild(stylePackElement);
                    });
                }
            } else {
                // Regular rendering of all style packs
                if (this.setting.fieldSettings && this.setting.fieldSettings.styleProperties) {
                    this.setting.fieldSettings.styleProperties.forEach((stylePack, index) => {
                        const stylePackElement = this.createStylePackElement(stylePack, index, iframeDoc);
                        contentWrapper.appendChild(stylePackElement);
                    });
                }
            }
        },

        createStylePackElement(stylePack, index, iframeDoc) {
            const stylePackDiv = iframeDoc.createElement('div');
            stylePackDiv.className = 'style-pack-item';

            // Add loading class if this is the currently loading style pack
            if (this.loadingStylePackIndex === index) {
                stylePackDiv.classList.add('style-pack-loading-item');
            }

            // Add active class if this is the currently active style pack
            if (this.activeStylePackIndex === index) {
                stylePackDiv.classList.add('active');
            }

            // Accessibility: expose swatches as a roving-tabindex group of buttons
            // so the picker is keyboard-navigable. The first swatch (or the
            // active one, if any) owns `tabindex=0`; the rest get `-1` and
            // are reached by arrow keys. Enter/Space applies the pack.
            stylePackDiv.setAttribute('role', 'button');
            stylePackDiv.setAttribute('aria-label', stylePack.label || ('Style pack ' + (index + 1)));
            stylePackDiv.setAttribute(
                'aria-pressed',
                this.activeStylePackIndex === index ? 'true' : 'false'
            );
            const desiredTabIndex =
                this.activeStylePackIndex === index ||
                (this.activeStylePackIndex === null && index === 0)
                    ? '0'
                    : '-1';
            stylePackDiv.setAttribute('tabindex', desiredTabIndex);

            stylePackDiv.addEventListener('keydown', (event) => {
                const key = event.key;
                const items = Array.from(
                    iframeDoc.querySelectorAll('.style-pack-item')
                );
                const currentIdx = items.indexOf(stylePackDiv);
                if (currentIdx < 0) {
                    return;
                }
                const focusItem = (nextIndex) => {
                    const target = items[nextIndex];
                    if (!target) return;
                    items.forEach((el) => el.setAttribute('tabindex', '-1'));
                    target.setAttribute('tabindex', '0');
                    target.focus();
                };
                switch (key) {
                    case 'Enter':
                    case ' ':
                    case 'Spacebar':
                        event.preventDefault();
                        this.applyStylePack(stylePack, index);
                        break;
                    case 'ArrowRight':
                    case 'ArrowDown':
                        event.preventDefault();
                        focusItem((currentIdx + 1) % items.length);
                        break;
                    case 'ArrowLeft':
                    case 'ArrowUp':
                        event.preventDefault();
                        focusItem((currentIdx - 1 + items.length) % items.length);
                        break;
                    case 'Home':
                        event.preventDefault();
                        focusItem(0);
                        break;
                    case 'End':
                        event.preventDefault();
                        focusItem(items.length - 1);
                        break;
                }
            });

            stylePackDiv.onclick = () => this.applyStylePack(stylePack, index);

            const innerDiv = iframeDoc.createElement('div');
            innerDiv.className = 'd-flex flex-column';

            // Create preview elements
            const previewDiv = iframeDoc.createElement('div');
            // Apply display format class based on previewElementsFormat prop or setting
            const displayFormat = this.previewElementsFormat;
            previewDiv.className = `preview-display-${displayFormat} cursor-pointer style-pack-preview`;

            if (this.setting.previewElements && this.setting.previewElements.length > 0) {
                // Use actual preview elements
                this.setting.previewElements.forEach(preview => {
                    const previewElement = iframeDoc.createElement('div');
                    previewElement.className = 'style-preview-element';

                    const component = iframeDoc.createElement(preview.tag || 'div');
                    component.className = `preview-component main ${preview.class || ''}`;
                    component.textContent = preview.label || '';

                    const attrs = preview.attributes || {};
                    Object.keys(attrs).forEach(attr => {
                        component.setAttribute(attr, attrs[attr]);
                    });

                    // Apply style pack properties to the preview element using static values
                    if (stylePack.properties) {
                        // For individual style pack previews, use the original static values
                        // This ensures each preview shows its unique style, not the current state
                        Object.keys(stylePack.properties).forEach(property => {
                            const value = stylePack.properties[property];

                            // Apply the property to the element
                            if (property.startsWith('--')) {
                                component.style.setProperty(property, value);
                                stylePackDiv.style.setProperty(property, value);
                            } else {
                                const cssProperty = property.replace(/([A-Z])/g, '-$1').toLowerCase();
                                component.style[cssProperty] = value;
                                stylePackDiv.style[cssProperty] = value;
                            }
                        });

                        // Apply font properties based on element type
                        this.applyFontProperties(component, stylePack.properties, preview.tag);
                    }

                    previewElement.appendChild(component);
                    previewDiv.appendChild(previewElement);
                });
            } else {
                // Fallback to selector names
                if (this.setting.selectors) {
                    this.setting.selectors.forEach(selector => {
                        const previewItem = iframeDoc.createElement('div');
                        previewItem.className = 'style-preview-item';

                        const label = iframeDoc.createElement('span');
                        label.className = 'style-preview-label';
                        label.textContent = this.getSelectorName(selector);

                        previewItem.appendChild(label);
                        previewDiv.appendChild(previewItem);
                    });
                }
            }

            innerDiv.appendChild(previewDiv);

            // Add label if available
            if (stylePack.label) {
                const labelDiv = iframeDoc.createElement('div');
                labelDiv.className = 'form-control-live-edit-label-wrapper predefined-styles-names';

                const label = iframeDoc.createElement('label');
                label.textContent = stylePack.label;
                label.className = 'live-edit-label';

                labelDiv.appendChild(label);
                innerDiv.appendChild(labelDiv);
            }

            stylePackDiv.appendChild(innerDiv);
            return stylePackDiv;
        },

        getSelectorName(selector) {
            // Remove any preceding dots/hashes and provide a readable name
            if (selector === ':root') {
                return 'Root';
            }
            return selector.replace(/^[.#]/g, '');
        },

        initIframeWrapper() {
            // Add fancy loading animation
            const loadingEl = document.createElement('div');
            loadingEl.className = 'style-pack-loading';
            loadingEl.innerHTML = `
                <div class="spinner-container">
                    <div class="spinner">
                        <div class="bounce1"></div>
                        <div class="bounce2"></div>
                        <div class="bounce3"></div>
                    </div>
                    <div class="loading-text">Loading styles...</div>
                </div>
            `;

            // Add loading spinner styles
            const styleEl = document.createElement('style');
            styleEl.textContent = `
                .style-pack-loading {
                    width: 100%;
                    height: 200px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    background-color: rgba(255,255,255,0.8);
                    border-radius: 7px;
                }
                .spinner-container {
                    text-align: center;
                }
                .loading-text {
                    margin-top: 15px;
                    color: #007bff;
                    font-size: 14px;
                    font-weight: 500;
                }
                .spinner {
                    margin: 0 auto;
                    width: 70px;
                    text-align: center;
                }
                .spinner > div {
                    width: 12px;
                    height: 12px;
                    background-color: #007bff;
                    border-radius: 100%;
                    display: inline-block;
                    animation: sk-bouncedelay 1.4s infinite ease-in-out both;
                    margin: 0 3px;
                }
                .spinner .bounce1 {
                    animation-delay: -0.32s;
                }
                .spinner .bounce2 {
                    animation-delay: -0.16s;
                }
                @keyframes sk-bouncedelay {
                    0%, 80%, 100% {
                        transform: scale(0);
                    }
                    40% {
                        transform: scale(1.0);
                    }
                }
            `;

            // if(!document.head){
            //     console.warn('Document head not found, cannot append styles');
            //     return;
            // }
            //
            // document.head.appendChild(styleEl);

            if(!this.$refs.iframeContainer){
                // container not found, cannot append loading element
                // use has switched to a different tab or the component is not mounted
                return;
            }


            this.$refs.iframeContainer.appendChild(loadingEl);

            // Create iframe element
            this.iframe = document.createElement('iframe');
            this.iframe.allowTransparency = true;
            // this.iframe.loading = 'lazy';
            this.iframe.loading = 'eager';
            this.iframe.className = 'preview-iframe';
            this.iframe.style.width = '100%';
            this.iframe.style.height = '400px';
            this.iframe.style.border = 'none';
            this.iframe.style.borderRadius = '7px';
            this.iframe.style.colorScheme = 'normal';

            // Append to container
            this.$refs.iframeContainer.appendChild(this.iframe);

            // Initialize iframe content after it's loaded
            this.iframe.onload = () => {
                // Remove loading element when iframe is loaded
                if (loadingEl && loadingEl.parentNode) {
                    loadingEl.parentNode.removeChild(loadingEl);
                }

                this.injectCanvasStyles();
                this.updateIframeContent();
                this.injectFontsIntoIframe();
                if (window.mw?.top()?.tools?.iframeAutoHeight) {
                    window.mw.top().tools.iframeAutoHeight(this.iframe);
                }
                const isDark = window.mw?.top()?.app?.theme?.isDark();
                if (isDark) {
                    document.querySelectorAll('iframe.preview-iframe[srcdoc]')
                        .forEach(frame => frame.contentDocument.documentElement.classList[isDark ? 'add' : 'remove']('dark'))
                }
            };

            // Define color variables based on theme
            const lightThemeColors = {
                borderColor: '#dee2e6',
                backgroundColor: '#f2f2f2',
                backgroundColorHover: '#d7d7d7',
                itemBackgroundColor: '#f8f9fa',
                textColor: '#495057',
                accentColor: '#007bff',
                shadowColor: 'rgba(0,0,0,0.1)'
            };

            const darkThemeColors = {
                borderColor: 'rgb(242, 242, 242)',
                backgroundColor: 'rgb(242, 242, 242)',
                backgroundColorHover: 'rgb(215, 215, 215)',
                itemBackgroundColor: '#1a202c',
                textColor: '#e2e8f0',
                accentColor: '#63b3ed',
                shadowColor: 'rgba(0,0,0,0.3)'
            };

            // Select theme based on dark mode state
            const colors = this.isDarkMode ? darkThemeColors : lightThemeColors;

            // Set initial content with empty container
            this.iframe.srcdoc = `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Style Pack Preview</title>
                    <style>
                        ${this.getIframeStyles(colors)}
                    </style>
                </head>
                <body>
                    <div id="preview-content"></div>
                </body>
                </html>
            `;
        },

        getIframeStyles(colors) {
            return `
                :root {
                    --border-color: ${colors.borderColor};
                    --background-color: ${colors.backgroundColor};
                    --background-color-hover: ${colors.backgroundColorHover};
                    --item-background-color: ${colors.itemBackgroundColor};
                    --text-color: ${colors.textColor};
                    --accent-color: ${colors.accentColor};
                    --shadow-color: ${colors.shadowColor};
                }

                body {
                    margin: 0;
                    padding: 0px;
                    background-color: transparent !important;
                    background: transparent !important;
                    color: var(--text-color);
                    overflow: hidden;
                }

                .style-pack-container {
                    display: flex;
                    flex-direction: column;
                    gap: 15px;
                }

                .style-pack-item {
                    cursor: pointer;
                    padding: 27px 22px 22px;
                    border-radius: 8px;
                    transition: all 0.2s;
                    border: 4px solid transparent;
                    margin-bottom: 10px;
                    background-color: var(--background-color);
                    zoom: 87%;

                    &:hover, &:focus, &:active, &.active {
                        transform: scale(1.0);
                        border: 4px solid gold;
                        box-shadow: 0 2px 4px var(--shadow-color) !important;
                    }
                }

                .style-pack-item:hover {
                    box-shadow: 0 2px 4px var(--shadow-color);
                }

                .style-pack-opener {
                    cursor: pointer;
                    padding: 27px 28px 22px;
                    border-radius: 8px;
                    transition: all 0.2s;
                    border: 1px solid var(--border-color);
                    margin-bottom: 10px;
                    background-color: var(--background-color);
                    position: relative;
                    zoom: 90%;
                }

                .style-pack-opener:after {
                    content: '>';
                    position: absolute;
                    right: 6px;
                    bottom: -8px;
                    transform: translateY(-50%);
                    font-size: 16px;
                    color: #000;
                    opacity: 0.7;
                    transition: all 0.3s ease;
                    padding: 5px;
                    width: 25px;
                    height: 25px;
                    background: white;
                    border-radius: 999px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 1px solid var(--border-color);
                }

                .style-pack-opener:hover:after {
                    transform: translateY(-50%) translateX(3px);
                    color: #007bff;
                    opacity: 1;
                    text-shadow: 0 0 5px rgba(0,123,255,0.3);
                    animation: arrow-pulse 1s infinite alternate;
                }

                @keyframes arrow-pulse {
                    0% { transform: translateY(-50%) translateX(0px); }
                    100% { transform: translateY(-50%) translateX(5px); }
                }

                .style-pack-container.expanded .style-pack-item {
                    display: block;
                    position: relative;
                }

                .style-pack-container:not(.expanded) .style-pack-item {
                    display: none;
                }

                .style-preview-item {
                    padding: 8px 15px;
                    border-radius: 6px;
                    background-color: var(--item-background-color);
                    border: 1px solid var(--border-color);
                    text-align: center;
                    min-width: 80px;
                    font-size: 12px;
                }

                .preview-display-block {
                    display: block;
                }

                .preview-display-flex {
                    display: flex;
                    gap: 6px;
                }

                .preview-display-flexZoom {
                    display: flex;
                    gap: 0;
                    flex-wrap: wrap;
                    zoom: 0.6;
                }

                .style-preview-element {
                    flex: 1;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin: 0;
                    padding: 0;
                }

                .preview-display-block .style-preview-element {
                    display: block;
                }

                .style-preview-element p, .preview-component {
                    margin: 0;
                }

                .style-label {
                    text-align: center;
                    font-weight: 500;
                    color: var(--text-color);
                }

                .d-flex {
                    display: flex;
                }

                .flex-column {
                    flex-direction: column;
                }

                .gap-2 {
                    gap: 8px;
                }

                .cursor-pointer {
                    cursor: pointer;
                }

                .mt-1 {
                    margin-top: 0.25rem;
                }

                .live-edit-label {
                    padding: 0 2px;
                    text-rendering: optimizelegibility;
                    -webkit-font-smoothing: antialiased;
                    font-size: 9.75px;
                    letter-spacing: 0.75px;
                    text-overflow: ellipsis;
                    text-transform: uppercase;
                    padding-left: 0;
                    overflow: hidden;
                    box-sizing: border-box;
                    display: block;
                    font-weight: 300;
                    color: #0a0a0a;
                }

                .predefined-styles-names {
                    position: absolute;
                    bottom: -9px;
                    background: white;
                    padding: 2px 10px;
                    border: 1px solid #e5e5e5;
                    border-radius: 11px;
                }

                .color-palette-item {
                    border-radius: 7px;
                    height: 60px;
                    width: 100%;
                    max-width: 47px;
                    border: 1px solid var(--border-color);
                }

                .click-to-expand {
                    text-align: center;
                    font-size: 12px;
                    opacity: 0.7;
                    margin-top: 5px;
                    color: #000;
                }

                .style-pack-loading-item {
                    position: relative;
                    pointer-events: none;
                    opacity: 0.7;
                }

                .style-pack-loading-item::before {
                    content: '';
                    position: absolute;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 20px;
                    height: 20px;
                    border: 2px solid #f3f3f3;
                    border-top: 2px solid #007bff;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                    z-index: 10;
                }

                .style-pack-loading-item::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    left: 0;
                    right: 0;
                    bottom: 0;
                    background-color: rgba(255, 255, 255, 0.8);
                    border-radius: 8px;
                    z-index: 9;
                }

                @keyframes spin {
                    0% { transform: translate(-50%, -50%) rotate(0deg); }
                    100% { transform: translate(-50%, -50%) rotate(360deg); }
                }
            `;
        },

        // Method to get current CSS variables based on mode and layout
        getCurrentCssVariables() {
            const variables = {};

            try {
                // Get the canvas document to read CSS variables
                const canvasDocument = window.mw?.top()?.app?.canvas?.getDocument();
                if (!canvasDocument) {
                    console.warn('Canvas document not available, using fallback');
                    return variables;
                }

                let targetElement = canvasDocument.documentElement; // Default to :root

                // If in layout mode, get variables from the specific layout element
                if (this.isLayoutMode && this.activeLayoutId && this.activeLayoutId !== 'None') {
                    const layoutElement = canvasDocument.getElementById(this.activeLayoutId);
                    if (layoutElement) {
                        targetElement = layoutElement;
                        console.log('Getting CSS variables from layout element:', this.activeLayoutId);
                    } else {
                        console.warn('Layout element not found:', this.activeLayoutId);
                    }
                }

                // Get computed styles from the target element
                const computedStyles = canvasDocument.defaultView.getComputedStyle(targetElement);

                // Extract CSS custom properties (variables)
                for (let i = 0; i < computedStyles.length; i++) {
                    const property = computedStyles[i];
                    if (property.startsWith('--')) {
                        const value = computedStyles.getPropertyValue(property).trim();
                        if (value) { // Only include non-empty values
                            variables[property] = value;
                        }
                    }
                }

                // Also check for CSS variables in the canvas CSS editor if available
                if (window.mw?.top()?.app?.cssEditor) {
                    const cssEditor = window.mw.top().app.cssEditor;
                    const rootSelector = this.isLayoutMode && this.activeLayoutId && this.activeLayoutId !== 'None'
                        ? `#${this.activeLayoutId}`
                        : ':root';

                    // Try to get values from the CSS editor
                    const cssVars = cssEditor.getVariablesForSelector && cssEditor.getVariablesForSelector(rootSelector);
                    if (cssVars) {
                        Object.assign(variables, cssVars);
                    }
                }

                console.log('Current CSS variables for mode:', this.isLayoutMode ? 'layout' : 'template',
                    'activeLayoutId:', this.activeLayoutId, 'variables:', variables);
                return variables;
            } catch (error) {
                console.error('Error getting current CSS variables:', error);
                return variables;
            }
        },

        // Method to apply current CSS variables to the opener element only
        applyCurrentVariablesToOpener(element, stylePack) {
            if (!element || !stylePack?.properties) return;

            const currentVariables = this.getCurrentCssVariables();

            // For the opener, we want to show the current state of the selected style pack
            // So we apply current variables for all properties in the style pack
            Object.keys(stylePack.properties).forEach(property => {
                let value = stylePack.properties[property];

                // If the property is a CSS variable and we have a current value, use it
                if (property.startsWith('--') && currentVariables[property]) {
                    value = currentVariables[property];
                }

                // Apply the property to the element
                if (property.startsWith('--')) {
                    element.style.setProperty(property, value);
                } else {
                    const cssProperty = property.replace(/([A-Z])/g, '-$1').toLowerCase();
                    element.style[cssProperty] = value;
                }
            });

            console.log('Applied current variables to opener element');
        },
    }
};
</script>

<style scoped>
.iframe-wrapper {
    width: 100%;
    min-height: 100px;
}
</style>
