<?php
$config = false;
if($params['module_info']){
    $params['module_info']= sanitize_path($params['module_info']);
    $try_config_file = modules_path() . '' . $params['module_info'] . '_config.php';
    if (is_file($try_config_file)) {
        include($try_config_file);
        if($config['icon'] == false){
            $config['icon'] = modules_path() . '' . $params['module_info'] .'.png';
            $config['icon'] = mw()->url_manager->link_to_file($config['icon']);
        }

    }
}

?>
<?php if(!empty($config)): ?>

<div class="mw_iframe_header">
  <div class="mw_iframe_header_title"> <img src="<?php print($config['icon']); ?>" align="left" height="25"  class="mw_iframe_header_icon" /> <?php print($config['name']); ?>


  </div>

 <a href="javascript:mw_delete_module_by_id('<?php print $params['module_id'] ?>','1')" class="mw_nav_button_delete">&nbsp</a>
  <a href="javascript:mw_sidebar_nav('#mw_sidebar_modules_holder')" class="mw_nav_button_blue_small"> <span> <?php _e('Back'); ?> </span> </a> </div>
<?php endif; ?>
