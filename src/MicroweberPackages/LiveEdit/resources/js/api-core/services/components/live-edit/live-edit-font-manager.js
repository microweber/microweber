import BaseComponent from "../../containers/base-class";

export class LiveEditFontManager extends BaseComponent {

    constructor() {
        super();

        this.fonts = [
            'Arial',
            'Tahoma',
            'Verdana',
            'Times New Roman',
            'Georgia',
        ];

        mw.app.on('onLiveEditReady', event =>{
            this.init();
        });

        mw.app.on('liveEditCanvasLoaded', event =>{
            this.init();
        });
    }

    init() {
        mw.log('LiveEditFontManager');
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

    manageFontsModal() {

    }
}

export default LiveEditFontManager;
