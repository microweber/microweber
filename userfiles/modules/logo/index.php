<?php
$logotype = get_option('logotype', $params['id']);
$logoimage = get_option('logoimage', $params['id']);
$text = get_option('text', $params['id']);
$font_family = get_option('font_family', $params['id']);
$font_size = get_option('font_size', $params['id']);
if ($font_size == false) {
    $font_size = 30;
}


$default = '';
if (isset($params['data-defaultlogo'])) {
    $default = $params['data-defaultlogo'];
}
if ($logoimage == false or $logoimage == '') {
    if (isset($params['image'])) {
        $logoimage = $params['image'];
    } else {
        $logoimage = $default;
    }


}


$font_family_safe = str_replace("+", " ", $font_family);
if ($font_family_safe == '') {
    $font_family_safe = 'inherit';
}


?>
<?php if ($font_family_safe != 'inherit') { ?>

    <script>mw.require('//fonts.googleapis.com/css?family=<?php print $font_family; ?>&filetype=.css', true);</script>

<?php } ?>

<?php

$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}



if ($module_template != false and trim($module_template) != '' and trim(strtolower($module_template) != 'none')) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if(!$logotype and !$text and !$logoimage){
    print lnotif("Setup logo");
    return;
}

$template_file = module_templates($config['module'], 'default');

if(is_file($template_file) != false){
    include($template_file);
} else {
    print lnotif("No template found. Please choose template.");
}





