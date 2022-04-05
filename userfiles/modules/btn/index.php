<?php

$btn_id = 'btn-' . $params['id'];

$btn_options = [];
$btn_options['button_style'] = '';
$btn_options['button_size'] = '';
$btn_options['button_action'] = '';
$btn_options['popupcontent'] = '';
$btn_options['url'] = '';
$btn_options['url_blank'] = '';
$btn_options['text'] = '';
$btn_options['icon'] = '';
$btn_options['button_id'] = '';

$get_btn_options = get_module_options($params['id']);
if (!empty($get_btn_options)) {
    foreach ($get_btn_options as $get_btn_option) {
        $btn_options[$get_btn_option['option_key']] = $get_btn_option['option_value'];
    }
}

$align = get_module_option('align', $params['id']);

$backgroundColor = get_module_option('backgroundColor', $params['id']);
$color = get_module_option('color', $params['id']);
$borderColor = get_module_option('borderColor', $params['id']);
$borderWidth = get_module_option('borderWidth', $params['id']);
$borderRadius = get_module_option('borderRadius', $params['id']);
$customSize = get_module_option('customSize', $params['id']);
$shadow = get_module_option('shadow', $params['id']);


$hoverbackgroundColor = get_module_option('hoverbackgroundColor', $params['id']);
$hovercolor = get_module_option('hovercolor', $params['id']);
$hoverborderColor = get_module_option('hoverborderColor', $params['id']);



$style = $btn_options['button_style'];
$size = $btn_options['button_size'];
$action = $btn_options['button_action'];
$action_content = $btn_options['popupcontent'];
$url = $btn_options['url'];
$blank = $btn_options['url_blank'] == 'y';
$text = $btn_options['text'];
if ($btn_options['icon']) {
    $icon = $btn_options['icon'];
} elseif (isset($params['icon'])) {
    $icon = $params['icon'];
} else {
    $icon = '';
}

$icon = html_entity_decode($icon);

if (isset($params['button_id'])) {
    $btn_id = $params['button_id'];
}

$attributes = '';
if (isset($params['button_onclick'])) {
    $attributes .= 'onclick="'.$params['button_onclick'].'"';
}

if (isset($params['button_text']) && !empty($params['button_text']) && empty($text)) {
	$text = $params['button_text'];
}

$popup_function_id = 'btn_popup' . uniqid();
if ($text == false and isset($params['text'])) {
    $text = $params['text'];
} elseif ($text == '') {
    $text = lang('Button', 'templates/dream/modules/btn');
}
if ($text === '$notext') {
    $text = '';
}
if($icon){
    $text = $icon . ($text !== '' ? '&nbsp;' : '') . $text;
}

if ($url == false and isset($params['url'])) {
    $url = $params['url'];
} elseif ($url == '') {
    $url = '#';
}

$url_display = false;


$link_to_content_by_id = 'content:';
$link_to_category_by_id = 'category:';

$url_to_content_id = get_module_option('url_to_content_id', $params['id']);
$url_to_category_id = get_module_option('url_to_category_id', $params['id']);

if ($url_to_content_id) {
    $url_display = content_link($url_to_content_id);
} else if ($url_to_category_id) {
    $url_display = category_link($url_to_category_id);

}


if($url_display){
    $url = $url_display;
}




if ($style == false and isset($params['button_style'])) {
    $style = $params['button_style'];
}
if ($style == '') {
    $style = 'btn-default';
}

if ($action == false and isset($params['button_action'])) {
    $action = $params['button_action'];
}

if ($size == false and isset($params['button_size'])) {
    $size = $params['button_size'];
}


if ($action == 'popup') {
    $url = 'javascript:' . $popup_function_id . '()';
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

?>


<?php

$cssWrapper = '';
$cssButton = '';
$cssHoverButton = '';


if($backgroundColor){
    $cssButton .= 'background-color:' . $backgroundColor . '!important;';
}
if($color){
    $cssButton .= 'color:' . $color . '!important;';
}

if($borderColor){
    $cssButton .= 'border-color:' . $borderColor . '!important;';
}

if($borderWidth){
    $cssButton .= 'border-width:' . $borderWidth . 'px!important;';
}

if($borderRadius){
    $cssButton .= 'border-radius:' . $borderRadius . 'px!important;';
}

if($customSize){
    $cssButton .= 'font-size: '.(intval($customSize)).'px!important;padding: .9em 2em!important;';
}

if($shadow){
    $cssButton .= 'box-shadow:' . $shadow . '!important;';
}

if($align){
    if(_lang_is_rtl()) {
        if($align == 'left') {
            $align = 'right';
        } elseif ($align == 'right') {
            $align = 'left';
        }
    }
    $cssWrapper .= 'text-align:' . $align . ' !important;';
}

if($hoverbackgroundColor){
    $cssHoverButton .= 'background-color:' . $hoverbackgroundColor . ' !important;';
}

if($hovercolor){
    $cssHoverButton .= 'color:' . $hovercolor . ' !important;';
}

if($hoverborderColor){
    $cssHoverButton .= 'border-color:' . $hoverborderColor . ' !important;';
}






?>
    <style >
        #<?php print $params['id']; ?> { <?php print $cssWrapper; ?> }
        #<?php print $params['id']; ?> > #<?php print $btn_id; ?>,#<?php print $params['id']; ?> > a, #<?php print $params['id']; ?> > button { <?php print $cssButton; ?> }
        #<?php print $params['id']; ?> > #<?php print $btn_id; ?>:hover,#<?php print $params['id']; ?> > a:hover, #<?php print $params['id']; ?> > button:hover { <?php print $cssHoverButton; ?> }

    </style>
<?php

if ($action == 'popup') { ?>

    <script type="text/microweber" id="area<?php print $btn_id; ?>">
        <?php print $action_content; ?>
    </script>

    <script>
        function <?php print $popup_function_id ?>() {
            mw.dialog({
                name: 'frame<?php print $btn_id; ?>',
                content: $(document.getElementById('area<?php print $btn_id; ?>')).html(),
                template: 'basic',
                title: "<?php print addslashes ($text); ?>"
            });
        }
    </script>
<?php }
