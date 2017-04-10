<?php
$btn_id = 'btn-' . $params['id'];
$style = get_option('button_style', $params['id']);
$size = get_option('button_size', $params['id']);
$action = get_option('button_action', $params['id']);
$action_content = get_option('popupcontent', $params['id']);
$url = get_option('url', $params['id']);
$blank = get_option('url_blank', $params['id']) == 'y';
$text = get_option('text', $params['id']);
if (get_option('icon', $params['id'])) {
    $icon = get_option('icon', $params['id']);
} elseif (isset($params['icon'])) {
    $icon = $params['icon'];
} else {
    $icon = '';
}

$popup_function_id = 'btn_popup' . uniqid();
if ($text == '') {
    $text = 'Button';
}
if ($style == '') {
    $style = 'btn-default';
}

if ($size == false and isset($params['button_size'])) {
    $size = $params['button_size'];

}

if ($text == false and isset($params['text'])) {
    $text = $params['text'];
}

if ($style == false and isset($params['button_style'])) {
    $style = $params['button_style'];
}

if ($action == 'popup') {
    $url = 'javascript:' . $popup_function_id . '()';
}
?>

    <script>
        mw.require('tools.js', true);
        mw.require('ui.css', true);
    </script>


<?php

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

<?php if ($action == 'popup') { ?>


    <script type="text/microweber" id="area<?php print $btn_id; ?>">
    <?php print $action_content; ?>





    </script>
    <script>

        function <?php print $popup_function_id ?>() {
            mw.modal({
                name: 'frame<?php print $btn_id; ?>',
                html: $(mwd.getElementById('area<?php print $btn_id; ?>')).html(),
                template: 'basic',
                title: "<?php print $text; ?>"
            });
        }


    </script>
<?php } ?>