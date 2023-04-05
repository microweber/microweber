import MicroweberBaseClass from "../containers/base-class.js";

import axios from 'axios';

export class TemplateSettings extends MicroweberBaseClass {

    reloadStylesheet(source_file, option_group) {

        axios.get(mw.settings.api_url + "template/delete_compiled_css?path="+source_file+"&option_group=" + option_group)
            .then(function (response){

            mw.top().notification.success("Reloading styles", 7000);

            var canvasWindow = mw.app.canvas.getWindow();
            var stylesheet = canvasWindow.document.getElementById('theme-style');

            if (stylesheet) {
                stylesheet.setAttribute('href', response.data.new_file + '&t=' + mw.random());
            }

        });
    }

};




