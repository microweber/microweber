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

    getFonts() {
        return this.fonts;
    }
}

export default LiveEditFontManager;
