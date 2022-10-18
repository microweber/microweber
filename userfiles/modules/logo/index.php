<?php

$logo_name = $params['id'];

if (isset($params['logo-name'])) {
    $logo_name = $params['logo-name'];
} else if (isset($params['logo_name'])) {
    $logo_name = $params['logo_name'];
}

// groups is logo_name

// This will increase the speed of loading
/*
 * Example with get_option funciton:
 * Rendering logo (49.19ms)
 * Example with one get with model by group:
 * Rendering logo (12.4ms)
 */

$logo_title = app()->option_manager->get('website_title', 'website');


$logo_options = [];
$logo_options['logotype'] = '';
$logo_options['logoimage'] = '';
$logo_options['logoimage_inverse'] = '';
$logo_options['text'] = '';
$logo_options['font_family'] = '';
$logo_options['font_size'] = '';
$logo_options['size'] = '';
$logo_options['data-template'] = '';

$get_logo_options = \MicroweberPackages\Option\Models\ModuleOption::where('option_group', $logo_name)->get();
if (!empty($get_logo_options)) {
    foreach ($get_logo_options as $logo_option) {
        $logo_options[$logo_option['option_key']] = $logo_option['option_value'];
    }
}

$logotype = $logo_options['logotype'];
$logoimage = $logo_options['logoimage'];
$logoimage_inverse = $logo_options['logoimage_inverse'];
$text = $logo_options['text'];
$font_family = $logo_options['font_family'];
$font_size = $logo_options['font_size'];

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
        if ($logoimage) {
            $logoimage_inverse = false;
        } else {
            $logoimage_inverse = false;
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
$font_family_safe = '';
if($font_family){
$font_family_safe = str_replace("+", " ", $font_family);
}
if ($font_family_safe == '') {
    $font_family_safe = 'inherit';
}
$size = $logo_options['size'];
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

$module_template = $logo_options['data-template'];
if ($module_template == false and isset($params['template'])) {
    $module_template = $params['template'];
}


$module_template = $logo_options['data-template'];
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
    print lnotif(_e("No template found. Please choose template."));
}


$textCheck = strip_tags(html_entity_decode($text));
$textCheck = mb_trim($textCheck);
$textCheck = str_replace(' ', false, $textCheck);

$hideUploadLogoText = false;

if (!empty($logoimage)) {
    $hideUploadLogoText = true;
}
if (!empty($textCheck)) {
    $hideUploadLogoText = true;
}
if (!empty($logoimage_inverse)) {
    $hideUploadLogoText = true;
}


if ($hideUploadLogoText == false) {
    print lnotif("Upload your logo");
}



