<?php

if (!isset($params['parent-module']) and isset($params['root-module'])) {
    $params['parent-module'] = $params['root-module'];
}
if (!isset($params['parent-module-id']) and isset($params['root-module-id'])) {
    $params['parent-module-id'] = $params['root-module-id'];
}


if (!isset($params['parent-module']) and isset($params['data-prev-module'])) {
    $params['parent-module'] = $params['data-prev-module'];
}
if (!isset($params['parent-module-id']) and isset($params['data-prev-module-id'])) {
    $params['parent-module-id'] = $params['data-prev-module-id'];
}

if (!isset($params['parent-module-id'])) {
    return;

}


//d($params['parent-module']);

$params['id'] = $params['data-id'] = $params['parent-module-id'];


$module_template = get_option('data-template', $params['parent-module-id']);

if (!$module_template) {

    if (isset($params['parent-template'])) {
        $module_template = $params['parent-template'];
    }
}

if ($module_template == false) {
    $module_template = 'default';
}


if ($module_template != false) {
    $template_file = module_templates($params['parent-module'], $module_template, true);
} else {
    $template_file = module_templates($params['parent-module'], 'default_settings', true);
}

?>

<div for-module-id="<?php print $params['id'] ?>" class="module">
    <?php if (isset($template_file) and $template_file != false and is_file($template_file)): ?>
        <div class="mw-ui-box m-t-20">
            <div class="mw-ui-box-header">
<!--                <i class="mw-icon-gear mr-1"></i>-->
                <label claas="control-label font-weight-bold" style="font-weight: bold;"><?php _e("Skin settings"); ?></label>
                <small class="text-muted d-block mb-3"><?php _e('Edit your design from here.');?></small>
            </div>
            <div class="mw-ui-box-content">
                <style>
                    #settings-holder h5 {
                        display: none !important;
                    }
                </style>
                <?php include($template_file); ?>

            </div>
        </div>
    <?php endif; ?>
</div>

