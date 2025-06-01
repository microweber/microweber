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
        var filename = "https://fonts.googleapis.com/css?family=" + encodeURIComponent(family);
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
                // fileref.setAttribute("referrerpolicy", "no-referrer");
                //  fileref.setAttribute("crossorigin", "anonymous");
                //fileref.setAttribute("data-noprefix", "1");
                fileref.setAttribute("id", id);

                win.document.getElementsByTagName("head")[0].appendChild(fileref);
                // console.log('Font loaded in window:', family, win.location.href);
            }
        });


        this.loadedNewFontsTemp.add(family);

        this._fireFontsManagerChange();

    }

    unLoadNewFontTemp(family) {
        if (!family) {
            return;
        }

        var id = 'font-' + family.replace(/[^a-zA-Z0-9]/g, '');

        if (!this.loadedNewFontsTemp.has(family)) {
            // console.log('Font not in loadedNewFontsTemp to unload:', family);
            return;
        }

        mw.tools.eachWindow(function (win) {
            if (win && win.document) {
                var fontLink = win.document.getElementById(id);
                if (fontLink) {
                    fontLink.parentNode.removeChild(fontLink);
                    // console.log('Font unloaded from window:', family, win.location.href);
                }
            }
        });

        if (window.document && window.document.getElementById(id)) {
            var fontLink = window.document.getElementById(id);
            if (fontLink) {
                fontLink.parentNode.removeChild(fontLink);
                // console.log('Font unloaded from self window:', family, window.location.href);
            }
        }

        this.loadedNewFontsTemp.delete(family);
    }

    saveNewLoadedTempFontsUsedOnPage() {


        if (this.loadedNewFontsTemp === undefined || this.loadedNewFontsTemp.size === 0) {

            return;
        }


        const canvasDocument = mw.top().app.canvas.getDocument();
        if (!canvasDocument) {
            console.warn('Canvas document not available.');
            return;
        }

        const allElements = canvasDocument.querySelectorAll(['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'span', 'div', 'a', 'li', 'td', 'th']);
        const usedFontFamiliesOnPage = new Set();

        allElements.forEach(element => {
            const computedStyle = window.getComputedStyle(element);
            const fontFamilyStr = computedStyle.fontFamily;
            const parsedFonts = this._parseFontFamilies(fontFamilyStr);
            parsedFonts.forEach(font => usedFontFamiliesOnPage.add(font));
        });

        const fontsToSave = [];
        this.loadedNewFontsTemp.forEach(tempFont => {
            if (usedFontFamiliesOnPage.has(tempFont)) {
                fontsToSave.push(tempFont);
            }
        });

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
    _parseFontFamilies(fontFamilyStr) {
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
