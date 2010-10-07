<?php $plugins = $this->core_model->plugins_getLoadedPlugins();
//var_dump($plugins);
$admin_loaded_plugins = array();
if(!empty($plugins)){
foreach($plugins as $k => $item){
$tempdata = $this->core_model->plugins_getPluginConfig($k); 
if($tempdata['plugin_admin_enabled'] == true){
$tempdata['plugin'] = $k;
$admin_loaded_plugins[] = $tempdata;
}

//var_dump($tempdata);

}


}
 ?>

<ul>
  <li  <?php if( $className == 'index' and $functionName == 'index')  : ?> class="active" <?php endif; ?>> <a href="<?php print site_url('admin/index') ?>">Microweber help</a> </li>
  <li  <?php if(( $className == 'content' and $functionName == 'posts_manage') or ( $className == 'content' and $functionName == 'posts_edit') ) : ?>    class="active" <?php endif; ?> > <a href="<?php print site_url('admin/content/posts_manage')  ?>"><img src="<?php print_the_static_files_url() ; ?>icons/page.png"  border="0" alt=" " />Content manager</a></li>
  <li  <?php if(( $className == 'content' and $functionName == 'pages_index') or ( $className == 'content' and $functionName == 'pages_edit') ) : ?>    class="active" <?php endif; ?> > <a href="<?php print site_url('admin/content/pages_index')  ?>"><img src="<?php print_the_static_files_url() ; ?>icons/layout_header.png"  border="0" alt=" " />Pages manager</a></li>
  <li <?php if( $className == 'comments')  : ?> class="active" <?php endif; ?> ><a href="<?php print site_url('admin/comments/index')  ?>"><img src="<?php print_the_static_files_url() ; ?>icons/comments.png"  border="0" alt=" " />Comments
    <?php $new_comments_counts = $this->content_model->commentsGetNewCommentsCount ();
	if(intval($new_comments_counts) > 0) : ?>
    <sup style="font-size:7px"><?php print $new_comments_counts ?> new</sup>
    <?php endif;  ?>
    </a></li>
    
    
      <li <?php if( $className == 'reports')  : ?> class="active" <?php endif; ?> ><a href="<?php print site_url('admin/reports')  ?>">Reports</a></li>
    
    
    
  <li <?php if( $className == 'menus' )  : ?> class="active" <?php endif; ?> ><a href="<?php print site_url('admin/menus')  ?>">Menus</a></li>
<!--  <li <?php if( $className == 'content' and $functionName == 'taxonomy_categories')  : ?> class="active" <?php endif; ?> ><a  
      href="<?php print site_url('admin/content/taxonomy_categories')  ?>"> Categories </a></li>-->
  <?php foreach($admin_loaded_plugins as $plugin) : ?>
  <li  <?php if( $pluginName == $plugin['plugin'])  : ?> class="active" <?php endif; ?>><a href="<?php print site_url('admin/plugins/load') ?>/<?php print $plugin['plugin'] ?>"><?php print $plugin['plugin_name'] ?></a></li>
  <?php endforeach;  ?>
  <li  <?php if( $className == 'options')  : ?> class="active" <?php endif; ?>><a href="<?php print site_url('admin/options') ?>"><img src="<?php print_the_static_files_url() ; ?>icons/wrench_screwdriver.png"  border="0" alt=" " />Options</a></li>
  <li  <?php if(( $className == 'users' and $functionName == 'index') or ( $className == 'users' and $functionName == 'index') ) : ?>    class="active" <?php endif; ?> > <a href="<?php print site_url('admin/users/index')  ?>"><img src="<?php print_the_static_files_url() ; ?>icons/user.png"  border="0" alt=" " />Users</a> </li>
</ul>
