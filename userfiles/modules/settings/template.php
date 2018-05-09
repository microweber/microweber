<?php
if (!is_admin()) {
    return;
}
$save_url = site_url('module?type=settings___template');
$save_url = api_url('current_template_save_custom_css');

$tpl_settings_for_theme = THIS_TEMPLATE_DIR . 'template_settings.php';


if (!is_file($tpl_settings_for_theme)) {
    return;
}
$active_template_dir = THIS_TEMPLATE_FOLDER_NAME;


if (isset($_POST['save_template_settings'])) {
    $css = "";

    if (isset($_POST['save_template_settings'])) {
        unset($_POST['save_template_settings']);
    }
    if (isset($_POST['module'])) {
        unset($_POST['module']);
    }
    if (isset($_POST['type'])) {
        unset($_POST['type']);
    }


    $live_edit_css_save_all = array();
    foreach ($_POST as $a => $b) {


        if (isset($b['selector'])) {

            $props = explode(',', $b['property']);
            $curr = "";
            foreach ($props as $prop) {
                $curr .= $prop . ":" . $b['value'] . ";";
            }


            $live_edit_css_save = array();
            $live_edit_css_save['active_site_template'] = THIS_TEMPLATE_FOLDER_NAME;
            $live_edit_css_save['selector'] = $b['selector'];
            $live_edit_css_save['css'] = $curr;

            $live_edit_css_save_all [] = $live_edit_css_save;
            $live_edit_css_save_all['active_site_template'] = THIS_TEMPLATE_FOLDER_NAME;

            // $css .= $b['selector']."{".$curr."}". "\n\r\n";

        }

    }

    if (!empty($live_edit_css_save_all)) {
        //  current_template_save_custom_css($live_edit_css_save_all);
    }

//$tpl_settings = TEMPLATE_DIR.'template_settings.css';


    //$chmod = chmod("/userfiles/templates/default/settings/", 0755);
    //$fp = fopen($tpl_settings, "w+");


//fwrite($fp,$css);
//fclose($fp);


    /*$option = array();
    $option['option_value'] = $json;
    $option['option_key'] = 'template_settings';
    $option['option_group'] = 'template_'.THIS_TEMPLATE_FOLDER_NAME;
    save_option($option);*/
    //file_put_contents($tpl_settings,$css);

    return;
}
$data = $arr = array();
$json = get_option('template_settings', 'template_' . THIS_TEMPLATE_FOLDER_NAME);
if ($json != false) {
    $data = $arr = json_decode($json, true);
}


?>


<div class="mw-template-settings" id="mw-template-settings-holder">
    <div id="mw-template-settings">
        <?php include($tpl_settings_for_theme); ?>


        <?php /*<button onclick="mw.tpl.save();" class="mw-ui-btn right">Save</button>*/ ?>

    </div>
</div>


<script>

    mw.tpl = {
        save: function () {
            var u = "<?php print $save_url; ?>", obj = {}, m = mwd.getElementById('mw-template-settings');
            mw.$(".tpl-field", m).each(function () {
                var name = this.name;
                obj.module = "settings/template";
                obj.save_template_settings = true;
                obj.active_site_template = "<?php print $active_template_dir; ?>";
                obj[name] = {
                    selector: $(this).dataset("selector"),
                    value: this.value,
                    property: $(this).dataset("property")
                }
            });
            $.post(u, obj, function (msg) {
                if (self !== parent) {
                    var css = parent.mw.$("#mw-template-settings")[0];

                    mw.reload_module('content/views/layout_selector_custom_css');

                    if ((css === undefined || css === null) && (msg.url !== undefined)) {
                        var l = parent.mwd.createElement('link');
                        l.href = mw.settings.template_url + "live_edit.css";
                        l.href = msg.url;

                        l.id = "mw-template-settings";
                        l.type = "text/css";
                        l.rel = "stylesheet";


                        parent.mwd.getElementsByTagName('head')[0].appendChild(l);

                    } else {
                        mw.tools.refresh(css);
                    }

                }
            })
        },
        reset: function () {

        }
    }


</script>

