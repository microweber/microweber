
import {EditorComponent} from './components/editor/editor.js';
import {MWClassContainer} from "./containers/class.container.js";
import {MWServiceProvider} from "./containers/service-provider.js";
import {liveEditComponent} from "./components/live-edit/live-edit.js";
import {eventManager} from "./services/events.service.js";
import {LiveEditCanvasService} from "./services/live-edit-canvas.service.js";

;(() => {

    mw.liveEditApp = new MWClassContainer();
    mw.liveEditApi = new MWServiceProvider();

    mw.liveEditServices = new MWServiceProvider();


    mw.app = {
        container: new MWClassContainer(),
        editor: mw.liveEditApi,
        services: mw.liveEditServices,

    };



    mw.liveEditServices.register('canvas', LiveEditCanvasService);
    mw.liveEditServices.register('event', eventManager);

    mw.liveEditServices.event.on('liveEditCanvasLoaded', () => {

        new EditorComponent();
        liveEditComponent();



    });

})();
