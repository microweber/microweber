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
<!-- Start Nav 
    <UL id="nav" class="fr ">
       Nav - Start Help 
      <LI class="help"><A class="help" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Help</A>
        <UL>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Documentation</A></LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Forums</A></LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Contact</A></LI>
        </UL>
      </LI>
       Nav - End Help  
       Nav - Start Settings 
      <LI class="settings"><A class="settings" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Settings</A>
        <UL>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">System Settings</A></LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Server Setup</A></LI>
        </UL>
      </LI>
       Nav - End Settings  
       Nav - Start Users 
      <LI class="users"><A class="users" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Users</A>
        <UL>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">User Manager</A></LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Manage Users  »</A>
            <UL>
              <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Add User</A></LI>
              <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Edit User</A></LI>
              <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Delete User</A></LI>
            </UL>
          </LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">User Reports</A></LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Usergoups</A></LI>
        </UL>
      </LI>
       Nav - End Users  
       Nav - Start Content
      <LI class="content"><A class="content" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Content</A>
        <UL>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Manage Pages  »</A>
            <UL>
              <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Add Page  »</A>
                <UL>
                  <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">HTML Page</A></LI>
                  <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Widget Page</A></LI>
                  <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Custom Page</A></LI>
                </UL>
              </LI>
              <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Edit Page</A></LI>
              <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Delete Page</A> </LI>
            </UL>
          </LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Page Statstics</A></LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Page Categories</A></LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Page Tags</A></LI>
        </UL>
      </LI>
       Nav - End Content
       Nav - Start Help 
      <LI class="dashboard"><A class="dashboard" href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Dashboard</A></LI>
       Nav - End Dashboard 
    </UL>-->

<ul id="nav" class="fr">
  <LI class="settings"><A class="settings<?php if( $className == 'options')  : ?> current<?php endif; ?>"  href="<?php print site_url('admin/options') ?>">Settings</A>
    <UL>
      <LI><A href="<?php print site_url('admin/options') ?>">Site settings</A></LI>
      <li <?php if( $className == 'menus' )  : ?> class="current" <?php endif; ?> ><a href="<?php print site_url('admin/menus')  ?>">Menus</a></li>
      <!--  <li <?php if( $className == 'content' and $functionName == 'taxonomy_categories')  : ?> class="current" <?php endif; ?> ><a
      href="<?php print site_url('admin/content/taxonomy_categories')  ?>"> Categories </a></li>-->
      <?php foreach($admin_loaded_plugins as $plugin) : ?>
      <li  <?php if( $pluginName == $plugin['plugin'])  : ?> class="current" <?php endif; ?>><a href="<?php print site_url('admin/plugins/load') ?>/<?php print $plugin['plugin'] ?>"><?php print $plugin['plugin_name'] ?></a></li>
      <?php endforeach;  ?>
    </UL>
  </LI>
  <LI class="users"> <A class="users<?php if(( $className == 'users' and $functionName == 'index') or ( $className == 'users' and $functionName == 'index') ) : ?> current<?php endif; ?>" href="<?php print site_url('admin/users/index')  ?>">Users</A>
    <UL>
      <LI><A href="<?php print site_url('admin/users/index')  ?>">User Manager</A></LI>
      <li <?php if( $className == 'reports')  : ?> class="current" <?php endif; ?> ><a href="<?php print site_url('admin/reports')  ?>">Reports</a></li>
    </UL>
  </LI>
  <li <?php if( $className == 'comments')  : ?> class="current" <?php endif; ?> ><a href="<?php print site_url('admin/comments/index')  ?>">Comments
    <?php $new_comments_counts = $this->comments_model->commentsGetNewCommentsCount ();
	if(intval($new_comments_counts) > 0) : ?>
    <sup style="font-size:7px"><?php print $new_comments_counts ?> new</sup>
    <?php endif;  ?>
    </a></li>
  <LI class="content"> <A class="content<?php if(( $className == 'content' and $functionName == 'posts_manage') or ( $className == 'content' and $functionName == 'posts_edit') ) : ?> current<?php endif; ?>" href="<?php print site_url('admin/content/posts_manage')  ?>">Content</A>
    <UL>
      <!--       <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Manage Pages  »</A>
          <UL>
              <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Add Page  »</A>
                <UL>
                  <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">HTML Page</A></LI>
                  <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Widget Page</A></LI>
                  <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Custom Page</A></LI>
                </UL>
              </LI>
              <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Edit Page</A></LI>
              <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Delete Page</A> </LI>
            </UL>
          </LI>-->
      
      <li  <?php if(( $className == 'content' and $functionName == 'posts_manage') or ( $className == 'content' and $functionName == 'posts_edit') ) : ?>    class="current" <?php endif; ?> > <a href="<?php print site_url('admin/content/posts_manage')  ?>"><img src="<?php print_the_static_files_url() ; ?>icons/page.png"  border="0" alt=" " />Content manager</a></li>
      <li  <?php if(( $className == 'content' and $functionName == 'pages_index') or ( $className == 'content' and $functionName == 'pages_edit') ) : ?>    class="current" <?php endif; ?> > <a href="<?php print site_url('admin/content/pages_index')  ?>"><img src="<?php print_the_static_files_url() ; ?>icons/layout_header.png"  border="0" alt=" " />Pages manager</a></li>
      
      <!--  <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Page Statstics</A></LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Page Categories</A></LI>
          <LI><A href="<?php   print( ADMIN_STATIC_FILES_URL);  ?>/Admin Base.htm">Page Tags</A></LI>-->
    </UL>
  </LI>
</ul>
