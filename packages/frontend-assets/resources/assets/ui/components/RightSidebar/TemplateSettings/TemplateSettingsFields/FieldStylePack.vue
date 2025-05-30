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
    }, data() {
        return {
            iframe: null,
            fontCallbacks: [],
            currentStylePack: null,
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
        this.initIframeWrapper();

        // Listen for font changes
        this.setupFontChangeListener();

        // Listen for custom CSS reload events
        this.setupCssReloadListener();
    },
    beforeUnmount() {
        // Clean up event listeners
        if (mw.top() && mw.top().app) {
            mw.top().app.off('fontsManagerSelectedFont');
            mw.top().app.canvas.off('reloadCustomCssDone');
        }
    },
    methods: {
        applyStylePack(stylePack) {
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


            this.iframe.className = 'preview-iframe';
            this.iframe.style.width = '100%';
            this.iframe.style.height = '400px';
            this.iframe.style.border = '1px solid #dee2e6';
            this.iframe.style.borderRadius = '6px';
            this.iframe.style.colorScheme   = 'normal';


            // Append to container
            this.$refs.iframeContainer.appendChild(this.iframe);

            // Initialize iframe content after it's loaded
            this.iframe.onload = () => {
                this.injectCanvasStyles();
                this.updateIframeContent();

                mw.top().tools.iframeAutoHeight(this.iframe)
            };

            // Set initial content with empty container
            this.iframe.srcdoc = `
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset="utf-8">
                    <title>Style Pack Preview</title>
                    <style>
                        body {
                            margin: 0;
                            padding: 0px;
                            background-color: transparent !important;
                            background: transparent !important;

                        }
                        .style-pack-container {
                            display: flex;
                            flex-direction: column;
                            gap: 15px;
                        }
                        .style-pack-item {
                            cursor: pointer;
                            padding: 5px;
                            border-radius: 8px;
                            transition: all 0.2s;
                            border: 1px solid #dee2e6;
                            margin-bottom: 10px;
                            background-color: transparent;
                        }
                        .style-pack-item:hover {
                            background-color: transparent;
                            border-color: #007bff;
                            transform: translateY(-2px);
                            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
                        }
                        .style-preview-item {
                            padding: 8px 15px;
                            border-radius: 6px;
                            background-color: #f8f9fa;
                            border: 1px solid #dee2e6;
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
                        .preview-component {
                            font-size: 14px;
                            padding: 8px 12px !important;
                            border-radius: 4px;
                            width: 100%;
                        }
                        .style-label {
                            text-align: center;
                            font-weight: 500;
                            color: #495057;
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
        }, updateIframeContent() {
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
            previewDiv.className = `preview-display-${displayFormat} cursor-pointer style-pack-preview`;

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
                labelDiv.className = 'style-label mt-1';

                const small = iframeDoc.createElement('small');
                small.textContent = stylePack.label;

                labelDiv.appendChild(small);
                innerDiv.appendChild(labelDiv);
            }

            stylePackDiv.appendChild(innerDiv);
            return stylePackDiv;
        },

        setupFontChangeListener() {
            if (mw.top() && mw.top().app) {
                mw.top().app.on('fontsManagerSelectedFont', (e) => {
                    if (typeof e.fontFamily !== 'undefined') {
                        // Refresh iframe content when font changes
                        this.injectCanvasStyles();
                        this.updateIframeContent();

                        console.log('Font changed:', e.fontFamily);
                    }
                });
            }
        },

        setupCssReloadListener() {
            if (mw.top() && mw.top().app && mw.top().app.canvas) {
                mw.top().app.canvas.on('reloadCustomCssDone', () => {
                    // Refresh iframe content when CSS is reloaded
                    this.injectCanvasStyles();
                    this.updateIframeContent();

                    console.log('CSS reloaded, refreshing style pack preview');
                });
            }
        }
    }
}
</script>
