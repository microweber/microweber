import MicroweberBaseClass from "../containers/base-class.js";


export class TemplateSettings extends MicroweberBaseClass {

    constructor() {
        super();
    }

    onRegister() {


    }
    reloadStylesheet() {
        alert('reloadStylesheet todo');
       /* $.get(mw.settings.api_url + "template/delete_compiled_css?path=<?php print $template_settings['stylesheet_compiler']['source_file']; ?>&option_group=<?php print $option_group; ?>", function () {
            mw.top().notification.success("<?php _ejs("Reloading styles"); ?>.",7000);

            mw.parent().$("#theme-style").attr('href', '<?php print mw()->template->get_stylesheet($template_settings['stylesheet_compiler']['source_file'], false, false); ?>&t=' + mw.random());
            mw.tools.refresh(parent.$("#theme-style"));
        });*/

    }



}





