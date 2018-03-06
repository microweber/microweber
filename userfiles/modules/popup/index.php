<?php
//setcookie("TestCookie", $value);

$session_get = false;
$modal_id = 'popup-' . $params['id'];
if (isset($_COOKIE['popup-' . $params['id']])) {
    $session_get = $_COOKIE['popup-' . $params['id']];
}

if ($session_get != 'yes') {
    $showPopUp = true;
} else {
    $showPopUp = false;
}
?>


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

<?php print lnotif('Pop-Up Settings'); ?>

<?php if (in_live_edit()): ?>
    <style>
        #popup-<?php print $params['id']; ?> {
            z-index: 900 !important;
            top: 10%;
        }
    </style>
    <a class="btn btn-default pull-right" data-toggle="modal" href="#popup-<?php print $params['id']; ?>"
       data-backdrop="false" style="margin-top: -30px;">Open Pop-Up</a>
<?php endif; ?>