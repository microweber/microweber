import BaseComponent from "../../containers/base-class.js";
import liveEditHelpers from "../../../core/live-edit-helpers.service.js";

export class LiveEditLayoutBackground extends BaseComponent {
    constructor() {
        super();

    }

    getBackgroundCursor(node) {
        if(node && node.style){
            var bg = node.style.cursor;
            if (bg) {
                return bg;
            }
        }
    }
    setBackgroundCursor(node, url) {
        mw.app.registerUndoState(node);
        mw.app.registerAskUserToStay(true);


        mw.top().app.cssEditor.temp(node, 'cursor',  `url("${url}") 0 0, auto`);

        mw.top().app.registerChangedState(node);
    }
    getBackgroundImage(node) {

        if(node && node.style){
            var bg = node.style.backgroundImage;
            if (bg) {
                bg = bg.replace('url(', '');
                bg = bg.replace(')', '');
                bg = bg.replace(/\"/gi, "");
            }
            return bg;
        }

    }
    setBackgroundImage(node, url) {
        mw.app.registerUndoState(node);
        mw.app.registerAskUserToStay(true);
        let bg



        if (!url) {
            bg = 'none'
        } else {
            url = url.toString();
            bg = `url(${url})`
        }


        node.innerHTML = ``;
        node.style.backgroundImage = bg;

        node.style.backgroundColor = 'transparent';


        var isInsideBackgroundModule = liveEditHelpers.targetGetFirstModuleOfType(node, 'background');
        if (isInsideBackgroundModule) {

            //set bg image option
            var optionsBg = {
                group: isInsideBackgroundModule.id,
                key: 'data-background-image',
                module: 'background',
                value: url
            };
            mw.top().options.tempOption(isInsideBackgroundModule, optionsBg);

            if(url != '') {

                //remove bg video option
                var optionsVideo = {
                    group: isInsideBackgroundModule.id,
                    key: 'data-background-video',
                    module: 'background',
                    value: 'none'
                };
                mw.top().options.tempOption(isInsideBackgroundModule,optionsVideo);

            }


        }

        delete node.dataset.mwvideo;
        mw.top().app.registerChangedState(node);

    }

    getBackgroundImageSize(node) {
        if(node && node.style){
            var bg = node.style.backgroundSize;
            return (bg || '').trim() || 'auto';
        }
    }

    setBackgroundImageSize(node, url) {
        mw.app.registerUndoState(node);
        mw.app.registerAskUserToStay(true);
        let bg



        if (!url) {
            bg = 'auto'
        } else {
            url = url.toString();
            bg = url
        }


        node.innerHTML = ``;
        node.style.backgroundSize = bg;




        var isInsideBackgroundModule = liveEditHelpers.targetGetFirstModuleOfType(node, 'background');

        console.log(isInsideBackgroundModule, node);
        if (isInsideBackgroundModule) {

            //set bg image option
            var optionsBg = {
                group: isInsideBackgroundModule.id,
                key: 'data-background-size',
                module: 'background',
                value: url
            };
            mw.top().options.tempOption(isInsideBackgroundModule, optionsBg);




        }


        mw.top().app.registerChangedState(node);

    }

    getBackgroundVideo(node){
        if(node && node.dataset && node.dataset.mwvideo){
            return node.dataset.mwvideo;
        }
    }

    setBackgroundVideo(node, url) {
        mw.app.registerAskUserToStay(true);
        mw.app.registerUndoState(node);
        if(!url) {
            url = ''
        }
        url = url.toString();
        if (url == '') {
            node.innerHTML = "";
            delete node.dataset.mwvideo;
        } else {
            node.innerHTML = `<video src="${url}" autoplay muted loop playsinline></video>`;
            node.dataset.mwvideo = url;
        }


        node.style.backgroundImage = null;

        node.style.backgroundColor = null;


        var isInsideBackgroundModule = liveEditHelpers.targetGetFirstModuleOfType(node, 'background');
        if (isInsideBackgroundModule) {
            if(url != '' && url != 'none') {
                //remove bg image option
                var optionsBg = {
                    group: isInsideBackgroundModule.id,
                    key: 'data-background-image',
                    module: 'background',
                    value: 'none'
                };

                mw.top().options.tempOption(isInsideBackgroundModule, optionsBg);
            }
           //set bg video option
            var optionsVideo = {
                group: isInsideBackgroundModule.id,
                key: 'data-background-video',
                module: 'background',
                value: url
            };

            mw.top().options.tempOption(isInsideBackgroundModule,optionsVideo);

        }



        mw.top().app.registerChangedState(node);

    }
    getBackgroundPosition(node){
        if(node && node.style){
            var bg = node.style.backgroundPosition;
            if (bg) {
                return bg;
            }
        }
    }
    setBackgroundPosition(node,position) {
        mw.app.registerUndoState(node);
        mw.app.registerAskUserToStay(true);
        node.style.backgroundPosition = position;
        mw.top().app.registerChangedState(node);

    }
    getBackgroundColor(node){
        if(node && node.style){
            var bg = node.style.backgroundColor;

            if (bg) {
                return bg;
            }
        }
    }
    setBackgroundColor(node,color) {
        mw.app.registerUndoState(node);

        if(color==''){
            node.style.backgroundColor = null;
        } else {
            node.style.backgroundColor = color;
        }

        node.style.backgroundImage = 'transparent';
        delete node.dataset.mwvideo;


        mw.app.registerAskUserToStay(true);
        var isInsideBackgroundModule = liveEditHelpers.targetGetFirstModuleOfType(node, 'background');
        if (isInsideBackgroundModule) {

            //set bg image option
            var optionsBg = {
                group: isInsideBackgroundModule.id,
                key: 'data-background-color',
                module: 'background',
                value: color
            };

            mw.top().options.tempOption(isInsideBackgroundModule,optionsBg);


        }





        mw.top().app.registerChangedState(node);
    }






}

export default LiveEditLayoutBackground;
