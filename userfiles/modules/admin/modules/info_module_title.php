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
<h5>
    <?php if (isset($module_info['icon'])) { ?>
        <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/>
    <?php } ?>
    <?php if (isset($module_info['name'])) { ?>
        <h1 class="main-pages-title"><?php _e($module_info['name']); ?> </h1>
    <?php } ?>
</h5>


