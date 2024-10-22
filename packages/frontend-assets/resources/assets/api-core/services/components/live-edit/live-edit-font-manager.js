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

        mw.app.on('onLiveEditReady', event => {
            this.init();
        });

        mw.app.on('liveEditCanvasLoaded', event => {
            this.init();
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
        return this.fonts;
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



        if (params && params.applySelectionToElement) {
            this.applyToSelectedElement = params.applySelectionToElement;
        }

        var attrsForSettings = {};

        attrsForSettings.live_edit = true;
        attrsForSettings.module_settings = true;
        attrsForSettings.id = 'mw_editor_fonts_manager_modal';
        attrsForSettings.type = 'editor/fonts/font-manager-modal';
        attrsForSettings.iframe = true;
        attrsForSettings.from_url = mw.app.canvas.getWindow().location.href;


        var moduleType = 'editor/fonts/font-manager-modal';
        //    var src = route('live_edit.module_settings') + "?" + json2url(attrsForSettings);
        var src = mw.app.adminModules.getModuleSettingsUrl(moduleType, attrsForSettings);



        var dialog = mw.top().dialogIframe({
            url: src,
            autoHeight: true,
            width: 800,
            skin: 'square_clean',
        });
        dialog.dialogHeader.style.display = 'none';
        dialog.iframe.addEventListener('load', () => {
            dialog.iframe.contentWindow.document.getElementById('js-modal-livewire-ui-close').addEventListener('click', () => {
                dialog.remove();
            });
        });
        mw.top().app.on('fontsManagerSelectedFont', (newFont) => {
            dialog.remove();
        });
    }
}

export default LiveEditFontManager;
