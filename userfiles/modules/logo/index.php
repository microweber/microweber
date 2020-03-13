<?php


$logo_name =  $params['id'];

if(isset($params['logo-name'])){
    $logo_name = $params['logo-name'];
} else if(isset($params['logo_name'])){
    $logo_name = $params['logo_name'];
}

$logotype = get_option('logotype', $logo_name);
$logoimage = get_option('logoimage', $logo_name);
$logoimage_inverse = get_option('logoimage_inverse', $logo_name);
$text = get_option('text', $logo_name);
$font_family = get_option('font_family', $logo_name);
$font_size = get_option('font_size', $logo_name);


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


if ($logoimage_inverse == false or $logoimage_inverse == '') {
    if (isset($params['logoimage_inverse'])) {
        $logoimage_inverse = $params['logoimage_inverse'];
    } else {
        if($logoimage){
            $logoimage_inverse = $logoimage;
        } else {
            $logoimage_inverse = $default;
        }
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

$size = get_option('size', $logo_name);
if ($size == false or $size == '') {
    if (isset($params['size'])) {
        $size = $params['size'];
    } else {
        $size = 60;
    }

}


?>
<?php if ($font_family_safe != 'inherit') { ?>

    <script>
        mw.require("fonts.js");

        $(document).ready(function () {
            mw.logoFont = mw.logoFont || new mw.font();

            mw.logoFont.set({
                family: {
                    "<?php print $font_family; ?>": [400]
                }
            })
        });
    </script>


<?php } ?>

<?php

$module_template = get_option('data-template', $logo_name);
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}


$module_template = get_option('data-template', $logo_name);
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





