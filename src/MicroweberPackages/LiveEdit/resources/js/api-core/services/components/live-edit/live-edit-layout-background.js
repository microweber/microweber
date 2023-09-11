import BaseComponent from "../../containers/base-class";
import liveEditHelpers from "../../../core/live-edit-helpers.service";

export class LiveEditLayoutBackground extends BaseComponent {
    constructor() {
        super();

    }


    setBackgroundImage(node, url) {

        mw.app.registerAskUserToStay(true);

        url = url.toString();
        node.innerHTML = ``;
        node.style.backgroundImage = `url(${url})`;

        node.style.backgroundColor = 'transparent';


        var isInsideBackgroundModule = liveEditHelpers.targetGetFirstModuleOfType(node, 'background');
        if (isInsideBackgroundModule) {

            //set bg image option
            var optionsBg = {
                group: isInsideBackgroundModule.id,
                key: 'background_image',
                module: 'background',
                value: url
            };

            mw.top().options.tempOption(isInsideBackgroundModule,optionsBg);
            //remove bg video option
            var optionsVideo = {
                group: isInsideBackgroundModule.id,
                key: 'background_video',
                module: 'background',
                value: ''
            };

            mw.top().options.tempOption(isInsideBackgroundModule,optionsVideo);


        }

        delete node.dataset.mwvideo;
        mw.top().app.registerChange(node);

    }
    setBackgroundVideo(node, url) {
        mw.app.registerAskUserToStay(true);

       node.innerHTML = `<video src="${url}" autoplay muted></video>`;

        node.dataset.mwvideo = url;
        node.style.backgroundImage = `none`;

        node.style.backgroundColor = 'transparent';


        var isInsideBackgroundModule = liveEditHelpers.targetGetFirstModuleOfType(node, 'background');
        if (isInsideBackgroundModule) {

            //set bg image option
            var optionsBg = {
                group: isInsideBackgroundModule.id,
                key: 'background_image',
                module: 'background',
                value: ''
            };

            mw.top().options.tempOption(isInsideBackgroundModule,optionsBg);

            //remove bg video option
            var optionsVideo = {
                group: isInsideBackgroundModule.id,
                key: 'background_video',
                module: 'background',
                value: url
            };

            mw.top().options.tempOption(isInsideBackgroundModule,optionsVideo);


        }




        mw.top().app.registerChange(node);

    }
    setBackgroundColor(node,color) {
        node.style.backgroundColor = color;
        node.style.backgroundImage = 'transparent';
        delete node.dataset.mwvideo;


        mw.app.registerAskUserToStay(true);
        var isInsideBackgroundModule = liveEditHelpers.targetGetFirstModuleOfType(node, 'background');
        if (isInsideBackgroundModule) {

            //set bg image option
            var optionsBg = {
                group: isInsideBackgroundModule.id,
                key: 'background_color',
                module: 'background',
                value: color
            };

            mw.top().options.tempOption(isInsideBackgroundModule,optionsBg);


        }





        mw.top().app.registerChange(node);
    }






}

export default LiveEditLayoutBackground;
