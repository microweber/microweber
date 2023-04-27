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
        <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill me-3" style="width: 24px; height: 24px;"/>
    <?php } ?>
    <?php if (isset($module_info['name'])) { ?>
        <h1 class="main-pages-title"><?php _e($module_info['name']); ?> </h1>
    <?php } ?>
</div>


