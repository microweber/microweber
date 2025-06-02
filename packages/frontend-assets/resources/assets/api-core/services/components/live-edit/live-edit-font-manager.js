import BaseComponent from "../../containers/base-class.js";


export class LiveEditFontManager extends BaseComponent {

    constructor() {
        super();

        this.selectedFont = 'Arial';
        this.applyToSelectedElement = null;

        this.fonts = [
            'Arial',
            'Tahoma',
            'Verdana',
            'Times New Roman',
            'Georgia',


            'Courier New',
            'sans-serif',
            'serif',
            'sans',
            'monospace',
            'system-ui',
        ];

        this.genericNames = [
            'serif', 'sans-serif',
            'monospace',
            'cursive',
            'fantasy',
            'system-ui',
            'ui-serif',
            'ui-sans-serif',
            'ui-monospace',
            'ui-rounded',
            'math',
            'emoji',
            'Arial',
            'Tahoma',
            'Verdana',
            'Times New Roman',
            'Georgia',


            'Courier New',
            'sans-serif',
            'segoe ui',

            'serif',
            'sans',
            'monospace',
            'system-ui',
        ];

        this.loadedNewFontsTemp = new Set();

        mw.app.on('onLiveEditReady', event => {
            this.init();
        });

        mw.app.on('liveEditCanvasLoaded', event => {
            this.init();
        });
        mw.app.on('fontsManagerSelectedFont', event => {
            mw.top().app.dispatch('hideFontsManager');
        });


    }


    init() {

        var liveEditIframeData = mw.top().app.canvas.getLiveEditData();
        if (liveEditIframeData
            && liveEditIframeData.template_config
            && liveEditIframeData.template_config.fonts

        ) {
            var template_config = liveEditIframeData.template_config;
            if (template_config.fonts && template_config.fonts.length > 0) {
                //merge fonts
                this.fonts = [...this.fonts, ...template_config.fonts];
            }
        }

        var canvasDocument = mw.top().app.canvas.getDocument();
        var canvasWindow = mw.top().app.canvas.getWindow();

        var liveEditIframeOnSave = mw.top().app.canvas.getLiveEditData();


    }

    selectFont(font) {
        this.selectedFont = font;
        if (!this.fonts.includes(font)) {
            this.fonts.push(font);
            this._fireFontsManagerChange();
        }
        mw.top().app.dispatch('fontsManagerSelectedFont', {
            fontFamily: this.selectedFont,
            applyToSelectedElement: this.applyToSelectedElement
        });
        this.reloadLiveEdit();
    }

    removeSelectedFont() {
        this.selectedFont = null;
    }

    reloadLiveEdit() {

        mw.top().app.canvas.dispatch('reloadCustomCss');


    }

    subscribeToSelectedFont(callback) {
        if (typeof callback === 'function') {
            mw.top().app.on('fontsManagerSelectedFont', (e) => {
                if (typeof e.fontFamily !== 'undefined') {
                    callback({
                        fontFamily: e.fontFamily,
                        applyToSelectedElement: e.applyToSelectedElement
                    });
                }
            });
        }
        return {
            fontFamily: this.selectedFont,
            applyToSelectedElement: this.applyToSelectedElement
        };
    }


    isGenericFontFamily(fontName) {
        if (!fontName) {
            return false;
        }
        // Normalize the font name to lowercase for comparison
        const normalizedFontName = fontName.toLowerCase();
        // Check if the font name is in the generic names list
        return this.genericNames.some(genericName => genericName.toLowerCase() === normalizedFontName);
    }


    addFont(font) {

        if (!this.fonts.includes(font)) {
            this.fonts.push(font);
            this._fireFontsManagerChange();
        }

        return this.getFonts();
    }

    addFonts(fonts) {
        if (fonts) {
            fonts.forEach(font => {
                if (!this.fonts.includes(font)) {
                    this.fonts.push(font);
                }
            });
            this._fireFontsManagerChange();
        }
        return this.getFonts();
    }

    _fireFontsManagerChange() {
        mw.top().app.dispatch('fontsManagerChange', this.getFonts());
    }

    removeFont(font) {

        this.fonts = this.fonts.filter(item => item !== font);
        this._fireFontsManagerChange();

        return this.getFonts();
    }

    getFonts() {
        const allFonts = new Set([...this.fonts]);
        this.loadedNewFontsTemp.forEach(family => {
            allFonts.add(family);
        });
        return Array.from(allFonts);
    }

    subscribe(callback) {
        if (typeof callback === 'function') {
            mw.top().app.on('fontsManagerChange', (e) => {
                callback(this.getFonts());
            });
        }
        return this.getFonts();
    }

    manageFonts(params) {


        mw.top().app.dispatch('showFontsManager', params);


    }

    getFontUrl(family) {
        if (!family) {
            return;
        }
        var filename = "https://fonts.googleapis.com/css?family=" + encodeURIComponent(family);

        return filename;
    }

