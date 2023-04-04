import MicroweberBaseClass from "../containers/base-class.js";


export class TemplateSettings extends MicroweberBaseClass {

    reloadStylesheet(source_file, option_group) {

        $.get(mw.settings.api_url + "template/delete_compiled_css?path="+source_file+"&option_group=" + option_group, function () {
            mw.top().notification.success("Reloading styles", 7000);

            $(document.getElementById('live-editor-frame').contentWindow.document).find("#theme-style").attr('href', source_file + '&t=' + mw.random());
            mw.tools.refresh($(document.getElementById('live-editor-frame').contentWindow.document).find("#theme-style"));

        });
    }

};




