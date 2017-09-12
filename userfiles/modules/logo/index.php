<?php
$logotype = get_option('logotype', $params['id']);
$logoimage = get_option('logoimage', $params['id']);
$text = get_option('text', $params['id']);
$font_family = get_option('font_family', $params['id']);
$font_size = get_option('font_size', $params['id']);



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

if ($font_family == false or $font_family == '') {
    if (isset($params['font_family'])) {
        $font_family = $params['font_family'];
    }
}
if ($font_size == false or $font_size == '') {
    if (isset($params['font_size'])) {
        $font_size = $params['font_size'];
    }
}

if ($font_size == false) {
    $font_size = 30;
}

if ($text == false or $text == '') {
    if (isset($params['text'])) {
        $text = $params['text'];
    }
}


$font_family_safe = str_replace("+", " ", $font_family);
if ($font_family_safe == '') {
    $font_family_safe = 'inherit';
}

    $size = get_option('size', $params['id']);
if ($size == false or $size == '') {
    if(isset($params['size'])){
        $size = $params['size'];
    }
    else{
        $size = 60;
    }

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



$module_template = get_option('data-template', $params['id']);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}
if ($module_template != false) {
    $template_file = module_templates($config['module'], $module_template);
} else {
    $template_file = module_templates($config['module'], 'default');
}

if (is_file($template_file) != false) {
    include($template_file);
} else {
    print lnotif("No template found. Please choose template.");
}





