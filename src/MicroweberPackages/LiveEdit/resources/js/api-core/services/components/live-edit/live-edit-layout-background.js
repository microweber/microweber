import BaseComponent from "../../containers/base-class";
import liveEditHelpers from "../../../core/live-edit-helpers.service";

export class LiveEditLayoutBackground extends BaseComponent {
    constructor() {
        super();

    }


    setBackgroundImage(node, url) {
        url = url.toString();
        node.innerHTML = ``;
        node.style.backgroundImage = `url(${url})`;

        node.style.backgroundColor = 'transparent';


        var isInsideBackgroundModule = liveEditHelpers.targetGetFirstModuleOfType(node, 'background');
        if (isInsideBackgroundModule) {
            var options = {
                group: isInsideBackgroundModule.id,
                key: 'image',
                module: 'background',
                value: url
            };
            alert('save option');
            mw.top().options.tempOption(isInsideBackgroundModule,options);

        }

        delete node.dataset.mwvideo;
        mw.top().app.registerChange(node);

    }
    setBackgroundVideo(node, url) {
        node.innerHTML = `<video src="${url}" autoplay muted></video>`;

        node.dataset.mwvideo = url;
        node.style.backgroundImage = `none`;

        node.style.backgroundColor = 'transparent';

        mw.top().app.registerChange(node);

    }
    setBackgroundColor(node,color) {
        node.style.backgroundColor = color;
        node.style.backgroundImage = 'transparent';
        delete node.dataset.mwvideo;
        mw.top().app.registerChange(node);
    }






}

export default LiveEditLayoutBackground;