    loadNewFontTemp(family) {
        if (!family) {
            return;
        }

        var id = 'font-' + family.replace(/[^a-zA-Z0-9]/g, '');

        if (this.fonts.includes(family)) {
            return;
        }

        // Skip if already loaded in the manager (though eachWindow will check its own doc)
        if (this.loadedNewFontsTemp.has(family)) {
            // console.log('Font already in loadedNewFontsTemp:', family);
            // return;
        }

        // var filename = "//fonts.googleapis.com/css?family=" + encodeURIComponent(family) + "&text=" + encodeURIComponent(family);
        //var filename = "https://fonts.googleapis.com/css?family=" + encodeURIComponent(family);
        var filename =this.getFontUrl(family);
        //var filename = "https://fonts.googleapis.com/css?family=" + encodeURIComponent(family) + ":300italic,400italic,600italic,700italic,800italic,400,600,800,700,300&subset=latin,cyrillic-ext,greek-ext,greek,vietnamese,latin-ext,cyrillic"


//also load to each frame window
        mw.tools.eachWindow(function (win) {
            if (win && win.document) {
                if (win.document.getElementById(id)) {
                    // console.log('Font already loaded in window:', family, win.location.href);
                    return; // Skip if already loaded in this specific window
                }

                var fileref = win.document.createElement("link");
                fileref.setAttribute("rel", "stylesheet");
                fileref.setAttribute("type", "text/css");
                fileref.setAttribute("href", filename);
                fileref.setAttribute("referrerpolicy", "no-referrer");
                fileref.setAttribute("crossorigin", "anonymous");
                fileref.setAttribute("data-noprefix", "1");
                fileref.setAttribute("id", id);

                win.document.getElementsByTagName("head")[0].appendChild(fileref);
                // console.log('Font loaded in window:', family, win.location.href);
            }
        });


        this.loadedNewFontsTemp.add(family);

        this._fireFontsManagerChange();

    }


    scanPageForNewUsedFonts() {
        const canvasDocument = mw.top().app.canvas.getDocument();
        if (!canvasDocument) {
            console.warn('Canvas document not available.');
            return [];
        }

        // Get all elements that might have font styles
        const allElements = canvasDocument.querySelectorAll([
            '.element',
            '.edit',
            '.mw-text',
            '.mw-text-editable',
            '.main p',
            '.main h1',
            '.main h2',
            '.main h3',
            '.main h4',
            '.main h5',
            '.main h6',
            '.main span',
            '.main div'
        ]);
        const usedFontFamiliesOnPage = new Set();

        // For debugging
        //console.log('Scanning for fonts in use...');
        // console.log('Temp loaded fonts:', Array.from(this.loadedNewFontsTemp));

        // Collect all font families used on the page
        allElements.forEach(element => {
            const computedStyle = window.getComputedStyle(element);
            const fontFamilyStr = computedStyle.fontFamily;
            if (fontFamilyStr) {
                const parsedFonts = this.parseFontFamilies(fontFamilyStr);
                parsedFonts.forEach(font => {
                    // Normalize font name by trimming and converting to lowercase for comparison
                    const normalizedFont = font.trim();
                    if (normalizedFont) {
                        usedFontFamiliesOnPage.add(normalizedFont);
                    }
                });
            }
        });

        // console.log('All fonts found on page:', Array.from(usedFontFamiliesOnPage));

        // Filter to only include fonts from loadedNewFontsTemp that are actually used
        const usedTempFonts = [];

        // Use a more explicit loop for better debugging and comparison
        this.loadedNewFontsTemp.forEach(tempFont => {
            const normalizedTempFont = tempFont.trim();

            // Check if this temp font is used on the page
            if (Array.from(usedFontFamiliesOnPage).some(pageFont =>
                pageFont === normalizedTempFont ||
                pageFont.toLowerCase() === normalizedTempFont.toLowerCase()
            )) {
                usedTempFonts.push(tempFont);
                console.log(`Font "${tempFont}" is being used on the page`);
            } else {
                console.log(`Font "${tempFont}" is NOT being used on the page`);
            }
        });

        //console.log('Final list of used temp fonts:', usedTempFonts);
        return usedTempFonts;
    }

    saveNewLoadedTempFontsUsedOnPage() {
        if (this.loadedNewFontsTemp === undefined || this.loadedNewFontsTemp.size === 0) {
            return;
        }

        // Use the new scanning function to get only used temp fonts
        const fontsToSave = this.scanPageForNewUsedFonts();

        if (fontsToSave.length > 0) {
            console.log('Fonts to save to server:', fontsToSave);

            // Add to the main fonts list and remove from temp
            fontsToSave.forEach(font => {
                if (!this.fonts.includes(font)) {
                    this.fonts.push(font);
                }
                this.loadedNewFontsTemp.delete(font); // Remove from temp as it's now "saved"
            });

            window.fetch(
                mw.settings.api_url + 'template/save-template-fonts',
                {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify({fonts: fontsToSave})
                }
            )
                .then(response => response.json())
                .then(data => console.log('Fonts saved:', data))
                .then(data => this.reloadLiveEdit())
                .then(data => this._fireFontsManagerChange())
                .catch(error => console.error('Error saving fonts:', error));


        } else {
            console.log('No new temporarily loaded fonts found in use on the page.');
        }
    }


    // Helper function to split and parse font-family string
    _splitFontFamily(str) {
        if (!str) return [];
        let result = [];
        let current = '';
        let inQuotes = false;
        for (let char of str) {
            if (char === '"' && !inQuotes) inQuotes = true;
            else if (char === '"' && inQuotes) inQuotes = false;
            else if (char === ',' && !inQuotes) {
                result.push(current.trim());
                current = '';
            } else {
                current += char;
            }
        }
        if (current) result.push(current.trim());
        return result;
    }

    // Helper function to clean up font names (remove quotes)
    parseFontFamilies(fontFamilyStr) {
        if (!fontFamilyStr) return [];
        const fonts = this._splitFontFamily(fontFamilyStr);
        return fonts.map(font => {
            let cleanedFont = font;
            if (cleanedFont.startsWith('"') && cleanedFont.endsWith('"')) {
                cleanedFont = cleanedFont.slice(1, -1);
            }
            if (cleanedFont.startsWith("'") && cleanedFont.endsWith("'")) {
                cleanedFont = cleanedFont.slice(1, -1);
            }
            return cleanedFont;
        });
    }

}

export default LiveEditFontManager;
