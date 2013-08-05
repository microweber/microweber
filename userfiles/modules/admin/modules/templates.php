<?php
 
if(isset($params['for-module'])){
	$params['parent-module'] = $params['for-module'];
}
if(!isset($params['parent-module'])){
error('parent-module is required');	
	
}

if(!isset($params['parent-module-id'])){
error('parent-module-id is required');	
	
}



 
$site_templates =  templates_list();
 
 $templates = module_templates($params['parent-module']);
 
$mod_name = $params['parent-module'];
 $mod_name = str_replace('admin', '', $mod_name);
 $mod_name = rtrim($mod_name, DS);
  $mod_name = rtrim($mod_name, '/');

  //$_dirs = glob(MW_TEMPLATES_DIR . '*', GLOB_ONLYDIR);
 
 
$cur_template = mw('option')->get('data-template', $params['parent-module-id']);
 ?><?php  if(is_array( $templates)): ?>
<label class="mw-ui-label"><?php _e("Current Skin / Template"); ?></label>
<div class="mw-ui-select" style="width: 100%"><select name="data-template"     class="mw_option_field" option_group="<?php print $params['parent-module-id'] ?>"  data-refresh="<?php print $params['parent-module-id'] ?>"  >

<option  value="default"   <?php if(('default' == $cur_template)): ?>   selected="selected"  <?php endif; ?>><?php _e("Default"); ?></option>

  <?php  foreach($templates as $item):	 ?>
  <?php if((strtolower($item['name']) != 'default')): ?>
  <option value="<?php print $item['layout_file'] ?>"   <?php if(($item['layout_file'] == $cur_template)): ?>   selected="selected"  <?php endif; ?>     > <?php print $item['name'] ?> </option>
  <?php endif; ?>
  <?php endforeach; ?>

<?php  if(is_array( $site_templates)): ?>
<?php  foreach($site_templates as $site_template):   ?>
<?php  if(isset( $site_template['dir_name'])): ?>
  <?php 
   $template_dir = MW_TEMPLATES_DIR.$site_template['dir_name'];
$possible_dir = $template_dir . DS . 'modules' . DS .$mod_name .DS;
 $possible_dir = normalize_path($possible_dir,false)
  ?>
  <?php  if(is_dir( $possible_dir)): ?>
    
<?php  


//$templates = module_templates($params['parent-module'], $item);
  $options = array();
            
            $options['for_modules'] = 1;
            $options['path'] = $possible_dir;
            $templates = layouts_list($options);
 
 ?>
 <?php  if(is_array( $templates)): ?>
 <optgroup label="<?php print $site_template['name']; ?>">
     <?php  foreach($templates as $item):  ?>
  <?php if((strtolower($item['name']) != 'default')): ?>
    <?php $opt_val = $site_template['dir_name'].'/'.'modules/'.$mod_name. $item['layout_file'] ;
        //$opt_val = normalize_path($opt_val, false);
    ?>
  <option value="<?php print $opt_val; ?>"   <?php if(($opt_val == $cur_template)): ?>   selected="selected"  <?php endif; ?>     ><?php  print $item['name'] ?> </option>
  <?php endif; ?>
  <?php endforeach; ?>
  </optgroup> 


<?php endif; ?>
    <?php endif; ?>

    <?php endif; ?>
 <?php endforeach; ?>
<?php endif; ?>

</select></div>


<label class="mw-ui-label">
    <hr>
    <small>
        <?php _e("Need more designs"); ?>?<br>
        <?php _e("You can use all templates you like and change the skin"); ?>.
    </small>
</label>

<a class="mw-ui-link" href="javascript:;"><?php _e("Browse Templates"); ?></a>

<?php //d($templates); ?>
<?php endif; ?>