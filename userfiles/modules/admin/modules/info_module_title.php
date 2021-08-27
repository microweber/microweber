<?php

$mod = false;
if (isset($params['for-module'])) {
    $mod = $params['for-module'];
}


if (!$mod) {
    return;
}


$module_info = module_info($mod);
 if(!$module_info){
    return;
}
?>
<h5>
    <img src="<?php echo $module_info['icon']; ?>" class="module-icon-svg-fill"/>
    <strong><?php _e($module_info['name']); ?></strong>
</h5>


