<template>
    <label v-if="setting.title" class="live-edit-label">{{ setting.title }}</label>
    <div v-if="setting.description" class="mt-1">
        <small>{{ setting.description }}</small>
    </div>

    <div class="mt-2">
        <!-- Iframe wrapper for rendering elements with canvas styles - always visible now -->
        <div ref="iframeContainer" class="iframe-wrapper"></div>
    </div>
</template>

<script>
export default {
    inject: ['templateSettings'],
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
        }
    },
    data() {
        return {
            iframe: null,
            isDarkMode: false,
            fontCallbacks: [],
            currentStylePack: null,
            previousStylePack: null, // Track the previously selected style pack
            fontsLoaded: false,
            fontsToLoad: [],
        }
    },
    watch: {
        // Watch for changes in layout mode
        isLayoutMode() {
            this.$nextTick(() => {
                this.updateIframeContent();
            });
        },

        // Watch for changes in active layout ID
        activeLayoutId() {
            this.$nextTick(() => {
                this.updateIframeContent();
            });
        }
    },
    mounted() {
        // First scan and load fonts, then initialize the iframe
        this.scanAndLoadFonts();

        this.isDarkMode = mw.top().app.theme.isDark();

        mw.top().app.theme.on('change', (isDark) => {
            this.isDarkMode = mw.top().app.theme.isDark();


            // this.isDarkMode = mw.top().app.theme.isDark();
            // if (this.iframe && this.iframe.contentDocument) {
            //     this.updateIframeContent();
            //
            //     // Re-inject styles when theme changes
            //     this.injectCanvasStyles();
            //     this.updateIframeContent();
            // }
        });
        //

        // Give a small timeout to allow font loading to start
        setTimeout(() => {
            this.initIframeWrapper();
            this.setupFontChangeListener();
            this.setupCssReloadListener();
        }, 100);
    },
    beforeUnmount() {
        // Clean up event listeners
        if (mw.top() && mw.top().app) {
            mw.top().app.off('fontsManagerSelectedFont');
            mw.top().app.canvas.off('reloadCustomCssDone');
        }
    },
    methods: {
        // Scan for font-family properties and load them
        scanAndLoadFonts() {
            if (!this.setting.fieldSettings || !this.setting.fieldSettings.styleProperties) {
                console.log('No style properties to scan for fonts');
                return;
            }

            const fontFamilyProperties = [];
            const fontManager = mw.top()?.app?.fontManager;

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

            console.log('Found font-family properties:', fontFamilyProperties);

            // Parse and load each font family
            this.fontsToLoad = []; // Reset the fonts list
            fontFamilyProperties.forEach(fontFamilyStr => {
                if (fontManager.parseFontFamilies) {
                    // Use parseFontFamilies to extract font names
                    const fontFamilies = fontManager.parseFontFamilies(fontFamilyStr);
                    fontFamilies.forEach(family => {
                        if (family && !this.fontsToLoad.includes(family)) {

                            if (fontManager.isGenericFontFamily(family)) {
                                // Skip generic names
                                return;
                            }

                            this.fontsToLoad.push(family);
                        }
                    });
                }
            });

            console.log('Fonts to load:', this.fontsToLoad);

            // Load each font in the parent window
            this.fontsToLoad.forEach(family => {
                fontManager.loadNewFontTemp(family);
            });

            this.fontsLoaded = true;
        },

        // New method to inject fonts into iframe
        injectFontsIntoIframe() {
            if (!this.iframe || !this.iframe.contentDocument || !this.fontsToLoad.length) return;

            const fontManager = mw.top()?.app?.fontManager;
            if (!fontManager) return;

            const iframeDoc = this.iframe.contentDocument;
            const iframeHead = iframeDoc.head;

            console.log('Injecting fonts into iframe:', this.fontsToLoad);

            this.fontsToLoad.forEach(family => {
                const fontUrl = fontManager.getFontUrl(family);
                if (!fontUrl) return;

                // Create a unique ID for this font link
                const fontId = 'font-' + family.replace(/[^a-zA-Z0-9]/g, '');

                // Skip if already added
                if (iframeDoc.getElementById(fontId)) return;

                // Create and append link element
                const link = iframeDoc.createElement('link');
                link.id = fontId;
                link.rel = 'stylesheet';
                link.href = fontUrl;
                link.setAttribute("referrerpolicy", "no-referrer");
                link.setAttribute("crossorigin", "anonymous");
                link.setAttribute("data-noprefix", "1");

                iframeHead.appendChild(link);
                console.log('Injected font into iframe:', family, fontUrl);
            });
        },

        applyStylePack(stylePack) {
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
                        false, // record = true to track the changes
                        false // skipMedia = false
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

            // Update the current style pack and refresh the iframe
            this.currentStylePack = stylePack;
            this.updateIframeContent();

            this.$emit('style-pack-applied', {
                selector: this.selectorToApply,
                stylePack: stylePack
            });
        },

        getSelectorName(selector) {
            // Remove any preceding dots/hashes and provide a readable name
            if (selector === ':root') {
                return 'Root';
            }

            return selector.replace(/^[.#]/g, '');
        },

        initIframeWrapper() {
            // Create iframe element
            this.iframe = document.createElement('iframe');

            this.iframe.allowTransparency = true;
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
                this.injectCanvasStyles();
                this.updateIframeContent();
                this.injectFontsIntoIframe();
                mw.top().tools.iframeAutoHeight(this.iframe)
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
                borderColor: '#f5f5f5',
                backgroundColor: '#f5f5f5',
                backgroundColorHover: '#f9f9f9',
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
                            padding: 10px;
                            border-radius: 8px;
                            transition: all 0.2s;
                            border: 1px solid var(--border-color);
                            margin-bottom: 10px;
                            background-color: var(--background-color);
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
                        /* Format display types */
                        .preview-display-block {
                            display: block;
                        }
                        .preview-display-flex {
                            display: flex;
                            gap: 10px;
                            flex-wrap: wrap;
                        }
                        .preview-display-flexZoom {
                            display: flex;
                            gap: 5px;
                            flex-wrap: wrap;
                            zoom: 0.5;
                        }
                        .style-preview-element {
                            flex: 1;
                            min-width: 0;
                            display: flex;
                            justify-content: center;
                        }
                        .preview-display-block .style-preview-element {
                            display: block;
                            margin-bottom: 10px;
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
                            color: var(--text-color);
                            padding-left: 0;
                            overflow: hidden;
                            box-sizing: border-box;
                            display:block;
                            font-weight: semibold;

                        }


                    </style>
                </head>
                <body>
                    <div id="preview-content"></div>
                </body>
                </html>
            `;
        },

        injectCanvasStyles() {
            if (!this.iframe || !this.iframe.contentWindow || !this.iframe.contentDocument) return;

            try {
                const canvasDocument = mw.top().app.canvas.getDocument();
                const canvasWindow = mw.top().app.canvas.getWindow();
                const iframeDoc = this.iframe.contentDocument;
                const iframeHead = iframeDoc.head;

                // Get all stylesheets from canvas
                const sheets = canvasDocument.querySelectorAll('[rel="stylesheet"],style,[type="text/css"]');

                sheets.forEach(sheet => {
                    try {
                        if (sheet.tagName === 'LINK' && sheet.href) {
                            // Copy external stylesheets
                            const link = iframeDoc.createElement('link');
                            link.rel = 'stylesheet';
                            link.href = sheet.href;
                            link.type = 'text/css';
                            iframeHead.appendChild(link);
                        } else if (sheet.tagName === 'STYLE') {
                            // Copy inline styles
                            const style = iframeDoc.createElement('style');
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


        updateIframeContent() {
            if (!this.iframe || !this.iframe.contentDocument) return;

            const iframeDoc = this.iframe.contentDocument;
            const previewContent = iframeDoc.getElementById('preview-content');

            if (!previewContent) return;

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

            // Render all style packs
            if (this.setting.fieldSettings && this.setting.fieldSettings.styleProperties) {
                this.setting.fieldSettings.styleProperties.forEach((stylePack, index) => {
                    const stylePackElement = this.createStylePackElement(stylePack, index, iframeDoc);
                    contentWrapper.appendChild(stylePackElement);
                });
            }
        },

        createStylePackElement(stylePack, index, iframeDoc) {
            const stylePackDiv = iframeDoc.createElement('div');
            stylePackDiv.className = 'style-pack-item';
            stylePackDiv.onclick = () => this.applyStylePack(stylePack);

            const innerDiv = iframeDoc.createElement('div');
            innerDiv.className = 'd-flex flex-column';

            // Create preview elements
            const previewDiv = iframeDoc.createElement('div');
            // Apply display format class based on previewElementsFormat prop or setting
            const displayFormat = this.previewElementsFormat;
            previewDiv.className = `preview-display-${displayFormat} cursor-pointer style-pack-preview main`;

            if (this.setting.previewElements && this.setting.previewElements.length > 0) {
                // Use actual preview elements
                this.setting.previewElements.forEach(preview => {
                    const previewElement = iframeDoc.createElement('div');
                    previewElement.className = 'style-preview-element';

                    const component = iframeDoc.createElement(preview.tag || 'div');
                    component.className = `preview-component ${preview.class || ''}`;
                    component.textContent = preview.label || '';

                    const attrs = preview.attributes || {};

                    Object.keys(attrs).forEach(attr => {
                        component.setAttribute(attr, attrs[attr]);
                    });

                    // Apply style pack properties to preview element
                    if (stylePack.properties) {
                        Object.keys(stylePack.properties).forEach(property => {
                            const cssProperty = property.replace(/([A-Z])/g, '-$1').toLowerCase();
                            component.style.setProperty(cssProperty, stylePack.properties[property]);
                        });
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
                labelDiv.className = 'form-control-live-edit-label-wrapper text-center';

                const label = iframeDoc.createElement('label');
                label.textContent = stylePack.label;
                label.className = 'live-edit-label';

                labelDiv.appendChild(label);
                innerDiv.appendChild(labelDiv);
            }

            stylePackDiv.appendChild(innerDiv);
            return stylePackDiv;
        },

        setupFontChangeListener() {
            if (mw.top() && mw.top().app) {
                mw.top().app.on('fontsManagerSelectedFont', (e) => {
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

        setupCssReloadListener() {
            mw.top().app.canvas.on('liveEditCanvasLoaded', () => {
                // Re-inject fonts when canvas is loaded
                this.injectFontsIntoIframe();
                this.injectCanvasStyles();
                this.updateIframeContent();
                console.log('Page changed, refreshing style pack preview');

            });


            if (mw.top() && mw.top().app && mw.top().app.canvas) {
                mw.top().app.canvas.on('reloadCustomCssDone', () => {
                    // Re-inject fonts when CSS is reloaded
                    this.injectFontsIntoIframe();
                    this.injectCanvasStyles();
                    this.updateIframeContent();

                    console.log('CSS reloaded, refreshing style pack preview');
                });
            }
        }
    }
}
</script>
