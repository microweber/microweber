<?php

$mod = false;
if (isset($params['for-module'])) {
    $mod = $params['for-module'];
}
if (!$mod) {
    return;
}

$module_info = module_info($mod);
if (!$module_info) {
    return;
}
?>
<div class="d-flex align-items-center">
    <?php if (isset($module_info['icon'])) { ?>
<!--        <span style="width: 30px; height: 30px ; margin-right:10px;">-->
<!--            --><?php //echo module_icon_inline($mod); ?>
<!--        </span>-->
    <?php } ?>
    <?php if (isset($module_info['name'])) { ?>
        <h1 class="main-pages-title"><?php _e($module_info['name']); ?> </h1>
    <?php } ?>
</div>


