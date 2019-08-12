<?php
$template_settings_config = mw()->template->get_config();
$template_settings = false;
if (isset($template_settings_config['template_settings'])) {
    $template_settings = $template_settings_config['template_settings'];
}

if (!$template_settings) {
    return;
}

$option_group = 'mw-template-' . mw()->template->folder_name() . '-settings';
if ($template_settings) {
    foreach ($template_settings as $key => $setting) {
        $$key = get_option($key, $option_group);
//        var_dump($$key);
        if ($$key === false AND $$key !== null) {
            if (isset($setting['default'])) {
                $$key = $setting['default'];
            } else {
                $$key = 'no-default-option';
            }
        } elseif ($$key == null) {
            $$key = (string)'';
        }
/*        var_dump($$key);
        echo PHP_EOL . '<br /><br />';*/
    }
}
