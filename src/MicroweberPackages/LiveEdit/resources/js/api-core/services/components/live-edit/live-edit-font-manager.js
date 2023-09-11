import BaseComponent from "../../containers/base-class";

export class LiveEditFontManager extends BaseComponent {
    constructor() {
        super();


        mw.app.on('onLiveEditReady', event =>{
            this.init();
        });

        mw.app.on('liveEditCanvasLoaded', event =>{
            this.init();
        });


    }

    init(){
mw.log('LiveEditFontManager')
    }
}

export default LiveEditFontManager;
