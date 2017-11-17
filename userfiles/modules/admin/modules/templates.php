<?php

if (isset($params['for-module'])) {
    $params['parent-module'] = $params['for-module'];
}
if (!isset($params['parent-module'])) {
    error('parent-module is required');

}

if (!isset($params['parent-module-id'])) {
    error('parent-module-id is required');

}


$site_templates = site_templates();

$templates = module_templates($params['parent-module']);

$mod_name = $params['parent-module'];
$mod_name = str_replace('admin', '', $mod_name);
$mod_name = rtrim($mod_name, DS);
$mod_name = rtrim($mod_name, '/');


$cur_template = get_option('data-template', $params['parent-module-id']);
 if ($cur_template == false) {

    if (isset($_REQUEST['template'])) {
        $cur_template = $_REQUEST['template'] . '.php';
    } else if (isset($_GET['data-template'])) {
        $cur_template = $_GET['data-template'] . '.php';
    }
    if ($cur_template != false) {
        $cur_template = str_replace('..', '', $cur_template);
        $cur_template = str_replace('.php.php', '.php', $cur_template);
    }
}



 
?> 
<?php if (is_array($templates)): ?>

<div class="mw-mod-template-settings-holder">
  <?php $default_item_names = array(); ?>
  <label class="mw-ui-label">
    <?php _e("Current Skin / Template"); ?>
  </label>
  <select data-also-reload="#mw-module-skin-settings-module" name="data-template" class="mw-ui-field mw_option_field"
                option_group="<?php print $params['parent-module-id'] ?>"
                data-refresh="<?php print $params['parent-module-id'] ?>">
    <option value="default"   <?php if (('default' == $cur_template)): ?>   selected="selected"  <?php endif; ?>>
    <?php _e("Default"); ?>
    </option>
    <?php  foreach ($templates as $item): ?>
    <?php if ((strtolower($item['name']) != 'default')): ?>
    <?php $default_item_names[] = $item['name']; ?>
    <option value="<?php print $item['layout_file'] ?>"   <?php if (($item['layout_file'] == $cur_template)): ?>   selected="selected"  <?php endif; ?>     > <?php print $item['name'] ?> </option>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php  if (is_array($site_templates)): ?>
    <?php foreach ($site_templates as $site_template): ?>
    <?php if (isset($site_template['dir_name'])): ?>
    <?php
                        $template_dir = templates_path() . $site_template['dir_name'];
                        $possible_dir = $template_dir . DS . 'modules' . DS . $mod_name . DS;
                        $possible_dir = normalize_path($possible_dir, false)
                        ?>
    <?php if (is_dir($possible_dir)): ?>
    <?php

                            $options = array();

                            $options['for_modules'] = 1;
                            $options['path'] = $possible_dir;
                            $templates = mw()->layouts_manager->get_all($options);

                            ?>
    <?php if (is_array($templates)): ?>
    <?php if ($site_template['dir_name'] == template_name()) { ?>
    
    
     <?php
	 
	  $has_items= false;
	 
	   foreach ($templates as $item) { 
		 if (!in_array($item['name'], $default_item_names)){
		 $has_items= true;
		   }
     
	   } 
	   
	   
	  
	   
	   ?>
    <?php if (is_array($has_items)): ?>
    <optgroup label="<?php print $site_template['name']; ?>">
    <?php  foreach ($templates as $item): ?>
    <?php if ((strtolower($item['name']) != 'default')): ?>
    <?php $opt_val = $site_template['dir_name'] . '/' . 'modules/' . $mod_name . $item['layout_file']; ?>
    <?php if (!in_array($item['name'], $default_item_names)): ?>
    <option  value="<?php print $opt_val; ?>"   <?php if (($opt_val == $cur_template)): ?>   selected="selected"  <?php endif; ?>     >
    <?php  print $item['name'] ?>
    </option>
    <?php endif; ?>
    <?php endif; ?>
    <?php endforeach; ?>
    </optgroup>
    <?php endif; ?>
    
    
    
    <?php } ?>
    <?php endif; ?>
    <?php endif; ?>
    <?php endif; ?>
    <?php endforeach; ?>
    <?php endif; ?>
  </select>
  <module type="admin/modules/templates_settings" id="mw-module-skin-settings-module" parent-module-id="<?php print $params['parent-module-id'] ?>" parent-module="<?php print $params['parent-module'] ?>" parent-template="<?php print $cur_template ?>" />
  <?php if (!isset($params['simple'])) { ?>
    <label class="mw-ui-label">
    <hr>
    <small>
    <?php _e("Need more designs"); ?>
    ?<br>
    <?php _e("You can use all templates you like and change the skin"); ?>
    . </small>
    </label>
    <a class="mw-ui-link" target="_blank" href="<?php print mw()->update->marketplace_admin_link($params); ?>">
    <?php _e("Browse Templates"); ?>
    </a>
    <?php } ?>
</div>
<?php endif; ?>
