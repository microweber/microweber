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
        }

        return this.getFonts();
    }

    addFonts(fonts) {
        if (fonts) {
            fonts.forEach(font => {
                this.addFont(font);
            });
        }
        return this.getFonts();
    }

    removeFont(font) {

        this.fonts = this.fonts.filter(item => item !== font);

        return this.getFonts();
    }

    getFonts() {
        return this.fonts;
    }
}

export default LiveEditFontManager;
